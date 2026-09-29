<?php

// =====================================================
// KONEKSI DATABASE
// =====================================================

include_once "m_koneksi.php";


// =====================================================
// CLASS RIWAYAT
// =====================================================

class m_riwayat
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
    // TAMBAH DATA RIWAYAT
    // =====================================================

    function tambah_data(
        $nama_buku,
        $nama_peminjam,
        $tgl_pinjam,
        $tgl_kembali,
        $kode_buku,
        $jumlah_pinjam
    ) {

        $sql = "INSERT INTO riwayat_peminjaman
                (
                    nama_buku,
                    nama_peminjam,
                    tanggal_peminjaman,
                    tanggal_pengembalian,
                    kode_buku,
                    jumlah_pinjam
                )
                VALUES
                (
                    '$nama_buku',
                    '$nama_peminjam',
                    '$tgl_pinjam',
                    '$tgl_kembali',
                    '$kode_buku',
                    '$jumlah_pinjam'
                )";

        return mysqli_query($this->koneksi, $sql);
    }


    // =====================================================
    // TAMPIL SEMUA DATA RIWAYAT
    // UNTUK ADMIN
    // =====================================================

    function tampil_semua_data()
    {

        $sql = "SELECT *
                FROM riwayat_peminjaman
                ORDER BY id_riwayat DESC";

        $q = mysqli_query($this->koneksi, $sql);

        $data = [];

        if ($q) {

            while ($d = mysqli_fetch_object($q)) {
                $data[] = $d;
            }

        }

        return $data;
    }


    // =====================================================
    // TAMPIL RIWAYAT USER
    // HANYA MILIK USER YANG LOGIN
    // =====================================================

    function tampil_data($id_pengguna = null)
    {

        // -------------------------------------------------
        // Jika ID tidak dikirim dari view/controller,
        // ambil otomatis dari session
        // -------------------------------------------------

        if ($id_pengguna === null) {

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (!isset($_SESSION['id_pengguna'])) {
                return [];
            }

            $id_pengguna = $_SESSION['id_pengguna'];
        }


        // -------------------------------------------------
        // Amankan ID pengguna
        // -------------------------------------------------

        $id_pengguna = mysqli_real_escape_string(
            $this->koneksi,
            $id_pengguna
        );


        // -------------------------------------------------
        // Cari nama pengguna berdasarkan ID
        // -------------------------------------------------

        $sql_user = "SELECT nama_pengguna
                     FROM pengguna
                     WHERE id_pengguna='$id_pengguna'
                     LIMIT 1";

        $q_user = mysqli_query(
            $this->koneksi,
            $sql_user
        );


        if (!$q_user) {
            return [];
        }


        $user = mysqli_fetch_assoc($q_user);


        // -------------------------------------------------
        // Jika user tidak ditemukan
        // -------------------------------------------------

        if (!$user) {
            return [];
        }


        $nama_pengguna = mysqli_real_escape_string(
            $this->koneksi,
            $user['nama_pengguna']
        );


        // -------------------------------------------------
        // Ambil riwayat hanya milik user tersebut
        // -------------------------------------------------

        $sql = "SELECT *
                FROM riwayat_peminjaman
                WHERE nama_peminjam='$nama_pengguna'
                ORDER BY id_riwayat DESC";


        $q = mysqli_query(
            $this->koneksi,
            $sql
        );


        $data = [];


        if ($q) {

            while ($d = mysqli_fetch_object($q)) {
                $data[] = $d;
            }

        }


        return $data;
    }


    // =====================================================
    // HAPUS RIWAYAT
    // =====================================================

    function hapus_data($id_riwayat)
    {

        $id_riwayat = mysqli_real_escape_string(
            $this->koneksi,
            $id_riwayat
        );


        $sql = "DELETE FROM riwayat_peminjaman
                WHERE id_riwayat='$id_riwayat'";


        return mysqli_query(
            $this->koneksi,
            $sql
        );
    }
}

?>   