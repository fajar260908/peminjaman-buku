<?php
session_start();

require_once "../models/m_buku.php";

if (!isset($_SESSION['role'])) header("Location: ../index.php");

$buku = new m_buku();
$data_buku = $buku->tampil_data();
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pinjam Buku</title>
<link rel="stylesheet" href="/pinjam_buku/asset/pinjam_user.css">
</head>
<body>

<div class="navbar">
    <div class="menu">
        <a href="v_dasbord_user.php" class="nav-btn">🏠 Kembali Ke Dashboard</a>
    </div>
</div>

<div class="container">

<div class="title-box">
    <h2>📚 Daftar Buku Perpustakaan</h2>
    <p>Silahkan pilih buku yang ingin dipinjam dari daftar berikut.</p>
</div>

<div class="table-wrapper">

<table>

<thead>
<tr>
<th>No</th>
<th>Cover</th>
<th>Judul Buku</th>
<th>Pencipta</th>
<th>Stok</th>
<th>Aksi</th>
</tr>
</thead>

<tbody>

<?php if(!empty($data_buku)): $no=1; foreach($data_buku as $row): ?>

<tr>

<td><?= $no++ ?></td>

<td>
<?= $row->gambar ? "<img src='{$row->gambar}'>" : "-" ?>
</td>

<td><strong><?= htmlspecialchars($row->nama_buku) ?></strong></td>

<td><?= htmlspecialchars($row->pencipta) ?></td>

<td>
<span class="stok"><?= $row->stok ?></span>
</td>

<td>

<?php if($row->stok > 0): ?>

<a class="btn-pinjam"
href="../views/v_from_peminjaman.php?kode_buku=<?= $row->kode_buku ?>">
Pinjam
</a>

<?php else: ?>

<span class="stok-habis">Stok Habis</span>

<?php endif; ?>

</td>

</tr>

<?php endforeach; else: ?>

<tr>
<td colspan="6">Buku belum tersedia</td>
</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>

</body>
</html>