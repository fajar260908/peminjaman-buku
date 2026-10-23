<?php
require_once __DIR__ . '/m_koneksi.php';

class m_riwayat
{
    public mysqli $koneksi;

    public function __construct()
    {
        $this->koneksi = (new m_koneksi())->koneksi;
    }

    public function tambah_data($nama_buku, $nama_peminjam, $tgl_pinjam, $tgl_kembali, $kode_buku, $jumlah_pinjam): bool
    {
        $bookId = filter_var($kode_buku, FILTER_VALIDATE_INT);
        if (!$bookId) return false;
        $qty = (string)$jumlah_pinjam;
        $stmt = $this->koneksi->prepare('INSERT INTO riwayat_peminjaman (nama_buku,nama_peminjam,tanggal_peminjaman,tanggal_pengembalian,kode_buku,jumlah_pinjam) VALUES (?,?,?,?,?,?)');
        $stmt->bind_param('ssssis', $nama_buku, $nama_peminjam, $tgl_pinjam, $tgl_kembali, $bookId, $qty);
        return $stmt->execute();
    }

    public function tampil_semua_data(): array
    {
        $result = $this->koneksi->query('SELECT * FROM riwayat_peminjaman ORDER BY id_riwayat DESC');
        $rows = [];
        while ($row = $result->fetch_object()) $rows[] = $row;
        return $rows;
    }

    public function tampil_data($id_pengguna = null): array
    {
        if ($id_pengguna === null) {
            if (session_status() !== PHP_SESSION_ACTIVE) session_start();
            $id_pengguna = $_SESSION['id_pengguna'] ?? null;
        }
        $id = filter_var($id_pengguna, FILTER_VALIDATE_INT);
        if (!$id) return [];
        $stmt = $this->koneksi->prepare('SELECT u.nama_pengguna FROM pengguna u WHERE u.id_pengguna=? LIMIT 1');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_object();
        if (!$user) return [];
        $stmt = $this->koneksi->prepare('SELECT * FROM riwayat_peminjaman WHERE nama_peminjam=? ORDER BY id_riwayat DESC');
        $stmt->bind_param('s', $user->nama_pengguna);
        $stmt->execute();
        $rows = [];
        $result = $stmt->get_result();
        while ($row = $result->fetch_object()) $rows[] = $row;
        return $rows;
    }

    public function hapus_data($id_riwayat): bool
    {
        $id = filter_var($id_riwayat, FILTER_VALIDATE_INT);
        if (!$id) return false;
        $stmt = $this->koneksi->prepare('DELETE FROM riwayat_peminjaman WHERE id_riwayat=?');
        $stmt->bind_param('i', $id);
        return $stmt->execute() && $stmt->affected_rows === 1;
    }
}
