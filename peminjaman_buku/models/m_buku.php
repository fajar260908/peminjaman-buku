<?php
require_once __DIR__ . '/m_koneksi.php';

class m_buku
{
    private mysqli $koneksi;

    public function __construct()
    {
        $this->koneksi = (new m_koneksi())->koneksi;
    }

    public function tambah_data($nama, $mapel, $kelas, $semester, $stok): bool
    {
        $nama = trim((string)$nama);
        $mapel = trim((string)$mapel);
        $kelas = trim((string)$kelas);
        $semester = trim((string)$semester);
        $stok = filter_var($stok, FILTER_VALIDATE_INT);
        if ($nama === '' || $mapel === '' || $kelas === '' || !in_array($semester, ['1', '2', 'Umum'], true) || $stok === false || $stok < 0) {
            return false;
        }

        // Kolom lama tetap diisi agar kompatibel dengan basis data versi sebelumnya.
        $gambar = '';
        $tanggalRilis = '2000-01-01';
        $pencipta = '';
        $stmt = $this->koneksi->prepare(
            'INSERT INTO buku (gambar, nama_buku, tanggal_rilis, pencipta, stok, mata_pelajaran, kelas, semester)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stokStr = (string)$stok;
        $stmt->bind_param('ssssssss', $gambar, $nama, $tanggalRilis, $pencipta, $stokStr, $mapel, $kelas, $semester);
        return $stmt->execute();
    }

    public function hapus_data($kode): bool
    {
        $id = filter_var($kode, FILTER_VALIDATE_INT);
        if (!$id) return false;
        $stmt = $this->koneksi->prepare('DELETE FROM buku WHERE kode_buku = ?');
        $stmt->bind_param('i', $id);
        try {
            return $stmt->execute() && $stmt->affected_rows > 0;
        } catch (mysqli_sql_exception $e) {
            // Foreign key prevents deleting books still referenced by loan/history records.
            error_log('Book delete failed: ' . $e->getMessage());
            return false;
        }
    }

    public function tampil_data(): array
    {
        $result = $this->koneksi->query('SELECT * FROM buku ORDER BY kode_buku DESC');
        return $this->toObjects($result);
    }

    private function toObjects(mysqli_result $result): array
    {
        $rows = [];
        while ($row = $result->fetch_object()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function tampil_data_by_kode($kode)
    {
        $id = filter_var($kode, FILTER_VALIDATE_INT);
        if (!$id) return null;
        $stmt = $this->koneksi->prepare('SELECT * FROM buku WHERE kode_buku = ? LIMIT 1');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_object() ?: null;
    }

    public function update_data($kode, $nama, $mapel, $kelas, $semester, $stok): bool
    {
        $id = filter_var($kode, FILTER_VALIDATE_INT);
        $nama = trim((string)$nama);
        $mapel = trim((string)$mapel);
        $kelas = trim((string)$kelas);
        $semester = trim((string)$semester);
        $stok = filter_var($stok, FILTER_VALIDATE_INT);
        if (!$id || $nama === '' || $mapel === '' || $kelas === '' || !in_array($semester, ['1', '2', 'Umum'], true) || $stok === false || $stok < 0) {
            return false;
        }
        $stokStr = (string)$stok;
        $stmt = $this->koneksi->prepare(
            'UPDATE buku SET nama_buku = ?, mata_pelajaran = ?, kelas = ?, semester = ?, stok = ? WHERE kode_buku = ?'
        );
        $stmt->bind_param('sssssi', $nama, $mapel, $kelas, $semester, $stokStr, $id);
        return $stmt->execute();
    }

    public function update_stok($kode_buku, $stok_baru): bool
    {
        $id = filter_var($kode_buku, FILTER_VALIDATE_INT);
        $stok = filter_var($stok_baru, FILTER_VALIDATE_INT);
        if (!$id || $stok === false || $stok < 0) return false;
        $stokStr = (string)$stok;
        $stmt = $this->koneksi->prepare('UPDATE buku SET stok = ? WHERE kode_buku = ?');
        $stmt->bind_param('si', $stokStr, $id);
        return $stmt->execute();
    }
}
