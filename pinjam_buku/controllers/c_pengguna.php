<?php
// Panggil model pengguna
require_once "../models/m_pengguna.php";

// Buat object dari class m_pengguna
$user = new m_pengguna();

// Ambil parameter aksi dari URL
$aksi = $_GET['aksi'] ?? '';


if ($aksi === "hapus") {

    // Ambil id dari URL lalu hapus data user
    $user->hapus_data($_GET['id']);

    // Redirect kembali ke halaman data user
    header("Location: ../views/v_data_user.php");
    exit;
}


if ($aksi === "tambah" && $_SERVER['REQUEST_METHOD'] === 'GET') {

    // Menampilkan form tambah user
    include "../views/v_tambah_pengguna.php";
    exit;
}


if ($aksi === "tambah" && $_SERVER['REQUEST_METHOD'] === 'POST') {

    // Kirim data dari form ke model
    $user->tambah_data(
        $_POST['nama_pengguna'],
        $_POST['email'],
        $_POST['jenis_kelamin'],
        $_POST['tanggal_lahir_pengguna'],
        $_POST['alamat'],
        $_POST['password'],
        $_POST['role']
    );

    // Redirect ke halaman data user setelah berhasil
    header("Location: ../views/v_data_user.php");
    exit;
}


if ($aksi === "edit") {

    // Ambil data user berdasarkan id
    $users = $user->tampil_data_by_id($_GET['id']);

    // Tampilkan form edit
    include "../views/v_edit_pengguna.php";
    exit;
}


if ($aksi === "update") {

    // Kirim data update ke model
    $user->ubah_data(
        $_POST['id_user'],
        $_POST['nama_pengguna'],
        $_POST['email'],
        $_POST['jenis_kelamin'],
        $_POST['tanggal_lahir_pengguna'],
        $_POST['alamat'],
        $_POST['password'], 
        $_POST['role']
    );

    // Redirect ke halaman data user
    header("Location: ../views/v_data_user.php");
    exit;
}



// Ambil semua data user
$pengguna = $user->tampil_data();

// Tampilkan ke view
include "../views/v_data_user.php";
?>