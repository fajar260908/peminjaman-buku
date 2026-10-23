<?php
require_once __DIR__ . '/m_koneksi.php';
require_once __DIR__ . '/../config/app.php';

class m_denda
{
    private mysqli $koneksi;

    public function __construct()
    {
        $this->koneksi = (new m_koneksi())->koneksi;
    }

    public function tampil_semua(): array
    {
        $result = $this->koneksi->query(
            'SELECT d.*, p.kode_buku, p.tanggal_jatuh_tempo, p.tanggal_kembali, b.nama_buku, u.nama_pengguna
             FROM denda d JOIN pinjam p ON p.id_pinjam=d.id_pinjam
             JOIN buku b ON b.kode_buku=p.kode_buku JOIN pengguna u ON u.id_pengguna=p.id_pengguna
             ORDER BY d.id_denda DESC'
        );
        return $this->objects($result);
    }

    public function tampil_milik_user(int $userId): array
    {
        $stmt = $this->koneksi->prepare(
            'SELECT d.*, p.kode_buku, b.nama_buku, u.nama_pengguna
             FROM denda d JOIN pinjam p ON p.id_pinjam=d.id_pinjam
             JOIN buku b ON b.kode_buku=p.kode_buku JOIN pengguna u ON u.id_pengguna=p.id_pengguna
             WHERE p.id_pengguna=? ORDER BY d.id_denda DESC'
        );
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $this->objects($stmt->get_result());
    }

    public function cari($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) return null;
        $stmt = $this->koneksi->prepare('SELECT * FROM denda WHERE id_denda=? LIMIT 1');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_object() ?: null;
    }

    public function peminjaman_belum_denda(): array
    {
        $result = $this->koneksi->query(
            "SELECT p.id_pinjam, p.kode_buku, p.tanggal_kembali, b.nama_buku, u.nama_pengguna
             FROM pinjam p JOIN buku b ON b.kode_buku=p.kode_buku JOIN pengguna u ON u.id_pengguna=p.id_pengguna
             WHERE p.status='dikembalikan' ORDER BY p.id_pinjam DESC"
        );
        return $this->objects($result);
    }

    public function tambah($idPinjam, $jenis, $hari, $jumlah): bool
    {
        $id = filter_var($idPinjam, FILTER_VALIDATE_INT);
        $days = filter_var($hari, FILTER_VALIDATE_INT);
        $amount = filter_var($jumlah, FILTER_VALIDATE_FLOAT);
        $allowed = ['Terlambat Mengembalikan Buku', 'Kerusakan', 'Hilang'];
        if (!$id || !in_array($jenis, $allowed, true) || $days === false || $days < 0 || $amount === false || $amount < 0) return false;
        if ($jenis === 'Terlambat Mengembalikan Buku') {
            if ($days < 1) return false;
            $amount = $days * DENDA_PER_HARI;
        } elseif ($amount <= 0) {
            return false;
        }

        $stmt = $this->koneksi->prepare("SELECT tanggal_kembali FROM pinjam WHERE id_pinjam=? AND status='dikembalikan' LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $loan = $stmt->get_result()->fetch_object();
        if (!$loan || empty($loan->tanggal_kembali)) return false;

        // Hindari duplikasi jenis denda yang sama; jenis berbeda tetap boleh dicatat.
        $stmt = $this->koneksi->prepare('SELECT id_denda FROM denda WHERE id_pinjam=? AND jenis_denda=? LIMIT 1');
        $stmt->bind_param('is', $id, $jenis);
        $stmt->execute();
        if ($stmt->get_result()->fetch_object()) return false;

        $status = 'Belum Dibayar';
        $stmt = $this->koneksi->prepare(
            'INSERT INTO denda (id_pinjam, tanggal_pengembalian, jenis_denda, jumlah_hari_keterlambatan, status_denda, jumlah_denda)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->bind_param('issisd', $id, $loan->tanggal_kembali, $jenis, $days, $status, $amount);
        try {
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            error_log('Fine insert failed: ' . $e->getMessage());
            return false;
        }
    }

    public function update($idDenda, $jenis, $hari, $jumlah, $status): bool
    {
        $id = filter_var($idDenda, FILTER_VALIDATE_INT);
        $days = filter_var($hari, FILTER_VALIDATE_INT);
        $amount = filter_var($jumlah, FILTER_VALIDATE_FLOAT);
        $allowedTypes = ['Terlambat Mengembalikan Buku', 'Kerusakan', 'Hilang'];
        $allowedStatus = ['Sudah Dibayar', 'Belum Dibayar'];
        if (!$id || !in_array($jenis, $allowedTypes, true) || !in_array($status, $allowedStatus, true) || $days === false || $days < 0 || $amount === false || $amount < 0) return false;
        if ($jenis === 'Terlambat Mengembalikan Buku') {
            if ($days < 1) return false;
            $amount = $days * DENDA_PER_HARI;
        } elseif ($amount <= 0) return false;
        $stmt = $this->koneksi->prepare('UPDATE denda SET jenis_denda=?, jumlah_hari_keterlambatan=?, jumlah_denda=?, status_denda=? WHERE id_denda=?');
        $stmt->bind_param('sidsi', $jenis, $days, $amount, $status, $id);
        try { return $stmt->execute(); } catch (mysqli_sql_exception $e) {
            error_log('Fine update failed: ' . $e->getMessage());
            return false;
        }
    }

    public function hapus($id): bool
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) return false;
        $stmt = $this->koneksi->prepare('DELETE FROM denda WHERE id_denda=?');
        $stmt->bind_param('i', $id);
        return $stmt->execute() && $stmt->affected_rows === 1;
    }

    private function objects(mysqli_result $result): array
    {
        $rows = [];
        while ($row = $result->fetch_object()) $rows[] = $row;
        return $rows;
    }
}
