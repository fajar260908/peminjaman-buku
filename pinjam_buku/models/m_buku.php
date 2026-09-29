<?php
// Panggil koneksi database
include_once "m_koneksi.php";

class m_buku {

    // Variabel untuk menyimpan koneksi
    private $koneksi;

    // Constructor (otomatis jalan saat class dipanggil)
    public function __construct() {
        $db = new m_koneksi();
        $this->koneksi = $db->koneksi;
    }

    
    function tambah_data($gambar, $nama, $tgl, $pencipta, $stok) {

        // Pastikan stok angka
        $stok = (int)$stok;

        // Query insert data
        $sql = "INSERT INTO buku 
                (gambar, nama_buku, tanggal_rilis, pencipta, stok) 
                VALUES 
                ('$gambar', '$nama', '$tgl', '$pencipta', '$stok')";

        // Jalankan query
        return mysqli_query($this->koneksi, $sql);
    }

    
    function hapus_data($kode) {

        // Hapus buku berdasarkan kode
        return mysqli_query($this->koneksi, 
            "DELETE FROM buku WHERE kode_buku='$kode'");
    }

    
    function tampil_data() {

        // Ambil semua data buku
        $sql = "SELECT * FROM buku ORDER BY kode_buku DESC";

        $q = mysqli_query($this->koneksi, $sql);

        $data = [];

        
        while($d = mysqli_fetch_object($q)) {
            $data[] = $d;
        }

        return $data;
    }

    
    function tampil_data_by_kode($kode) {

        // Amankan input
        $kode = mysqli_real_escape_string($this->koneksi, $kode);

        // Ambil 1 data buku
        $q = mysqli_query($this->koneksi,
            "SELECT * FROM buku WHERE kode_buku='$kode'");

        return mysqli_fetch_object($q);
    }

    
    function update_data($kode, $gambar, $nama, $tgl, $pencipta, $stok) {

        // Pastikan stok angka
        $stok = (int)$stok;

        // Query update
        $sql = "UPDATE buku SET 
                    gambar='$gambar',
                    nama_buku='$nama',
                    tanggal_rilis='$tgl',
                    pencipta='$pencipta',
                    stok='$stok'
                WHERE kode_buku='$kode'";

        return mysqli_query($this->koneksi, $sql);
    }

    
    function update_stok($kode_buku, $stok_baru){

        // Pastikan stok angka
        $stok_baru = (int)$stok_baru;

        // Update hanya kolom stok
        $sql = "UPDATE buku 
                SET stok='$stok_baru'
                WHERE kode_buku='$kode_buku'";

        return mysqli_query($this->koneksi,$sql);
    }
}
?>