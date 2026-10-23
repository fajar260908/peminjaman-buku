<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/m_pengguna.php';
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_valid_csrf();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['login'])) {
    redirect_to('index.php');
}
$usernameEmail = trim((string)($_POST['username_email'] ?? ''));
$password = (string)($_POST['password'] ?? '');
$pengguna = new m_pengguna();
$data = $pengguna->login($usernameEmail, $password);
if (!$data) redirect_to('index.php?error=login');

session_regenerate_id(true);
$_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
$_SESSION['id_pengguna'] = (int)$data->id_pengguna;
$_SESSION['nama_pengguna'] = $data->nama_pengguna;
$_SESSION['role'] = $data->role;
redirect_to($data->role === 'admin' ? 'views/v_dasbord_admin.php' : 'views/v_dasbord_user.php');
