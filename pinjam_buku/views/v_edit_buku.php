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
    <title>Edit Buku</title>
    <link rel="stylesheet" href="/pinjam_buku/asset/v_edit_buku.css">
</head>

<body>

<div class="container">

    <h2>Edit Buku</h2>
    <p>Perbarui data buku</p>

    <form action="../controllers/c_buku.php?aksi=update" method="post">

    
        <input type="hidden" name="kode_buku" value="<?= $data->kode_buku ?? '' ?>">

        
        <label>Nama Buku</label>
        <input type="text" name="nama_buku"
            value="<?= htmlspecialchars($data->nama_buku ?? '') ?>" required>

        
        <label>Pencipta</label>
        <input type="text" name="pencipta"
            value="<?= htmlspecialchars($data->pencipta ?? '') ?>" required>

        
        <label>Tanggal Rilis</label>
        <input type="date" name="tanggal_rilis"
            value="<?= $data->tanggal_rilis ?? '' ?>" required>

        
        <label>Link Gambar (URL)</label>
        <input type="text" name="gambar"
            value="<?= htmlspecialchars($data->gambar ?? '') ?>">

        <?php if (!empty($data->gambar)): ?>
            <img src="<?= htmlspecialchars($data->gambar) ?>" alt="Cover Buku" width="120">
        <?php endif; ?>

        <label>Stok Buku</label>
        <input type="number" name="stok" min="0"
            value="<?= $data->stok ?? 0 ?>" required>

        <div class="aksi">
            <button type="submit" class="btn btn-simpan">Update</button>
           
        </div>

    </form>

</div>

</body>
</html>