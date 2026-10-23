<?php
require_once __DIR__ . '/../config/app.php';
require_admin();
require_valid_csrf();
require_once __DIR__ . '/../models/m_pengguna.php';
$user = new m_pengguna();
$aksi = (string)($_GET['aksi'] ?? '');

if ($aksi === 'hapus' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    if ($id === (int)($_SESSION['id_pengguna'] ?? 0)) redirect_to('views/v_data_user.php?error=self_delete');
    $ok = $user->hapus_data($id);
    redirect_to('views/v_data_user.php?' . ($ok ? 'success=deleted' : 'error=delete'));
}
if ($aksi === 'tambah' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    include __DIR__ . '/../views/v_tambah_pengguna.php';
    exit;
}
if ($aksi === 'tambah' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = ($_POST['role'] ?? '') === 'admin' ? 'admin' : 'user';
    $ok = $user->tambah_data($_POST['nama_pengguna'] ?? '', $_POST['email'] ?? '', $_POST['jenis_kelamin'] ?? '', $_POST['tanggal_lahir_pengguna'] ?? '', $_POST['alamat'] ?? '', $_POST['password'] ?? '', $role);
    redirect_to('views/v_data_user.php?' . ($ok ? 'success=created' : 'error=validation'));
}
if ($aksi === 'edit') {
    $users = $user->tampil_data_by_id($_GET['id'] ?? '');
    if (!$users) redirect_to('views/v_data_user.php?error=not_found');
    include __DIR__ . '/../views/v_edit_pengguna.php';
    exit;
}
if ($aksi === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $ok = $user->ubah_data($_POST['id_user'] ?? '', $_POST['nama_pengguna'] ?? '', $_POST['email'] ?? '', $_POST['jenis_kelamin'] ?? '', $_POST['tanggal_lahir_pengguna'] ?? '', $_POST['alamat'] ?? '', $_POST['password'] ?? '', $_POST['role'] ?? 'user');
    redirect_to('views/v_data_user.php?' . ($ok ? 'success=updated' : 'error=validation'));
}

$data_pengguna = $user->tampil_data();
include __DIR__ . '/../views/v_data_user.php';
