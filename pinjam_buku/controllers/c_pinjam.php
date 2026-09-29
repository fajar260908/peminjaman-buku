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
// PANGGIL MODEL
// =====================================================

require_once "../models/m_buku.php";
require_once "../models/m_pinjam.php";


// =====================================================
// BUAT OBJECT
// =====================================================

$buku   = new m_buku();
$pinjam = new m_pinjam();


// =====================================================
// AMBIL AKSI DARI URL
// =====================================================

$aksi = $_GET['aksi'] ?? '';


// =====================================================
// ID USER YANG SEDANG LOGIN
// =====================================================

$id_pengguna = $_SESSION['id_pengguna'];


// =====================================================
// PROSES PINJAM
// =====================================================

if ($aksi == "pinjam") {

    // Ambil data dari form
    $kode_buku     = $_POST['kode_buku'];
    $tgl_pinjam    = $_POST['tanggal_pinjam'];
    $jumlah_pinjam = $_POST['jumlah_pinjam'];

    // Gunakan ID pengguna dari SESSION
    // Jangan mengambil id_pengguna dari POST
    $id_pengguna = $_SESSION['id_pengguna'];


    // =================================================
    // AMBIL DATA BUKU
    // =================================================

    $data_buku = $buku->tampil_data_by_kode($kode_buku);


    // Validasi buku
    if (!$data_buku) {

        die("Buku tidak ditemukan");

    }


    // =================================================
    // CEK STOK
    // =================================================

    $stok_lama = $data_buku->stok;


    if ($jumlah_pinjam > $stok_lama) {

        echo "
        <script>
            alert('Stok tidak cukup');
            window.history.back();
        </script>
        ";

        exit;
    }


    // =================================================
    // SIMPAN REQUEST PEMINJAMAN
    // =================================================

    $pinjam->request_pinjam(
        $kode_buku,
        $id_pengguna,
        $tgl_pinjam,
        $jumlah_pinjam
    );


    // =================================================
    // NOTIFIKASI
    // =================================================

    echo "
    <script>
        alert('Pengajuan peminjaman berhasil, menunggu verifikasi admin');
        window.location='../views/v_dasbord_user.php';
    </script>
    ";

    exit;
}


// =====================================================
// TAMPIL BUKU YANG SEDANG DIPINJAM
// HANYA MILIK USER YANG LOGIN
// =====================================================

if ($aksi == "sedang_dipinjam") {

    // Ambil data berdasarkan user yang sedang login
    $data = $pinjam->tampil_buku_dipinjam($id_pengguna);


    // Kirim data ke halaman view
    include "../views/sedang_dipinjam.php";

    exit;
}

?>