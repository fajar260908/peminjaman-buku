<?php
require_once __DIR__ . '/../config/app.php';
require_login();
if (($_SESSION['role'] ?? '') !== 'user') redirect_to('views/v_dasbord_admin.php');
require_once __DIR__ . '/../models/m_buku.php';
$data = (new m_buku())->tampil_data_by_kode($_GET['kode_buku'] ?? '');
if (!$data) redirect_to('views/v_pinjam_user.php?error=not_found');
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Form Peminjaman Buku</title><link rel="stylesheet" href="<?= h(app_url('asset/app.css')) ?>"></head><body><main class="form-page"><section class="form-card"><a class="back-link" href="<?= h(app_url('views/v_pinjam_user.php')) ?>">← Kembali ke daftar buku</a><p class="eyebrow">FORM PEMINJAMAN</p><h1><?= h($data->nama_buku) ?></h1><p class="muted"><?= h($data->mata_pelajaran ?? 'Umum') ?> · Kelas <?= h($data->kelas ?? 'Umum') ?> · Semester <?= h($data->semester ?? 'Umum') ?></p><form class="stack" action="<?= h(app_url('controllers/c_pinjam.php?aksi=pinjam')) ?>" method="post"><input type="hidden" name="kode_buku" value="<?= (int)$data->kode_buku ?>"><label>Nama Siswa<input type="text" value="<?= h($_SESSION['nama_pengguna'] ?? '') ?>" readonly></label><label>Stok tersedia<input type="text" value="<?= (int)$data->stok ?>" readonly></label><label>Tanggal Pengajuan<input type="date" name="tanggal_pinjam" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" required></label><label>Jumlah Buku<input type="number" name="jumlah_pinjam" min="1" max="<?= (int)$data->stok ?>" value="1" required></label><button class="btn btn-primary" type="submit" <?= (int)$data->stok < 1 ? 'disabled' : '' ?>>Ajukan Peminjaman</button><?= csrf_field() ?></form></section></main></body></html>
