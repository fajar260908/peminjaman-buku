<?php
require_once __DIR__ . '/../config/app.php';

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
if (($_SESSION['role'] ?? '') !== 'user') {
    header("Location: v_dasbord_admin.php");
    exit;
}


// =====================================================
// PANGGIL MODEL
// =====================================================

require_once __DIR__ . '/../models/m_pinjam.php';


// =====================================================
// BUAT OBJECT
// =====================================================

$pinjam = new m_pinjam();


// =====================================================
// AMBIL ID PENGGUNA YANG SEDANG LOGIN
// =====================================================

$id_pengguna = $_SESSION['id_pengguna'];


// =====================================================
// AMBIL DATA PEMINJAMAN
// HANYA MILIK USER YANG LOGIN
// =====================================================

$data = $pinjam->tampil_buku_dipinjam($id_pengguna);

?>

<!DOCTYPE html>
<html lang="id">

<head>
<link rel="stylesheet" href="<?= h(app_url('asset/app.css')) ?>">

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Buku Sedang Dipinjam</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 20px;
            background: #f3f6fa;
            font-family: Arial, sans-serif;
            color: #16324f;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background: white;
            padding: 24px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        h2 {
            margin-top: 0;
            margin-bottom: 5px;
        }

        .deskripsi {
            color: #657786;
            margin-top: 0;
            margin-bottom: 15px;
        }

        .btn-dashboard {
            display: inline-block;
            text-decoration: none;
            background: #e7eef4;
            color: #16324f;
            padding: 10px 15px;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        .btn-dashboard:hover {
            background: #dfe7ee;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th {
            background: #f7fafc;
            text-align: left;
            padding: 13px 10px;
        }

        td {
            padding: 13px 10px;
            border-bottom: 1px solid #dfe7ee;
        }

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 15px;
            font-size: 12px;
        }

        .dipinjam {
            background: #fff0bf;
            color: #9a6800;
        }

        .menunggu {
            background: #d7f7e4;
            color: #08743b;
        }

        .btn-kembalikan {
            display: inline-block;
            background: #1f6f8b;
            color: white;
            text-decoration: none;
            padding: 8px 13px;
            border-radius: 8px;
            font-size: 12px;
        }

        .btn-kembalikan:hover {
            background: #16324f;
        }

        .pesan-kosong {
            text-align: center;
            padding: 30px;
            color: #718096;
        }

        @media (max-width: 800px) {

            .container {
                padding: 15px;
                overflow-x: auto;
            }

            table {
                min-width: 850px;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <h2>📚 Buku Sedang Dipinjam</h2>

    <p class="deskripsi">
        Daftar buku yang saat ini masih dalam proses peminjaman.
    </p>


    <!-- KEMBALI KE DASHBOARD -->

    <a href="v_dasbord_user.php" class="btn-dashboard">
        🏠 Kembali ke Dashboard
    </a>


    <!-- TABEL PEMINJAMAN -->

    <table>

        <thead>

            <tr>

                <th>No</th>

                <th>Nama Buku</th>

                <th>Nama Peminjam</th>

                <th>Tanggal Pinjam</th>

                <th>Tanggal Kembali</th>

                <th>Jumlah</th>

                <th>Status</th>

                <th>Aksi</th>

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
                        <?= htmlspecialchars($d->nama_pengguna); ?>
                    </td>


                    <!-- TANGGAL PINJAM -->

                    <td>
                        <?= htmlspecialchars($d->tanggal_pinjam); ?>
                    </td>


                    <!-- TANGGAL KEMBALI -->

                    <td>
                        <?= htmlspecialchars($d->tanggal_kembali ?? '-'); ?>
                    </td>


                    <!-- JUMLAH -->

                    <td>
                        <?= htmlspecialchars($d->jumlah_pinjam); ?>
                    </td>


                    <!-- STATUS -->

                    <td>

                        <?php if ($d->status == 'dipinjam') : ?>

                            <span class="status dipinjam">
                                Dipinjam
                            </span>

                        <?php elseif ($d->status == 'menunggu_verifikasi') : ?>

                            <span class="status menunggu">
                                Menunggu_verifikasi
                            </span>

                        <?php endif; ?>

                    </td>


                    <!-- AKSI -->

                    <td>

                        <?php if ($d->status == 'dipinjam') : ?>

                            <form method="post" action="../controllers/c_kembalikan.php" onsubmit="return confirm('Yakin ingin mengajukan pengembalian buku ini?');">
                                <input type="hidden" name="id_pinjam" value="<?= (int)$d->id_pinjam; ?>">
                                <button type="submit" class="btn-kembalikan">🔄 Kembalikan</button>
                            <?= csrf_field() ?></form>

                        <?php endif; ?>

                    </td>

                </tr>

            <?php endforeach; ?>


        <?php else : ?>

            <tr>

                <td colspan="8" class="pesan-kosong">

                    Tidak ada buku yang sedang dipinjam.

                </td>

            </tr>

        <?php endif; ?>

        </tbody>

    </table>

</div>

</body>

</html>