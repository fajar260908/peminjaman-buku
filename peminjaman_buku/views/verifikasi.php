<?php
require_once __DIR__ . '/../config/app.php';
session_start();

if (!isset($_SESSION['role'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: v_dasbord_user.php");
    exit;
}
require_once __DIR__ . '/../models/m_pinjam.php';

$pinjam = new m_pinjam();

$id_pinjam = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
$data = $id_pinjam ? $pinjam->tampil_pinjam_by_id($id_pinjam) : null;
if (!$data || $data->status !== 'pending') {
    redirect_to('views/verif_admin.php?error=not_found');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Verifikasi Pinjaman</title>
<link rel="stylesheet" href="<?= h(app_url('asset/verif.css')) ?>">
<link rel="stylesheet" href="<?= h(app_url('asset/app.css')) ?>">
</head>
<body>

<div class="container">

    <h2>📚 Form Verifikasi Pinjaman</h2>

    <form action="../controllers/c_proses_verifikasi.php" method="POST">
        <input type="hidden" name="aksi" value="terima_peminjaman">

        <input type="hidden" name="id_pinjam" value="<?= $data->id_pinjam ?>">

        <div class="info-box">
            <p><strong>Kode Buku:</strong> <?= $data->kode_buku ?></p>
            <p><strong>Tanggal Pinjam:</strong> <?= $data->tanggal_pinjam ?></p>
            <p><strong>Jumlah Pinjam:</strong> <?= $data->jumlah_pinjam ?></p>
        </div>

        <label>Tanggal Jatuh Tempo</label>
        <input type="date" name="tanggal_jatuh_tempo" min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d', strtotime('+14 days')) ?>" required>

        <button type="submit">
            ✅ Konfirmasi Verifikasi
        </button>

    <?= csrf_field() ?></form>

</div>

</body>
</html>