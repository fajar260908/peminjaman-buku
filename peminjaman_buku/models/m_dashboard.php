<?php
// Panggil koneksi database
require_once __DIR__ . '/m_koneksi.php';

class m_dashboard {

    // Variabel koneksi
    public $koneksi;

    // Constructor (otomatis jalan saat class dipanggil)
    function __construct(){
        $db = new m_koneksi();
        $this->koneksi = $db->koneksi;
    }

    
    function total_buku(){

        // Hitung semua data buku
        $sql = "SELECT COUNT(*) as total FROM buku";

        $q = mysqli_query($this->koneksi,$sql);

        // Ambil hasil jumlah
        return mysqli_fetch_assoc($q)['total'];
    }

    
    function total_user(){

        // Hitung user dengan role 'user'
        $sql = "SELECT COUNT(*) as total FROM pengguna WHERE role='user'";

        $q = mysqli_query($this->koneksi,$sql);

        return mysqli_fetch_assoc($q)['total'];
    }


    function total_pending(){

        // Hitung pengajuan pinjam yang masih pending
        $sql = "SELECT COUNT(*) as total FROM pinjam WHERE status='pending'";

        $q = mysqli_query($this->koneksi,$sql);

        return mysqli_fetch_assoc($q)['total'];
    }

    
    function total_pinjam(){

        // Hitung buku yang sedang dipinjam
        $sql = "SELECT COUNT(*) as total FROM pinjam WHERE status='dipinjam'";

        $q = mysqli_query($this->koneksi,$sql);

        return mysqli_fetch_assoc($q)['total'];
    }

    
    function total_kembali(){

        // Hitung buku yang sudah dikembalikan
        $sql = "SELECT COUNT(*) as total FROM pinjam WHERE status='dikembalikan'";

        $q = mysqli_query($this->koneksi,$sql);

        return mysqli_fetch_assoc($q)['total'];
    }

}
?>