<?php
session_start();

include "../models/m_buku.php";

$buku = new m_buku();

$kode_buku = $_GET['kode_buku'];

$data = $buku->tampil_data_by_kode($kode_buku);
?>

<!DOCTYPE html>
<html>

<head>
       <title>Form Pinjam Buku</title>
<link rel="stylesheet" href="/pinjam_buku/asset/form_peminjaman.css">
</head>

<body>

       <div class="container">

              <h2>📚 Form Peminjaman Buku</h2>

              <form action="../controllers/c_pinjam.php?aksi=pinjam" method="POST">

                     <input type="hidden" name="kode_buku" value="<?= $data->kode_buku ?>">
                     <input type="hidden" name="id_pengguna" value="<?= $_SESSION['id_pengguna'] ?>">

                     <label>Nama Buku</label>
                     <input type="text" value="<?= $data->nama_buku ?>" readonly>

                     <label>Stok Tersedia</label>
                     <input type="text" value="<?= $data->stok ?>" readonly>

                     <label>Tanggal Pinjam</label>
                     <input type="date" name="tanggal_pinjam" required>

                     <label>Jumlah Pinjam</label>
                     <input type="number"
                            name="jumlah_pinjam"
                            min="1"
                            max="<?= $data->stok ?>"
                            required>

                     <button type="submit">✅ Ajukan Peminjaman</button>

              </form>

       </div>

</body>

</html>