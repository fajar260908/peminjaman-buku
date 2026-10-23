<?php
require_once __DIR__ . '/../config/app.php';
require_login();
require_valid_csrf();
require_once __DIR__ . '/../models/m_riwayat.php';
$riwayat = new m_riwayat();
$aksi = (string)($_GET['aksi'] ?? '');
if (($_SESSION['role'] ?? '') === 'admin') {
    if ($aksi === 'hapus' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $ok = $riwayat->hapus_data($_POST['id_riwayat'] ?? '');
        redirect_to('controllers/c_riwayat.php?' . ($ok ? 'success=deleted' : 'error=delete'));
    }
    $data_riwayat = $riwayat->tampil_semua_data();
    include __DIR__ . '/../views/v_riwayat_peminjaman_buku.php';
    exit;
}
$data_riwayat = $riwayat->tampil_data((int)$_SESSION['id_pengguna']);
include __DIR__ . '/../views/v_riwayat_user.php';
