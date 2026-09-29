<?php

// Menghubungkan file model buku
include "../models/m_buku.php";

// Membuat objek dari class m_buku
$buku = new m_buku();

try {

    // Mengambil aksi dari URL (contoh: ?aksi=tambah)
    $aksi = $_GET['aksi'] ?? '';

    // Jika ada aksi yang dikirim
    if ($aksi != "") {

        // EDIT DATA BUKU
        if ($aksi == "edit") {

            // Ambil kode buku dari URL
            $kode = $_GET['kode_buku'] ?? '';

            // Jika kode ada
            if ($kode != "") {

                // Ambil data buku berdasarkan kode
                $data = $buku->tampil_data_by_kode($kode);

                // Tampilkan halaman edit
                include_once '../views/v_edit_buku.php';

            } else {

                // Jika kode tidak ada
                echo "Kode buku tidak ditemukan!";
            }

            exit; // Hentikan proses
        }


        // TAMBAH DATA BUKU
        if ($aksi == "tambah" && isset($_POST['nama_buku'])) {

            // Ambil data dari form
            $nama     = $_POST['nama_buku'];
            $gambar   = $_POST['gambar'] ?? '';
            $tanggal  = $_POST['tanggal_rilis'] ?? null;
            $pencipta = $_POST['pencipta'];
            $stok     = $_POST['stok'];

            // Jika tanggal kosong, set NULL
            if (empty($tanggal)) $tanggal = null;

            // Panggil fungsi tambah data di model
            $query = $buku->tambah_data(
                $gambar,
                $nama,
                $tanggal,
                $pencipta,
                $stok
            );

            // Jika berhasil
            if ($query) {

                echo "<script>
                alert('Data berhasil ditambahkan');
                window.location='../views/v_admin_buku.php';
                </script>";

            } else {

                // Jika gagal
                echo "<script>
                alert('Data gagal ditambahkan');
                window.location='../views/v_admin_buku.php';
                </script>";
            }

            exit; // Hentikan proses
        }


    
        // UPDATE DATA BUKU
        if ($aksi == "update" && isset($_POST['kode_buku'])) {

            // Ambil data dari form
            $kode     = $_POST['kode_buku'];
            $nama     = $_POST['nama_buku'];
            $gambar   = $_POST['gambar'] ?? '';
            $tanggal  = $_POST['tanggal_rilis'] ?? null;
            $pencipta = $_POST['pencipta'];
            $stok     = $_POST['stok'];

            // Jika tanggal kosong, set NULL
            if (empty($tanggal)) $tanggal = null;

            // Panggil fungsi update di model
            $query = $buku->update_data(
                $kode,
                $gambar,
                $nama,
                $tanggal,
                $pencipta,
                $stok
            );

            // Jika berhasil
            if ($query) {

                echo "<script>
                alert('Data berhasil diupdate');
                window.location='../views/v_admin_buku.php';
                </script>";

            } else {

                // Jika gagal
                echo "<script>
                alert('Data gagal diupdate');
                window.location='../views/v_admin_buku.php';
                </script>";
            }

            exit; // Hentikan proses
        }


    
        // HAPUS DATA BUKU
        
        if ($aksi == "hapus") {

            // Ambil kode buku dari URL
            $kode = $_GET['kode_buku'] ?? '';

            // Jalankan fungsi hapus di model
            $result = $buku->hapus_data($kode);

            // Jika berhasil
            if ($result) {

                echo "<script>
                alert('Data berhasil dihapus');
                window.location='../views/v_admin_buku.php';
                </script>";

            } else {

                // Jika gagal (biasanya karena foreign key)
                echo "<script>
                alert('Data gagal dihapus (mungkin buku masih dipinjam)');
                window.location='../views/v_admin_buku.php';
                </script>";
            }

            exit; // Hentikan proses
        }

    } else {


        $data_buku = $buku->tampil_data();
    }

} catch (Exception $e) {

    // Menampilkan error jika terjadi kesalahan
    echo $e->getMessage();
}
?>