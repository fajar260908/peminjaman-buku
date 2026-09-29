<?php

// =====================================================
// KONEKSI DATABASE
// =====================================================

include_once "m_koneksi.php";


// =====================================================
// CLASS PEMINJAMAN
// =====================================================

class m_pinjam
{
    public $koneksi;


    // =====================================================
    // CONSTRUCTOR
    // =====================================================

    function __construct()
    {
        $db = new m_koneksi();
        $this->koneksi = $db->koneksi;
    }


    // =====================================================
    // TAMBAH PEMINJAMAN
    // =====================================================

    function tambah_pinjam(
        $kode_buku,
        $id_pengguna,
        $tanggal_pinjam,
        $tanggal_kembali,
        $jumlah_pinjam
    ) {

        $tanggal_kembali = ($tanggal_kembali == NULL)
            ? "NULL"
            : "'$tanggal_kembali'";

        $sql = "INSERT INTO pinjam
                (
                    id_pinjam,
                    kode_buku,
                    id_pengguna,
                    tanggal_pinjam,
                    tanggal_kembali,
                    status,
                    jumlah_pinjam
                )
                VALUES
                (
                    NULL,
                    '$kode_buku',
                    '$id_pengguna',
                    '$tanggal_pinjam',
                    $tanggal_kembali,
                    'dipinjam',
                    '$jumlah_pinjam'
                )";

        return mysqli_query($this->koneksi, $sql);
    }


    // =====================================================
    // TAMPIL DATA PEMINJAMAN BERDASARKAN ID
    // =====================================================

    function tampil_pinjam_by_id($id_pinjam)
    {

        $sql = "SELECT
                    pinjam.*,
                    buku.nama_buku,
                    pengguna.nama_pengguna
                FROM pinjam
                JOIN buku
                    ON pinjam.kode_buku = buku.kode_buku
                JOIN pengguna
                    ON pinjam.id_pengguna = pengguna.id_pengguna
                WHERE pinjam.id_pinjam='$id_pinjam'";

        $q = mysqli_query($this->koneksi, $sql);

        return mysqli_fetch_object($q);
    }


    // =====================================================
    // TAMPIL BUKU YANG SEDANG DIPINJAM
    // HANYA UNTUK USER YANG SEDANG LOGIN
    // =====================================================

    function tampil_buku_dipinjam($id_pengguna)
    {

        $sql = "SELECT
                    pinjam.*,
                    buku.nama_buku,
                    pengguna.nama_pengguna
                FROM pinjam
                JOIN buku
                    ON pinjam.kode_buku = buku.kode_buku
                JOIN pengguna
                    ON pinjam.id_pengguna = pengguna.id_pengguna
                WHERE pinjam.id_pengguna='$id_pengguna'
                AND pinjam.status IN
                    ('dipinjam', 'menunggu_verifikasi')
                ORDER BY pinjam.id_pinjam DESC";

        $q = mysqli_query($this->koneksi, $sql);

        $data = [];

        while ($d = mysqli_fetch_object($q)) {
            $data[] = $d;
        }

        return $data;
    }


    // =====================================================
    // AJUKAN PENGEMBALIAN
    // =====================================================

    function ajukan_pengembalian($id_pinjam)
    {

        $sql = "UPDATE pinjam
                SET status='menunggu_verifikasi'
                WHERE id_pinjam='$id_pinjam'
                AND status='dipinjam'";

        return mysqli_query($this->koneksi, $sql);
    }


    // =====================================================
    // ADMIN MENERIMA PENGEMBALIAN
    // =====================================================

    function kembalikan_buku($id_pinjam)
    {

        $tanggal_kembali = date("Y-m-d");

        $sql = "UPDATE pinjam
                SET status='dikembalikan',
                    tanggal_kembali='$tanggal_kembali'
                WHERE id_pinjam='$id_pinjam'
                AND status='menunggu_verifikasi'";

        return mysqli_query($this->koneksi, $sql);
    }


    // =====================================================
    // ADMIN MENOLAK PENGEMBALIAN
    // =====================================================

    function tolak_pengembalian($id_pinjam)
    {

        $sql = "UPDATE pinjam
                SET status='dipinjam'
                WHERE id_pinjam='$id_pinjam'
                AND status='menunggu_verifikasi'";

        return mysqli_query($this->koneksi, $sql);
    }


    // =====================================================
    // TAMPIL PEMINJAMAN YANG MENUNGGU VERIFIKASI
    // KHUSUS ADMIN
    // =====================================================

    function tampil_menunggu_pengembalian()
    {

        $sql = "SELECT
                    pinjam.*,
                    buku.nama_buku,
                    pengguna.nama_pengguna
                FROM pinjam
                JOIN buku
                    ON pinjam.kode_buku = buku.kode_buku
                JOIN pengguna
                    ON pinjam.id_pengguna = pengguna.id_pengguna
                WHERE pinjam.status='menunggu_verifikasi'
                ORDER BY pinjam.id_pinjam DESC";

        $q = mysqli_query($this->koneksi, $sql);

        $data = [];

        while ($d = mysqli_fetch_object($q)) {
            $data[] = $d;
        }

        return $data;
    }


    // =====================================================
    // REQUEST PEMINJAMAN
    // =====================================================

    function request_pinjam(
        $kode_buku,
        $id_pengguna,
        $tanggal_pinjam,
        $jumlah_pinjam
    ) {

        $sql = "INSERT INTO pinjam
                (
                    id_pinjam,
                    kode_buku,
                    id_pengguna,
                    tanggal_pinjam,
                    tanggal_kembali,
                    status,
                    jumlah_pinjam
                )
                VALUES
                (
                    NULL,
                    '$kode_buku',
                    '$id_pengguna',
                    '$tanggal_pinjam',
                    NULL,
                    'pending',
                    '$jumlah_pinjam'
                )";

        return mysqli_query($this->koneksi, $sql);
    }


    // =====================================================
    // TAMPIL REQUEST PEMINJAMAN
    // KHUSUS ADMIN
    // =====================================================

    function tampil_pending()
    {

        $sql = "SELECT
                    pinjam.*,
                    buku.nama_buku,
                    pengguna.nama_pengguna
                FROM pinjam
                JOIN buku
                    ON pinjam.kode_buku = buku.kode_buku
                JOIN pengguna
                    ON pinjam.id_pengguna = pengguna.id_pengguna
                WHERE pinjam.status='pending'
                ORDER BY pinjam.id_pinjam DESC";

        $q = mysqli_query($this->koneksi, $sql);

        $data = [];

        while ($d = mysqli_fetch_object($q)) {
            $data[] = $d;
        }

        return $data;
    }


    // =====================================================
    // ADMIN MEMVERIFIKASI PEMINJAMAN
    // =====================================================

    function verifikasi_pinjam($id_pinjam, $tanggal_kembali)
    {

        $sql = "UPDATE pinjam
                SET status='dipinjam',
                    tanggal_kembali='$tanggal_kembali'
                WHERE id_pinjam='$id_pinjam'";

        return mysqli_query($this->koneksi, $sql);
    }
}

?>