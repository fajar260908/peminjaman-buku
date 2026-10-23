<?php
require_once __DIR__ . '/../config/app.php';
require_login();
if (($_SESSION['role'] ?? '') !== 'user') redirect_to('views/v_dasbord_admin.php');
require_once __DIR__ . '/../models/m_buku.php';
if (!isset($data_buku)) $data_buku = (new m_buku())->tampil_data();
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Pilih Buku Mapel</title><link rel="stylesheet" href="<?= h(app_url('asset/app.css')) ?>"></head><body><main class="page"><header class="page-head"><div><p class="eyebrow">PEMINJAMAN SISWA</p><h1>Daftar Buku Mata Pelajaran</h1><p class="muted">Pilih buku yang ingin diajukan untuk dipinjam.</p></div><a class="btn btn-light" href="<?= h(app_url('views/v_dasbord_user.php')) ?>">Kembali ke Dashboard</a></header>
<?php if (isset($_GET['error'])): ?><div class="notice error">Pengajuan tidak dapat diproses. Periksa stok dan data peminjaman.</div><?php endif; ?>
<section class="book-grid"><?php if ($data_buku): foreach ($data_buku as $row): ?><article class="book-card"><p class="eyebrow"><?= h($row->mata_pelajaran ?? 'Umum') ?></p><h3><?= h($row->nama_buku) ?></h3><div class="book-meta"><span>Kelas <?= h($row->kelas ?? 'Umum') ?></span><span>Semester <?= h($row->semester ?? 'Umum') ?></span></div><p class="muted">Stok tersedia: <strong><?= (int)$row->stok ?></strong></p><div style="margin-top:16px"><?php if ((int)$row->stok > 0): ?><a class="btn btn-primary" href="<?= h(app_url('views/v_from_peminjaman.php?kode_buku=' . (int)$row->kode_buku)) ?>">Ajukan Pinjam</a><?php else: ?><span class="pill pill-amber">Stok Habis</span><?php endif; ?></div></article><?php endforeach; else: ?><div class="panel">Belum ada buku mata pelajaran yang tersedia.</div><?php endif; ?></section></main></body></html>
