<?php
require_once __DIR__ . '/../config/app.php';
require_admin();
require_valid_csrf();
require_once __DIR__ . '/../models/m_buku.php';
$buku = new m_buku();
$aksi = (string)($_GET['aksi'] ?? '');

if ($aksi === 'edit') {
    $data = $buku->tampil_data_by_kode($_GET['kode_buku'] ?? '');
    if (!$data) redirect_to('views/v_admin_buku.php?error=not_found');
    include __DIR__ . '/../views/v_edit_buku.php';
    exit;
}

if ($aksi === 'hapus' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $ok = $buku->hapus_data($_POST['kode_buku'] ?? '');
    redirect_to('views/v_admin_buku.php?' . ($ok ? 'success=deleted' : 'error=delete'));
}

if ($aksi === 'tambah' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $ok = $buku->tambah_data($_POST['nama_buku'] ?? '', $_POST['mata_pelajaran'] ?? '', $_POST['kelas'] ?? '', $_POST['semester'] ?? '', $_POST['stok'] ?? null);
    redirect_to('views/v_admin_buku.php?' . ($ok ? 'success=created' : 'error=validation'));
}

if ($aksi === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $ok = $buku->update_data($_POST['kode_buku'] ?? '', $_POST['nama_buku'] ?? '', $_POST['mata_pelajaran'] ?? '', $_POST['kelas'] ?? '', $_POST['semester'] ?? '', $_POST['stok'] ?? null);
    redirect_to('views/v_admin_buku.php?' . ($ok ? 'success=updated' : 'error=validation'));
}

redirect_to('views/v_admin_buku.php');
