<?php

session_start();


// =====================================================
// CEK LOGIN
// =====================================================

if (!isset($_SESSION['role'])) {

    header("Location: ../index.php");
    exit;
}


// =====================================================
// CEK ADMIN
// =====================================================

if ($_SESSION['role'] !== 'admin') {

    header("Location: v_dasbord_user.php");
    exit;
}


// =====================================================
// PANGGIL MODEL DASHBOARD
// =====================================================

include "../models/m_dashboard.php";

$dashboard = new m_dashboard();


// =====================================================
// PANGGIL MODEL PEMINJAMAN
// =====================================================

include "../models/m_pinjam.php";

$pinjam = new m_pinjam();


// =====================================================
// DATA STATISTIK
// =====================================================

$total_buku    = $dashboard->total_buku();
$total_user    = $dashboard->total_user();
$total_pending = $dashboard->total_pending();
$total_pinjam  = $dashboard->total_pinjam();
$total_kembali = $dashboard->total_kembali();


// =====================================================
// DATA PENGAJUAN PENGEMBALIAN
// =====================================================

$data_pengembalian = $pinjam->tampil_menunggu_pengembalian();


// =====================================================
// NAMA ADMIN
// =====================================================

$nama_admin = $_SESSION['nama_pengguna'] ?? 'Admin';

?>

<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<title>Dashboard Admin</title>

<link
    rel="stylesheet"
    href="/pinjam_buku/asset/dasbord_admin.css"
>


<style>

/* =====================================================
   TABEL PENGEMBALIAN
===================================================== */

.pengembalian-box {

    background: white;

    margin-top: 25px;

    padding: 25px;

    border-radius: 20px;

    box-shadow: 0 5px 20px rgba(0,0,0,0.08);

    width: 100%;

    box-sizing: border-box;

}


.pengembalian-box h2 {

    margin: 0;

    color: #222;

    font-size: 22px;

}


.pengembalian-box p {

    color: #777;

    margin-top: 8px;

    margin-bottom: 20px;

}


.table-wrapper {

    width: 100%;

    overflow-x: auto;

}


.table-pengembalian {

    width: 100%;

    border-collapse: collapse;

}


.table-pengembalian thead {

    background: #2864e8;

    color: white;

}


.table-pengembalian th {

    padding: 14px;

    text-align: center;

}


.table-pengembalian td {

    padding: 14px;

    text-align: center;

    border-bottom: 1px solid #ddd;

}


.table-pengembalian tbody tr:hover {

    background: #f5f8ff;

}


/* =====================================================
   TOMBOL TERIMA
===================================================== */

.btn-terima {

    background: #16a34a;

    color: white;

    border: none;

    padding: 9px 15px;

    border-radius: 8px;

    cursor: pointer;

    font-weight: bold;

    margin-right: 5px;

}


.btn-terima:hover {

    background: #15803d;

}


/* =====================================================
   TOMBOL TOLAK
===================================================== */

.btn-tolak {

    background: #dc2626;

    color: white;

    border: none;

    padding: 9px 15px;

    border-radius: 8px;

    cursor: pointer;

    font-weight: bold;

}


.btn-tolak:hover {

    background: #b91c1c;

}


/* =====================================================
   JIKA TIDAK ADA PENGEMBALIAN
===================================================== */

.tidak-ada {

    padding: 25px !important;

    color: #777;

}


/* =====================================================
   JUDUL SECTION
===================================================== */

.judul-pengembalian {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 10px;

}


.jumlah-pengembalian {

    background: #2563eb;

    color: white;

    padding: 6px 12px;

    border-radius: 20px;

    font-size: 14px;

    font-weight: bold;

}

</style>

</head>


<body>


<!-- =====================================================
     SIDEBAR
===================================================== -->

<div class="sidebar">

    <h2>📚 Admin</h2>


    <a href="v_dasbord_admin.php">
        🏠 Dashboard
    </a>


    <a href="../views/v_admin_buku.php">
        📖 Data Buku
    </a>


    <a href="../views/v_data_user.php">
        👥 Data User
    </a>


    <a href="../views/verif_admin.php">
        📩 Pengajuan Pinjaman
    </a>


    <!-- =================================================
         RIWAYAT PEMINJAMAN
         DIARAHKAN KE CONTROLLER
    ================================================== -->

    <a href="../controllers/c_riwayat.php">
        📜 Riwayat
    </a>


    <a href="../controllers/c_logout.php">
        🚪 Logout
    </a>

</div>



<!-- =====================================================
     MAIN
===================================================== -->

<div class="main">


    <!-- TOPBAR -->

    <div class="topbar">

        <h1>
            Dashboard Admin
        </h1>


        <span>

            Selamat datang,
            <?= htmlspecialchars($nama_admin) ?>
            👋

        </span>

    </div>



    <!-- WELCOME -->

    <div class="welcome">

        <h2>
            Selamat Datang di Dashboard Admin
        </h2>

        <p>
            Kelola sistem perpustakaan dengan mudah dan cepat
        </p>

    </div>



    <!-- =================================================
         STATISTIK
    ================================================== -->

    <div class="cards">


        <!-- TOTAL BUKU -->

        <div class="card">

            <h3>
                📚 Total Buku
            </h3>

            <p>
                <?= $total_buku ?>
            </p>

        </div>



        <!-- TOTAL USER -->

        <div class="card">

            <h3>
                👥 Total User
            </h3>

            <p>
                <?= $total_user ?>
            </p>

        </div>



        <!-- PENGAJUAN BARU -->

        <div class="card">

            <h3>
                📩 Pengajuan Baru
            </h3>

            <p>
                <?= $total_pending ?>
            </p>

        </div>



        <!-- PEMINJAMAN -->

        <div class="card">

            <h3>
                📥 Peminjaman
            </h3>

            <p>
                <?= $total_pinjam ?>
            </p>

        </div>



        <!-- PENGEMBALIAN -->

        <div class="card">

            <h3>
                📤 Total Pengembalian Buku
            </h3>

            <p>
                <?= $total_kembali ?>
            </p>

        </div>


    </div>



    <!-- =================================================
         PENGAJUAN PENGEMBALIAN
    ================================================== -->

    <div class="pengembalian-box">


        <div class="judul-pengembalian">

            <h2>
                🔄 Pengajuan Pengembalian
            </h2>


            <span class="jumlah-pengembalian">

                <?= count($data_pengembalian) ?>
                Menunggu

            </span>

        </div>


        <p>
            Pengembalian buku yang menunggu verifikasi admin.
        </p>



        <div class="table-wrapper">


            <table class="table-pengembalian">


                <thead>

                    <tr>

                        <th>
                            Nama User
                        </th>

                        <th>
                            Buku
                        </th>

                        <th>
                            Tanggal Pinjam
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


                    <?php if (!empty($data_pengembalian)): ?>


                        <?php foreach ($data_pengembalian as $row): ?>


                            <tr>


                                <!-- NAMA USER -->

                                <td>

                                    <?= htmlspecialchars(
                                        $row->nama_pengguna
                                    ) ?>

                                </td>



                                <!-- NAMA BUKU -->

                                <td>

                                    <?= htmlspecialchars(
                                        $row->nama_buku
                                    ) ?>

                                </td>



                                <!-- TANGGAL PINJAM -->

                                <td>

                                    <?= htmlspecialchars(
                                        $row->tanggal_pinjam
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


                                    <!-- ======================
                                         TERIMA
                                    ======================= -->

                                    <form
                                        action="../controllers/c_proses_verifikasi.php"
                                        method="POST"
                                        style="display:inline;"
                                    >


                                        <input
                                            type="hidden"
                                            name="id_pinjam"
                                            value="<?= $row->id_pinjam ?>"
                                        >


                                        <input
                                            type="hidden"
                                            name="aksi"
                                            value="terima_pengembalian"
                                        >


                                        <button
                                            type="submit"
                                            class="btn-terima"
                                            onclick="return confirm('Terima pengembalian buku ini?')"
                                        >

                                            ✅ Terima

                                        </button>


                                    </form>



                                    <!-- ======================
                                         TOLAK
                                    ======================= -->

                                    <form
                                        action="../controllers/c_proses_verifikasi.php"
                                        method="POST"
                                        style="display:inline;"
                                    >


                                        <input
                                            type="hidden"
                                            name="id_pinjam"
                                            value="<?= $row->id_pinjam ?>"
                                        >


                                        <input
                                            type="hidden"
                                            name="aksi"
                                            value="tolak_pengembalian"
                                        >


                                        <button
                                            type="submit"
                                            class="btn-tolak"
                                            onclick="return confirm('Tolak pengembalian buku ini?')"
                                        >

                                            ❌ Tolak

                                        </button>


                                    </form>


                                </td>


                            </tr>


                        <?php endforeach; ?>


                    <?php else: ?>


                        <tr>


                            <td
                                colspan="5"
                                class="tidak-ada"
                            >

                                Tidak ada pengajuan pengembalian buku.

                            </td>


                        </tr>


                    <?php endif; ?>


                </tbody>


            </table>


        </div>


    </div>



    <!-- =================================================
         INFORMASI ADMIN
    ================================================== -->

    <div class="section">

        <h3>
            📌 Informasi Admin
        </h3>

        <p>
            Gunakan sidebar untuk mengelola semua data perpustakaan.
        </p>

    </div>


</div>


</body>

</html>
