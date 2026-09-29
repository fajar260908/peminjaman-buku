<?php

// =====================================================
// MULAI SESSION
// =====================================================

session_start();


// =====================================================
// CEK LOGIN
// =====================================================

if (!isset($_SESSION['id_pengguna'])) {

    header("Location: ../index.php");
    exit;

}


// =====================================================
// PANGGIL MODEL RIWAYAT
// =====================================================

require_once "../models/m_riwayat.php";


// =====================================================
// BUAT OBJECT
// =====================================================

$riwayat = new m_riwayat();


// =====================================================
// AMBIL AKSI
// =====================================================

$aksi = $_GET['aksi'] ?? '';


// =====================================================
// ID PENGGUNA YANG LOGIN
// =====================================================

$id_pengguna = $_SESSION['id_pengguna'];


// =====================================================
// ADMIN HAPUS RIWAYAT
// =====================================================

if (
    $aksi == "hapus"
    && isset($_GET['id_riwayat'])
) {

    // Pastikan yang melakukan adalah admin
    if (
        !isset($_SESSION['role'])
        || $_SESSION['role'] !== 'admin'
    ) {

        header("Location: ../index.php");
        exit;

    }


    // Ambil ID riwayat
    $id_riwayat = $_GET['id_riwayat'];


    // Validasi
    if (empty($id_riwayat)) {

        echo "
        <script>
            alert('ID tidak valid');
            history.back();
        </script>
        ";

        exit;

    }


    // Jalankan hapus
    $hapus = $riwayat->hapus_data($id_riwayat);


    // Cek hasil
    if ($hapus) {

        echo "
        <script>
            alert('Riwayat berhasil dihapus');
            window.location='../views/v_riwayat_peminjaman_buku.php';
        </script>
        ";

    } else {

        echo "
        <script>
            alert('Gagal menghapus riwayat');
            window.location='../views/v_riwayat_peminjaman_buku.php';
        </script>
        ";

    }

    exit;
}


// =====================================================
// JIKA ADMIN
// ADMIN MELIHAT SEMUA RIWAYAT
// =====================================================

if (
    isset($_SESSION['role'])
    && $_SESSION['role'] === 'admin'
) {

    $data_riwayat = $riwayat->tampil_semua_data();


    // View admin
    include "../views/v_riwayat_peminjaman_buku.php";

    exit;
}


// =====================================================
// JIKA USER
// USER HANYA MELIHAT RIWAYAT MILIKNYA
// =====================================================

$data_riwayat = $riwayat->tampil_data($id_pengguna);


// View user
include "../views/v_riwayat_user.php";

exit;

?>   