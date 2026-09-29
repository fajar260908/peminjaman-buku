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
<title>Tambah Buku</title>
<link rel="stylesheet" href="/pinjam_buku/asset/v_tambah_buku.css">
</head>
<body>

<div class="container">

    <h2>📚 Tambah Buku</h2>
    <p>Masukkan data buku baru ke sistem</p>

    <form action="../controllers/c_buku.php?aksi=tambah" method="post">

        <label>Nama Buku</label>
        <input type="text" name="nama_buku" required>

        <label>Pencipta</label>
        <input type="text" name="pencipta" required>

        <label>Tanggal Rilis</label>
        <input type="date" name="tanggal_rilis" required>

        <label>Stok Buku</label>
        <input type="number" name="stok" min="0" required>

        <label>Link Gambar (URL)</label>
        <input type="text" name="gambar" placeholder="https://example.com/cover.jpg">

        <div class="aksi">
            <button type="submit" class="btn btn-simpan">💾 Simpan</button>
            <a href="v_admin_buku.php" class="btn btn-batal">❌ Batal</a>
        </div>

    </form>

</div>

</body>
</html>