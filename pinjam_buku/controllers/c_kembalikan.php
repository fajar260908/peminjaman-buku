<?php 
session_start(); 
 
require_once "../models/m_pinjam.php"; 
 
$pinjam = new m_pinjam(); 
 
// ==============================
// CEK ID PEMINJAMAN
// ==============================

if (!isset($_GET['id_pinjam'])) { 
    echo "<script> 
        alert('ID peminjaman tidak ditemukan'); 
        window.location='../views/sedang_dipinjam.php'; 
    </script>"; 
    exit; 
} 
 
$id_pinjam = $_GET['id_pinjam']; 
 
// ==============================
// AMBIL DATA PEMINJAMAN
// ==============================

$data_pinjam = $pinjam->tampil_pinjam_by_id($id_pinjam); 
 
// ==============================
// CEK DATA
// ==============================

if (!$data_pinjam) { 
 
    echo "<script> 
        alert('Data peminjaman tidak ditemukan'); 
        window.location='../views/sedang_dipinjam.php'; 
    </script>"; 
    exit; 
} 
 
// ==============================
// CEK PEMILIK PEMINJAMAN
// ==============================

if ($_SESSION['id_pengguna'] != $data_pinjam->id_pengguna) { 
 
    echo "<script> 
        alert('Anda tidak berhak mengembalikan buku ini!'); 
        window.location='../views/sedang_dipinjam.php'; 
    </script>"; 
    exit; 
} 
 
// ==============================
// CEK STATUS
// ==============================

// Jika sudah menunggu verifikasi
if ($data_pinjam->status == 'menunggu_verifikasi') { 
 
    echo "<script> 
        alert('Pengembalian buku sedang menunggu verifikasi admin.'); 
        window.location='../views/sedang_dipinjam.php'; 
    </script>"; 
    exit; 
}

// Hanya status dipinjam yang boleh mengajukan pengembalian
if ($data_pinjam->status != 'dipinjam') {

    echo "<script> 
        alert('Buku ini tidak dapat dikembalikan.'); 
        window.location='../views/sedang_dipinjam.php'; 
    </script>"; 
    exit; 
}

// ==============================
// AJUKAN PENGEMBALIAN
// ==============================

$hasil = $pinjam->ajukan_pengembalian($id_pinjam);

// ==============================
// CEK HASIL
// ==============================

if (!$hasil) {

    echo "<script> 
        alert('Gagal mengajukan pengembalian buku.'); 
        window.location='../views/sedang_dipinjam.php'; 
    </script>"; 
    exit; 
}

// ==============================
// SELESAI
// ==============================

echo "<script> 
    alert('Pengembalian berhasil diajukan. Silakan menunggu verifikasi admin.'); 
    window.location='../views/sedang_dipinjam.php'; 
</script>"; 

exit; 
?>