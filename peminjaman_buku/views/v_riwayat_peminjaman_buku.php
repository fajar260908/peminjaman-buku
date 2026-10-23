<?php
require_once __DIR__ . '/../config/app.php';
require_admin();
?>


<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<title>Riwayat Peminjaman</title>

<link rel="stylesheet" href="<?= h(app_url('asset/riwayat_peminjaman.css')) ?>">
<link rel="stylesheet" href="<?= h(app_url('asset/app.css')) ?>">

</head>


<body>


<div class="container">


    <!-- =================================================
         HEADER
    ================================================== -->

    <div class="header">

        <h2>
            📖 Riwayat Peminjaman Buku
        </h2>

        <p>
            Semua data aktivitas peminjaman & pengembalian buku
        </p>


        <div class="menu">

            <a
                href="../views/v_dasbord_admin.php"
                class="btn dashboard">
                🏠 Kembali ke Dashboard
             </a>


        </div>

    </div>



    <!-- =================================================
         TABEL RIWAYAT
    ================================================== -->

    <div class="table-box">


        <table>


            <thead>

                <tr>

                    <th>
                        No
                    </th>

                    <th>
                        Nama Buku
                    </th>

                    <th>
                        Nama Peminjam
                    </th>

                    <th>
                        Tanggal Pinjam
                    </th>

                    <th>
                        Tanggal Kembali
                    </th>

                    <th>
                        Jumlah
                    </th>

                    <th>
                        Aksi
                    </th>

                </tr>

            </thead>



            <tbody>


                <?php if (!empty($data_riwayat)): ?>


                    <?php $no = 1; ?>


                    <?php foreach ($data_riwayat as $row): ?>


                        <tr>


                            <!-- NOMOR -->

                            <td>

                                <?= $no++ ?>

                            </td>



                            <!-- NAMA BUKU -->

                            <td>

                                <?= htmlspecialchars(
                                    $row->nama_buku
                                ) ?>

                            </td>



                            <!-- NAMA PEMINJAM -->

                            <td>

                                <?= htmlspecialchars(
                                    $row->nama_peminjam
                                ) ?>

                            </td>



                            <!-- TANGGAL PEMINJAMAN -->

                            <td>

                                <?= htmlspecialchars(
                                    $row->tanggal_peminjaman
                                ) ?>

                            </td>



                            <!-- TANGGAL PENGEMBALIAN -->

                            <td>

                                <?= htmlspecialchars(
                                    $row->tanggal_pengembalian
                                ) ?>

                            </td>



                            <!-- JUMLAH -->

                            <td>

                                <?= htmlspecialchars(
                                    $row->jumlah_pinjam
                                ) ?>

                            </td>



                            <!-- AKSI -->

                            <td>

                                <form method="post" action="../controllers/c_riwayat.php?aksi=hapus" onsubmit="return confirm('Yakin ingin menghapus riwayat ini?')">
                                    <input type="hidden" name="id_riwayat" value="<?= (int)$row->id_riwayat ?>">
                                    <button type="submit" style="background:#b42318;color:white;padding:6px 10px;border:0;border-radius:5px;cursor:pointer">Hapus</button>
                                <?= csrf_field() ?></form>

                            </td>


                        </tr>


                    <?php endforeach; ?>


                <?php else: ?>


                    <!-- =================================================
                         JIKA TIDAK ADA DATA
                    ================================================== -->

                    <tr>

                        <td
                            colspan="7"
                            class="empty"
                        >

                            Belum ada riwayat peminjaman

                        </td>

                    </tr>


                <?php endif; ?>


            </tbody>


        </table>


    </div>



    <!-- =================================================
         FOOTER
    ================================================== -->

    <div class="footer">

        © <?= date('Y'); ?> Sistem Perpustakaan Digital

    </div>


</div>


</body>

</html>
