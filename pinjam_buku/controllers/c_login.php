<?php
// Mulai session
session_start();

// Panggil model pengguna
include "../models/m_pengguna.php";

// Buat object dari class m_pengguna
$pengguna = new m_pengguna();

 $_SESSION['nama_pengguna'] = $data_user->nama_pengguna;


// Cek apakah tombol login ditekan
if (isset($_POST['login'])) {

    // Ambil input username/email dan password dari form
    $username_email = trim($_POST['username_email']);
    $password       = trim($_POST['password']);

    // Panggil function login di model
    $data = $pengguna->login($username_email, $password);

    // Jika data ditemukan (login berhasil)
    if ($data) {

        // Simpan data user ke session
        $_SESSION['id_pengguna']   = $data->id_pengguna;
        $_SESSION['nama_pengguna'] = $data->nama_pengguna;
        $_SESSION['role']          = $data->role;

        // Cek role (admin atau user)
        if ($data->role == "admin") {

            // Redirect ke dashboard admin
            header("Location:../views/v_dasbord_admin.php");

        } else {

            // Redirect ke dashboard user
            header("Location:../views/v_dasbord_user.php");
        }

        exit;

    } else {

        // Jika login gagal tampilkan alert
        echo "<script>
                alert('Username/Email atau Password salah!');
                window.location='../index.php';
              </script>";
        exit;
    }
}
?>