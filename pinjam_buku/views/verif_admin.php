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


include "../models/m_pinjam.php"; 

$pinjam = new m_pinjam(); 


// =====================================================
// DATA PENGAJUAN PEMINJAMAN
// =====================================================

$data_pending = $pinjam->tampil_pending(); 


// =====================================================
// DATA PENGAJUAN PENGEMBALIAN
// =====================================================

$data_pengembalian = $pinjam->tampil_menunggu_pengembalian(); 

?> 

<!DOCTYPE html> 

<html lang="id"> 

<head> 

<meta charset="UTF-8"> 

<title>Verifikasi Peminjaman & Pengembalian</title> 

<link rel="stylesheet" href="/pinjam_buku/asset/verif_admin.css"> 

<style>

/* =====================================================
   TOMBOL VERIFIKASI
===================================================== */

.verif-btn {
    background: #16a34a;
    color: white;
    border: none;
    padding: 10px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    font-size: 14px;
    display: inline-block;
}

.verif-btn:hover {
    background: #15803d;
}


/* =====================================================
   TOMBOL TOLAK
===================================================== */

.tolak-btn {
    background: #dc2626;
    color: white;
    border: none;
    padding: 10px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    font-size: 14px;
    margin-left: 5px;
    display: inline-block;
}

.tolak-btn:hover {
    background: #b91c1c;
}


/* =====================================================
   FORM TOMBOL
===================================================== */

td form {
    display: inline-block;
}

</style>

</head> 


<body> 


<div class="container"> 


<!-- =================================================
     HEADER
================================================== --> 

<div class="header"> 

    <h2>📩 Verifikasi Peminjaman & Pengembalian</h2> 

    <p> 
        Kelola pengajuan peminjaman dan pengembalian buku. 
    </p> 

    <div class="top-btn"> 

        <a 
            href="v_dasbord_admin.php" 
            class="back-btn" 
        > 
            🏠 Kembali ke Dashboard 
        </a> 

    </div> 

</div> 



<!-- =================================================
     PENGAJUAN PEMINJAMAN
================================================== --> 

<div class="table-box"> 

<h3>📚 Pengajuan Peminjaman</h3> 


<table> 

<thead> 

<tr> 

    <th>Nama User</th> 

    <th>Buku</th> 

    <th>Tanggal Pinjam</th> 

    <th>Jumlah</th> 

    <th>Aksi</th> 

</tr> 

</thead> 


<tbody> 


<?php if (!empty($data_pending)): ?> 


<?php foreach ($data_pending as $row): ?> 


<tr> 

    <td> 
        <?= htmlspecialchars($row->nama_pengguna) ?> 
    </td> 


    <td> 
        <?= htmlspecialchars($row->nama_buku) ?> 
    </td> 


    <td> 
        <?= htmlspecialchars($row->tanggal_pinjam) ?> 
    </td> 


    <td> 
        <?= htmlspecialchars($row->jumlah_pinjam) ?> 
    </td> 


    <td> 

        <form 
            action="../controllers/c_proses_verifikasi.php" 
            method="POST" 
        > 

            <input 
                type="hidden" 
                name="id_pinjam" 
                value="<?= $row->id_pinjam ?>" 
            > 


            <input 
                type="hidden" 
                name="aksi" 
                value="terima_peminjaman" 
            > 


            <input 
                type="hidden" 
                name="tanggal_kembali" 
                value="<?= $row->tanggal_kembali ?>" 
            > 


            <button 
                type="submit" 
                class="verif-btn" 
            > 
                ✅ Terima 
            </button> 

        </form> 

    </td> 

</tr> 


<?php endforeach; ?> 


<?php else: ?> 


<tr> 

    <td 
        colspan="5" 
        class="empty" 
    > 
        Tidak ada pengajuan peminjaman. 
    </td> 

</tr> 


<?php endif; ?> 


</tbody> 

</table> 

</div> 



<!-- =================================================
     PENGAJUAN PENGEMBALIAN
================================================== --> 

<div class="table-box"> 

<h3>🔄 Pengajuan Pengembalian</h3> 


<table> 

<thead> 

<tr> 

    <th>Nama User</th> 

    <th>Buku</th> 

    <th>Tanggal Pinjam</th> 

    <th>Jumlah</th> 

    <th>Aksi</th> 

</tr> 

</thead> 


<tbody> 


<?php if (!empty($data_pengembalian)): ?> 


<?php foreach ($data_pengembalian as $row): ?> 


<tr> 

    <td> 
        <?= htmlspecialchars($row->nama_pengguna) ?> 
    </td> 


    <td> 
        <?= htmlspecialchars($row->nama_buku) ?> 
    </td> 


    <td> 
        <?= htmlspecialchars($row->tanggal_pinjam) ?> 
    </td> 


    <td> 
        <?= htmlspecialchars($row->jumlah_pinjam) ?> 
    </td> 


    <td> 


        <!-- TERIMA PENGEMBALIAN --> 

        <form 
            action="../controllers/c_proses_verifikasi.php" 
            method="POST" 
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
                class="verif-btn" 
                onclick="return confirm('Terima pengembalian buku ini?')" 
            > 
                ✅ Terima 
            </button> 

        </form> 



        <!-- TOLAK PENGEMBALIAN --> 

        <form 
            action="../controllers/c_proses_verifikasi.php" 
            method="POST" 
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
                class="tolak-btn"
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
        class="empty" 
    > 
        Tidak ada pengajuan pengembalian. 
    </td> 

</tr> 


<?php endif; ?> 


</tbody> 

</table> 

</div> 


</div> 


</body> 

</html>