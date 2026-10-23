<?php
require_once __DIR__ . '/../config/app.php';
require_login();
require_valid_csrf();
require_once __DIR__ . '/../models/m_denda.php';
$denda = new m_denda();
$aksi = (string)($_GET['aksi'] ?? '');

if ($aksi === 'milik_saya') {
    $data_denda = $denda->tampil_milik_user((int)$_SESSION['id_pengguna']);
    include __DIR__ . '/../views/v_denda_user.php';
    exit;
}
require_admin();

if ($aksi === 'tambah' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $pinjam = $denda->peminjaman_belum_denda();
    include __DIR__ . '/../views/v_tambah_denda.php';
    exit;
}
if ($aksi === 'simpan' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $ok = $denda->tambah($_POST['id_pinjam'] ?? '', $_POST['jenis_denda'] ?? '', $_POST['jumlah_hari_keterlambatan'] ?? 0, $_POST['jumlah_denda'] ?? 0);
    redirect_to('controllers/c_denda.php?' . ($ok ? 'success=created' : 'error=validation'));
}
if ($aksi === 'edit') {
    $data = $denda->cari($_GET['id'] ?? '');
    if (!$data) redirect_to('controllers/c_denda.php?error=not_found');
    include __DIR__ . '/../views/v_edit_denda.php';
    exit;
}
if ($aksi === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $ok = $denda->update($_POST['id_denda'] ?? '', $_POST['jenis_denda'] ?? '', $_POST['jumlah_hari_keterlambatan'] ?? 0, $_POST['jumlah_denda'] ?? 0, $_POST['status_denda'] ?? 'Belum Dibayar');
    redirect_to('controllers/c_denda.php?' . ($ok ? 'success=updated' : 'error=validation'));
}
if ($aksi === 'hapus' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $ok = $denda->hapus($_POST['id_denda'] ?? '');
    redirect_to('controllers/c_denda.php?' . ($ok ? 'success=deleted' : 'error=delete'));
}

$data_denda = $denda->tampil_semua();
include __DIR__ . '/../views/v_denda.php';
