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
include "../models/m_pinjam.php";

$pinjam = new m_pinjam();

$id_pinjam = $_GET['id'];

$data = $pinjam->tampil_pinjam_by_id($id_pinjam);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Verifikasi Pinjaman</title>
<link rel="stylesheet" href="/pinjam_buku/asset/verif.css">
</head>
<body>

<div class="container">

    <h2>📚 Form Verifikasi Pinjaman</h2>

    <form action="../controllers/c_proses_verifikasi.php" method="POST">

        <input type="hidden" name="id_pinjam" value="<?= $data->id_pinjam ?>">

        <div class="info-box">
            <p><strong>Kode Buku:</strong> <?= $data->kode_buku ?></p>
            <p><strong>Tanggal Pinjam:</strong> <?= $data->tanggal_pinjam ?></p>
            <p><strong>Jumlah Pinjam:</strong> <?= $data->jumlah_pinjam ?></p>
        </div>

        <label>Tanggal Kembali</label>
        <input type="date" name="tanggal_kembali" required>

        <button type="submit">
            ✅ Konfirmasi Verifikasi
        </button>

    </form>

</div>

</body>
</html>