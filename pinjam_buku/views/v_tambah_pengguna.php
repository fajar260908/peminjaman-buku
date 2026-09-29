<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: v_dasbord_user.php");
    exit;
}


?>


<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Daftar Akun Perpustakaan</title>
<link rel="stylesheet" href="/pinjam_buku/asset/tambah_pengguna.css">
</head>
<body>

<div class="register-card">

<h2>📚 Tambah Pengguna</h2>
<p>Perpustakaan - Sistem Peminjaman Buku</p>

<form method="POST" action="../controllers/c_pengguna.php?aksi=tambah">

<input type="text" name="nama_pengguna" placeholder="Username" required>

<input type="email" name="email" placeholder="Email" required>

<div class="radio-group">
<label>
<input type="radio" name="jenis_kelamin" value="Laki-laki" required>
Laki-laki
</label>

<label>
<input type="radio" name="jenis_kelamin" value="Perempuan" required>
Perempuan
</label>
</div>

<input type="date" name="tanggal_lahir_pengguna" required>

<textarea name="alamat" rows="3" placeholder="Alamat" required></textarea>

<input type="password" name="password" placeholder="Password" required>

<input type="hidden" name="role" value="user">

<button type="submit" name="tambah">Daftar</button>

</form>

</div>

</body>
</html>