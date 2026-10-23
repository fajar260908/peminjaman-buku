<?php
require_once __DIR__ . '/../config/app.php';
require_login();
require_valid_csrf();
require_once __DIR__ . '/../models/m_pinjam.php';

$id = filter_var($_POST['id_pinjam'] ?? null, FILTER_VALIDATE_INT);
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) redirect_to('views/sedang_dipinjam.php?error=not_found');
$pinjam = new m_pinjam();
$data = $pinjam->tampil_pinjam_by_id($id);
if (!$data || (int)$data->id_pengguna !== (int)$_SESSION['id_pengguna']) redirect_to('views/sedang_dipinjam.php?error=not_allowed');
if ($data->status !== 'dipinjam') redirect_to('views/sedang_dipinjam.php?error=status');
$ok = $pinjam->ajukan_pengembalian($id);
redirect_to('views/sedang_dipinjam.php?' . ($ok ? 'success=return_requested' : 'error=status'));
