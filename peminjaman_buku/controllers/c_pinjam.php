<?php
require_once __DIR__ . '/../config/app.php';
require_login();
require_valid_csrf();
require_once __DIR__ . '/../models/m_buku.php';
require_once __DIR__ . '/../models/m_pinjam.php';

if (($_SESSION['role'] ?? '') !== 'user') redirect_to('views/v_dasbord_admin.php');
if (($_GET['aksi'] ?? '') !== 'pinjam' || $_SERVER['REQUEST_METHOD'] !== 'POST') redirect_to('views/v_pinjam_user.php');

$kodeBuku = filter_var($_POST['kode_buku'] ?? null, FILTER_VALIDATE_INT);
$tanggal = trim((string)($_POST['tanggal_pinjam'] ?? ''));
$jumlah = filter_var($_POST['jumlah_pinjam'] ?? null, FILTER_VALIDATE_INT);
if (!$kodeBuku || $jumlah === false || $jumlah < 1) redirect_to('views/v_pinjam_user.php?error=validation');
$parsed = DateTimeImmutable::createFromFormat('!Y-m-d', $tanggal);
if (!$parsed || $parsed->format('Y-m-d') !== $tanggal || $tanggal > date('Y-m-d')) redirect_to('views/v_pinjam_user.php?error=date');

$buku = new m_buku();
$dataBuku = $buku->tampil_data_by_kode($kodeBuku);
if (!$dataBuku || (int)$dataBuku->stok < $jumlah) redirect_to('views/v_pinjam_user.php?error=stock');
$pinjam = new m_pinjam();
$ok = $pinjam->request_pinjam($kodeBuku, (int)$_SESSION['id_pengguna'], $tanggal, $jumlah);
redirect_to('views/v_dasbord_user.php?' . ($ok ? 'success=requested' : 'error=validation'));
