<?php
require_once __DIR__ . '/m_koneksi.php';
require_once __DIR__ . '/../config/app.php';

class m_pinjam
{
    public mysqli $koneksi;

    public function __construct()
    {
        $this->koneksi = (new m_koneksi())->koneksi;
    }

    public function request_pinjam($kode_buku, $id_pengguna, $tanggal_pinjam, $jumlah_pinjam): bool
    {
        $bookId = filter_var($kode_buku, FILTER_VALIDATE_INT);
        $userId = filter_var($id_pengguna, FILTER_VALIDATE_INT);
        $qty = filter_var($jumlah_pinjam, FILTER_VALIDATE_INT);
        $date = (string)$tanggal_pinjam;
        if (!$bookId || !$userId || $qty === false || $qty < 1 || !$this->validDate($date) || $date > date('Y-m-d')) return false;

        // Stok divalidasi kembali saat admin menyetujui, sehingga pengajuan tidak mengunci stok.
        $stmt = $this->koneksi->prepare(
            "INSERT INTO pinjam (kode_buku, id_pengguna, tanggal_pinjam, tanggal_kembali, tanggal_jatuh_tempo, status, jumlah_pinjam)
             VALUES (?, ?, ?, NULL, NULL, 'pending', ?)"
        );
        $qtyStr = (string)$qty;
        $stmt->bind_param('iiss', $bookId, $userId, $date, $qtyStr);
        return $stmt->execute();
    }

    public function tampil_pinjam_by_id($id_pinjam)
    {
        $id = filter_var($id_pinjam, FILTER_VALIDATE_INT);
        if (!$id) return null;
        $stmt = $this->koneksi->prepare(
            'SELECT p.*, b.nama_buku, b.mata_pelajaran, b.kelas, u.nama_pengguna
             FROM pinjam p
             JOIN buku b ON p.kode_buku = b.kode_buku
             JOIN pengguna u ON p.id_pengguna = u.id_pengguna
             WHERE p.id_pinjam = ? LIMIT 1'
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_object() ?: null;
    }

    public function tampil_buku_dipinjam($id_pengguna): array
    {
        $id = filter_var($id_pengguna, FILTER_VALIDATE_INT);
        if (!$id) return [];
        $stmt = $this->koneksi->prepare(
            "SELECT p.*, b.nama_buku, b.mata_pelajaran, b.kelas, u.nama_pengguna
             FROM pinjam p JOIN buku b ON p.kode_buku=b.kode_buku
             JOIN pengguna u ON p.id_pengguna=u.id_pengguna
             WHERE p.id_pengguna=? AND p.status IN ('dipinjam','menunggu_verifikasi')
             ORDER BY p.id_pinjam DESC"
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $this->objects($stmt->get_result());
    }

    public function ajukan_pengembalian($id_pinjam): bool
    {
        $id = filter_var($id_pinjam, FILTER_VALIDATE_INT);
        if (!$id) return false;
        $stmt = $this->koneksi->prepare("UPDATE pinjam SET status='menunggu_verifikasi' WHERE id_pinjam=? AND status='dipinjam'");
        $stmt->bind_param('i', $id);
        return $stmt->execute() && $stmt->affected_rows === 1;
    }

    /** Atomically verifies a loan, checks/decrements stock and saves a due date. */
    public function verifikasi_pinjam($id_pinjam, $tanggal_jatuh_tempo): bool
    {
        $id = filter_var($id_pinjam, FILTER_VALIDATE_INT);
        $dueDate = (string)$tanggal_jatuh_tempo;
        if (!$id || !$this->validDate($dueDate) || $dueDate < date('Y-m-d')) return false;

        $this->koneksi->begin_transaction();
        try {
            $stmt = $this->koneksi->prepare('SELECT kode_buku, jumlah_pinjam, status FROM pinjam WHERE id_pinjam=? FOR UPDATE');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $loan = $stmt->get_result()->fetch_object();
            if (!$loan || $loan->status !== 'pending') {
                $this->koneksi->rollback();
                return false;
            }
            $bookId = (int)$loan->kode_buku;
            $qty = (int)$loan->jumlah_pinjam;
            if ($qty < 1) {
                $this->koneksi->rollback();
                return false;
            }

            $stmt = $this->koneksi->prepare('SELECT stok FROM buku WHERE kode_buku=? FOR UPDATE');
            $stmt->bind_param('i', $bookId);
            $stmt->execute();
            $book = $stmt->get_result()->fetch_object();
            if (!$book || (int)$book->stok < $qty) {
                $this->koneksi->rollback();
                return false;
            }

            $stmt = $this->koneksi->prepare('UPDATE buku SET stok=CAST(stok AS UNSIGNED)-? WHERE kode_buku=?');
            $stmt->bind_param('ii', $qty, $bookId);
            $stmt->execute();
            $stmt = $this->koneksi->prepare("UPDATE pinjam SET status='dipinjam', tanggal_jatuh_tempo=? WHERE id_pinjam=? AND status='pending'");
            $stmt->bind_param('si', $dueDate, $id);
            $stmt->execute();
            if ($stmt->affected_rows !== 1) {
                $this->koneksi->rollback();
                return false;
            }
            $this->koneksi->commit();
            return true;
        } catch (Throwable $e) {
            $this->koneksi->rollback();
            error_log('Loan approval failed: ' . $e->getMessage());
            return false;
        }
    }

    /** Atomically returns a loan, restores stock, records history, and creates an overdue fine. */
    public function kembalikan_buku($id_pinjam): bool
    {
        $id = filter_var($id_pinjam, FILTER_VALIDATE_INT);
        if (!$id) return false;
        $today = date('Y-m-d');
        $this->koneksi->begin_transaction();
        try {
            $stmt = $this->koneksi->prepare(
                'SELECT p.*, b.nama_buku, u.nama_pengguna
                 FROM pinjam p JOIN buku b ON p.kode_buku=b.kode_buku
                 JOIN pengguna u ON p.id_pengguna=u.id_pengguna
                 WHERE p.id_pinjam=? FOR UPDATE'
            );
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $loan = $stmt->get_result()->fetch_object();
            if (!$loan || $loan->status !== 'menunggu_verifikasi') {
                $this->koneksi->rollback();
                return false;
            }
            $bookId = (int)$loan->kode_buku;
            $qty = (int)$loan->jumlah_pinjam;
            if ($qty < 1) {
                $this->koneksi->rollback();
                return false;
            }

            $stmt = $this->koneksi->prepare("UPDATE pinjam SET status='dikembalikan', tanggal_kembali=? WHERE id_pinjam=? AND status='menunggu_verifikasi'");
            $stmt->bind_param('si', $today, $id);
            $stmt->execute();
            if ($stmt->affected_rows !== 1) {
                $this->koneksi->rollback();
                return false;
            }
            $stmt = $this->koneksi->prepare('UPDATE buku SET stok=CAST(stok AS UNSIGNED)+? WHERE kode_buku=?');
            $stmt->bind_param('ii', $qty, $bookId);
            $stmt->execute();

            $stmt = $this->koneksi->prepare(
                'INSERT INTO riwayat_peminjaman (nama_buku, nama_peminjam, tanggal_peminjaman, tanggal_pengembalian, kode_buku, jumlah_pinjam)
                 VALUES (?, ?, ?, ?, ?, ?)'
            );
            $qtyStr = (string)$qty;
            $stmt->bind_param('ssssis', $loan->nama_buku, $loan->nama_pengguna, $loan->tanggal_pinjam, $today, $bookId, $qtyStr);
            $stmt->execute();

            $due = (string)($loan->tanggal_jatuh_tempo ?? '');
            if ($this->validDate($due) && $today > $due) {
                $lateDays = (int)(new DateTimeImmutable($due))->diff(new DateTimeImmutable($today))->days;
                $fineAmount = $lateDays * DENDA_PER_HARI;
                $kind = 'Terlambat Mengembalikan Buku';
                $stmt = $this->koneksi->prepare('SELECT id_denda FROM denda WHERE id_pinjam=? AND jenis_denda=? FOR UPDATE');
                $stmt->bind_param('is', $id, $kind);
                $stmt->execute();
                $existingFine = $stmt->get_result()->fetch_object();
                if (!$existingFine && $lateDays > 0) {
                    $status = 'Belum Dibayar';
                    $stmt = $this->koneksi->prepare(
                        'INSERT INTO denda (id_pinjam, tanggal_pengembalian, jenis_denda, jumlah_hari_keterlambatan, status_denda, jumlah_denda)
                         VALUES (?, ?, ?, ?, ?, ?)'
                    );
                    $stmt->bind_param('issisi', $id, $today, $kind, $lateDays, $status, $fineAmount);
                    $stmt->execute();
                }
            }

            $this->koneksi->commit();
            return true;
        } catch (Throwable $e) {
            $this->koneksi->rollback();
            error_log('Loan return failed: ' . $e->getMessage());
            return false;
        }
    }

    public function tolak_pengembalian($id_pinjam): bool
    {
        $id = filter_var($id_pinjam, FILTER_VALIDATE_INT);
        if (!$id) return false;
        $stmt = $this->koneksi->prepare("UPDATE pinjam SET status='dipinjam' WHERE id_pinjam=? AND status='menunggu_verifikasi'");
        $stmt->bind_param('i', $id);
        return $stmt->execute() && $stmt->affected_rows === 1;
    }

    public function tampil_menunggu_pengembalian(): array
    {
        $result = $this->koneksi->query(
            "SELECT p.*, b.nama_buku, u.nama_pengguna FROM pinjam p
             JOIN buku b ON p.kode_buku=b.kode_buku JOIN pengguna u ON p.id_pengguna=u.id_pengguna
             WHERE p.status='menunggu_verifikasi' ORDER BY p.id_pinjam DESC"
        );
        return $this->objects($result);
    }

    public function tampil_pending(): array
    {
        $result = $this->koneksi->query(
            "SELECT p.*, b.nama_buku, b.mata_pelajaran, u.nama_pengguna FROM pinjam p
             JOIN buku b ON p.kode_buku=b.kode_buku JOIN pengguna u ON p.id_pengguna=u.id_pengguna
             WHERE p.status='pending' ORDER BY p.id_pinjam DESC"
        );
        return $this->objects($result);
    }

    private function objects(mysqli_result $result): array
    {
        $rows = [];
        while ($row = $result->fetch_object()) $rows[] = $row;
        return $rows;
    }

    private function validDate(string $date): bool
    {
        $parsed = DateTimeImmutable::createFromFormat('!Y-m-d', $date);
        return $parsed !== false && $parsed->format('Y-m-d') === $date;
    }
}
