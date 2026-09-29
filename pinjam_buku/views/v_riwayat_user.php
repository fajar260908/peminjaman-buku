<?php

// =====================================================
// MULAI SESSION
// =====================================================

session_start();


// =====================================================
// CEK LOGIN
// =====================================================

if (!isset($_SESSION['id_pengguna'])) {

    header("Location: ../index.php");
    exit;

}


// =====================================================
// PANGGIL MODEL RIWAYAT
// =====================================================

require_once "../models/m_riwayat.php";


// =====================================================
// BUAT OBJECT
// =====================================================

$riwayat = new m_riwayat();


// =====================================================
// AMBIL DATA RIWAYAT USER YANG SEDANG LOGIN
// =====================================================

$data = $riwayat->tampil_data();

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Riwayat Peminjaman Buku</title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {
            margin: 0;
            padding: 20px;
            background: #eef7ff;
            font-family: Arial, sans-serif;
            color: #17365d;
        }


        .container {
            max-width: 1200px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }


        h2 {
            margin-top: 0;
            margin-bottom: 5px;
            color: #17365d;
        }


        .deskripsi {
            margin-top: 0;
            margin-bottom: 20px;
            color: #66788a;
        }


        .btn-dashboard {
            display: inline-block;
            text-decoration: none;
            background: #e7edf5;
            color: #17365d;
            padding: 10px 16px;
            border-radius: 10px;
            margin-bottom: 25px;
        }


        .btn-dashboard:hover {
            background: #d7e0eb;
        }


        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }


        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }


        th {
            background: #edf2f7;
            color: #17365d;
            text-align: left;
            padding: 14px 12px;
            border-bottom: 1px solid #dce4ec;
        }


        td {
            padding: 14px 12px;
            border-bottom: 1px solid #e1e7ee;
        }


        tr:hover {
            background: #f8fbff;
        }


        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }


        .dikembalikan {
            background: #d7f7e4;
            color: #08743b;
        }


        .dipinjam {
            background: #fff0bf;
            color: #9a6800;
        }


        .menunggu {
            background: #dcecff;
            color: #1769e0;
        }


        .pending {
            background: #ffe2e2;
            color: #b42318;
        }


        .kosong {
            text-align: center;
            padding: 35px;
            color: #718096;
        }


        @media (max-width: 800px) {

            .container {
                padding: 15px;
            }

            table {
                min-width: 850px;
            }

        }

    </style>

</head>


<body>


<div class="container">


    <!-- =================================================
         JUDUL
         ================================================= -->

    <h2>📚 Riwayat Peminjaman Buku</h2>


    <p class="deskripsi">
        Berikut adalah riwayat peminjaman buku pada akun Anda.
    </p>


    <!-- =================================================
         TOMBOL KEMBALI
         ================================================= -->

    <a href="v_dasbord_user.php" class="btn-dashboard">
        🏠 Kembali ke Dashboard
    </a>


    <!-- =================================================
         TABEL
         ================================================= -->

    <div class="table-wrapper">

        <table>

            <thead>

                <tr>

                    <th>No</th>

                    <th>Nama Buku</th>

                    <th>Nama Peminjam</th>

                    <th>Tanggal Peminjaman</th>

                    <th>Tanggal Pengembalian</th>

                    <th>Kode Buku</th>

                    <th>Jumlah</th>

                    <th>Status</th>

                </tr>

            </thead>


            <tbody>


            <?php if (!empty($data)) : ?>


                <?php $no = 1; ?>


                <?php foreach ($data as $d) : ?>


                    <tr>


                        <!-- NO -->

                        <td>
                            <?= $no++; ?>
                        </td>


                        <!-- NAMA BUKU -->

                        <td>
                            <?= htmlspecialchars($d->nama_buku); ?>
                        </td>


                        <!-- NAMA PEMINJAM -->

                        <td>
                            <?= htmlspecialchars($d->nama_peminjam); ?>
                        </td>


                        <!-- TANGGAL PEMINJAMAN -->

                        <td>
                            <?= htmlspecialchars($d->tanggal_peminjaman); ?>
                        </td>


                        <!-- TANGGAL PENGEMBALIAN -->

                        <td>

                            <?php

                            if (
                                isset($d->tanggal_pengembalian)
                                && $d->tanggal_pengembalian != ''
                                && $d->tanggal_pengembalian != '0000-00-00'
                            ) {

                                echo htmlspecialchars(
                                    $d->tanggal_pengembalian
                                );

                            } else {

                                echo '-';

                            }

                            ?>

                        </td>


                        <!-- KODE BUKU -->

                        <td>
                            <?= htmlspecialchars($d->kode_buku); ?>
                        </td>


                        <!-- JUMLAH -->

                        <td>
                            <?= htmlspecialchars($d->jumlah_pinjam); ?>
                        </td>


                        <!-- STATUS -->

                        <td>

                            <?php

                            /*
                             * Karena tabel riwayat_peminjaman
                             * yang kamu kirim tidak mempunyai
                             * kolom status, status ditentukan
                             * berdasarkan tanggal pengembalian.
                             */

                            if (
                                isset($d->tanggal_pengembalian)
                                && $d->tanggal_pengembalian != ''
                                && $d->tanggal_pengembalian != '0000-00-00'
                            ) :

                            ?>

                                <span class="status dikembalikan">
                                    Dikembalikan
                                </span>

                            <?php else : ?>

                                <span class="status dipinjam">
                                    Selesai
                                </span>

                            <?php endif; ?>

                        </td>


                    </tr>


                <?php endforeach; ?>


            <?php else : ?>


                <tr>

                    <td colspan="8" class="kosong">

                        Belum ada riwayat peminjaman buku.

                    </td>

                </tr>


            <?php endif; ?>


            </tbody>

        </table>

    </div>


</div>


</body>

</html>