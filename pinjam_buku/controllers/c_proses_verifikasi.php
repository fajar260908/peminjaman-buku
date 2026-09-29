<?php

// =====================================================
// MULAI SESSION
// =====================================================

session_start();


// =====================================================
// CEK ADMIN
// =====================================================

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {

    header("Location: ../index.php");
    exit;
}


// =====================================================
// PANGGIL MODEL
// =====================================================

include "../models/m_pinjam.php";
include "../models/m_buku.php";
include "../models/m_riwayat.php";


$pinjam  = new m_pinjam();
$buku    = new m_buku();
$riwayat = new m_riwayat();


// =====================================================
// CEK ID PEMINJAMAN
// =====================================================

if (!isset($_POST['id_pinjam'])) {

    echo "<script>
        alert('ID peminjaman tidak ditemukan.');
        window.location='../views/verif_admin.php';
    </script>";

    exit;
}


$id_pinjam = $_POST['id_pinjam'];


// =====================================================
// AMBIL DATA PEMINJAMAN
// =====================================================

$data_pinjam = $pinjam->tampil_pinjam_by_id($id_pinjam);


if (!$data_pinjam) {

    echo "<script>
        alert('Data peminjaman tidak ditemukan.');
        window.location='../views/verif_admin.php';
    </script>";

    exit;
}


$kode_buku     = $data_pinjam->kode_buku;
$jumlah_pinjam = $data_pinjam->jumlah_pinjam;


// =====================================================
// AMBIL DATA BUKU
// =====================================================

$data_buku = $buku->tampil_data_by_kode($kode_buku);


if (!$data_buku) {

    echo "<script>
        alert('Data buku tidak ditemukan.');
        window.location='../views/verif_admin.php';
    </script>";

    exit;
}


// =====================================================
// CEK AKSI
// =====================================================

$aksi = isset($_POST['aksi']) ? $_POST['aksi'] : '';


// =====================================================
// 1. TERIMA PEMINJAMAN
// pending → dipinjam
// stok dikurangi
// =====================================================

if ($aksi == 'terima_peminjaman') {

    $tanggal_kembali = $_POST['tanggal_kembali'] ?? NULL;


    // Cek stok buku
    $stok_baru = $data_buku->stok - $jumlah_pinjam;


    if ($stok_baru < 0) {

        echo "<script>
            alert('Stok buku tidak mencukupi!');
            window.history.back();
        </script>";

        exit;
    }


    // Kurangi stok
    $buku->update_stok(
        $kode_buku,
        $stok_baru
    );


    // Ubah pending menjadi dipinjam
    $pinjam->verifikasi_pinjam(
        $id_pinjam,
        $tanggal_kembali
    );


    echo "<script>
        alert('Peminjaman berhasil diverifikasi.');
        window.location='../views/verif_admin.php';
    </script>";

    exit;
}


// =====================================================
// 2. TERIMA PENGEMBALIAN
// menunggu_verifikasi → dikembalikan
// stok ditambah
// masuk riwayat
// =====================================================

if ($aksi == 'terima_pengembalian') {


    // Pastikan status benar
    if ($data_pinjam->status != 'menunggu_verifikasi') {

        echo "<script>
            alert('Data ini bukan pengajuan pengembalian.');
            window.location='../views/verif_admin.php';
        </script>";

        exit;
    }


    // Tambahkan stok
    $stok_baru = $data_buku->stok + $jumlah_pinjam;


    $buku->update_stok(
        $kode_buku,
        $stok_baru
    );


    // Ubah status menjadi dikembalikan
    $pinjam->kembalikan_buku($id_pinjam);


    // Tambahkan ke riwayat
    $riwayat->tambah_data(
        $data_buku->nama_buku,
        $data_pinjam->nama_pengguna ?? $_SESSION['nama_pengguna'],
        $data_pinjam->tanggal_pinjam,
        date("Y-m-d"),
        $kode_buku,
        $jumlah_pinjam
    );


    echo "<script>
        alert('Pengembalian buku berhasil diverifikasi.');
        window.location='../views/verif_admin.php';
    </script>";

    exit;
}


// =====================================================
// 3. TOLAK PENGEMBALIAN
// menunggu_verifikasi → dipinjam
// stok tidak berubah
// =====================================================

if ($aksi == 'tolak_pengembalian') {


    // Pastikan status benar
    if ($data_pinjam->status != 'menunggu_verifikasi') {

        echo "<script>
            alert('Data ini bukan pengajuan pengembalian.');
            window.location='../views/verif_admin.php';
        </script>";

        exit;
    }


    // Status kembali menjadi dipinjam
    $pinjam->tolak_pengembalian($id_pinjam);


    echo "<script>
        alert('Pengembalian ditolak. Buku tetap tercatat sebagai dipinjam.');
        window.location='../views/verif_admin.php';
    </script>";

    exit;
}


// =====================================================
// AKSI TIDAK DIKENALI
// =====================================================

echo "<script>
    alert('Aksi tidak dikenali.');
    window.location='../views/verif_admin.php';
</script>";

exit;

?>