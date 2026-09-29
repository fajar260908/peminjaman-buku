<?php

// =====================================================
// CEK ROLE ADMIN
// Session sudah dimulai di c_riwayat.php
// =====================================================

if (!isset($_SESSION['role'])) {

    header("Location: ../index.php");
    exit;

}


if ($_SESSION['role'] !== 'admin') {

    header("Location: v_dasbord_user.php");
    exit;

}

?>


<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<title>Riwayat Peminjaman</title>

<link
    rel="stylesheet"
    href="/pinjam_buku/asset/riwayat_peminjaman.css"
>

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

                                <a
                                    href="../controllers/c_riwayat.php?aksi=hapus&id_riwayat=<?= urlencode($row->id_riwayat) ?>"
                                    onclick="return confirm('Yakin ingin menghapus riwayat ini?')"
                                    style="
                                        background:red;
                                        color:white;
                                        padding:6px 10px;
                                        border-radius:5px;
                                        text-decoration:none;
                                    "
                                >
                                    Hapus
                                </a>

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
