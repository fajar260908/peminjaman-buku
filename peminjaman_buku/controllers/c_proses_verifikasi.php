<?php
require_once __DIR__ . '/../config/app.php';
require_admin();
require_valid_csrf();
require_once __DIR__ . '/../models/m_pinjam.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect_to('views/verif_admin.php');
$id = filter_var($_POST['id_pinjam'] ?? null, FILTER_VALIDATE_INT);
$aksi = (string)($_POST['aksi'] ?? '');
if (!$id) redirect_to('views/verif_admin.php?error=not_found');
$pinjam = new m_pinjam();

if ($aksi === 'terima_peminjaman') {
    $due = trim((string)($_POST['tanggal_jatuh_tempo'] ?? ''));
    $parsed = DateTimeImmutable::createFromFormat('!Y-m-d', $due);
    if (!$parsed || $parsed->format('Y-m-d') !== $due || $due < date('Y-m-d')) redirect_to('views/verif_admin.php?error=due_date');
    $ok = $pinjam->verifikasi_pinjam($id, $due);
    redirect_to('views/verif_admin.php?' . ($ok ? 'success=loan_approved' : 'error=stock_or_status'));
}

if ($aksi === 'terima_pengembalian') {
    $ok = $pinjam->kembalikan_buku($id);
    redirect_to('views/verif_admin.php?' . ($ok ? 'success=returned' : 'error=return_failed'));
}

if ($aksi === 'tolak_pengembalian') {
    $ok = $pinjam->tolak_pengembalian($id);
    redirect_to('views/verif_admin.php?' . ($ok ? 'success=return_rejected' : 'error=return_failed'));
}

redirect_to('views/verif_admin.php?error=action');
