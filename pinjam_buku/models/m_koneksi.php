<?php
class m_koneksi {
    public $koneksi;

    public function __construct() {
        $host = "localhost";
        $user = "root";
        $pass = ""; 
        $db   = "pinjam_buku"; 

        $this->koneksi = mysqli_connect($host, $user, $pass, $db);

        if (!$this->koneksi) {
            die("Koneksi database gagal: " . mysqli_connect_error());
        }
    }
}
