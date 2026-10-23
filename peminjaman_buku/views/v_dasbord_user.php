<?php
require_once __DIR__ . '/../config/app.php';
session_start();


if (!isset($_SESSION['role'])) {
    header("Location: ../index.php");
    exit;
}


if ($_SESSION['role'] !== 'user') {
    header("Location: v_dasbord_admin.php");
    exit;
}


$nama_user = $_SESSION['nama_pengguna'] ?? 'User';
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard User</title>
<link rel="stylesheet" href="<?= h(app_url('asset/dasbord.css')) ?>">
<link rel="stylesheet" href="<?= h(app_url('asset/app.css')) ?>">
</head>
<body>


<div class="navbar">
    <div class="menu">

        <div class="logo">📚 Perpustakaan</div>

        <div class="menu-right">
           
            <a href="../controllers/c_logout.php">🚪 Logout</a>
        </div>

    </div>
</div>


<div class="container">

    <div class="hero">
        <h1>Selamat Datang, <?= htmlspecialchars($nama_user) ?> 👋</h1>
        <p>peminjaman buku dengan mudah, cepat, dan modern melalui sistem perpustakaan digital.</p>
    </div>

    <div class="grid">

        <div class="card">
            <h3>📚 Daftar Buku</h3>
            <p>Lihat semua koleksi buku yang tersedia di perpustakaan.</p>
            <a href="../views/v_pinjam_user.php">Buka</a>
        </div>

        <div class="card">
            <h3>📖 Buku Dipinjam</h3>
            <p>Lihat buku yang sedang kamu pinjam saat ini.</p>
            <a href="../views/sedang_dipinjam.php">Lihat</a>
        </div>

        <div class="card">
            <h3>📜 Riwayat Peminjaman</h3>
            <p>Cek riwayat semua peminjaman yang pernah kamu lakukan.</p>
            <a href="../controllers/c_riwayat.php">Cek</a>
        </div>

        <div class="card">
            <h3>💰 Denda Saya</h3>
            <p>Lihat denda keterlambatan atau kondisi buku jika ada.</p>
            <a href="../controllers/c_denda.php?aksi=milik_saya">Lihat</a>
        </div>

    </div>

    <div class="footer">
        © <?= date('Y'); ?> Sistem Perpustakaan Digital
    </div>

</div>

</body>
</html>