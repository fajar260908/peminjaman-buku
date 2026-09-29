<?php
// menghubungkan file koneksi database
require_once "m_koneksi.php";

// membuat class m_pengguna untuk mengelola data user
class m_pengguna {
    private $koneksi;

    // constructor → otomatis jalan saat class dipanggil
    public function __construct() {
        $db = new m_koneksi();
        $this->koneksi = $db->koneksi;
    }

    // fungsi untuk menampilkan semua data pengguna
    public function tampil_data() {
        $sql = "SELECT * FROM pengguna";
        $query = mysqli_query($this->koneksi, $sql);

        $data = [];

        
        while ($row = mysqli_fetch_object($query)) {
            $data[] = $row;
        }

        return $data;
    }

    // fungsi untuk mengambil 1 data user berdasarkan ID
    public function tampil_data_by_id($id) {
        $sql = "SELECT * FROM pengguna WHERE id_pengguna = '$id'";
        $query = mysqli_query($this->koneksi, $sql);

        return mysqli_fetch_object($query);
    }

    // fungsi untuk menambahkan data user baru
    public function tambah_data($nama, $email, $jk, $tgl, $alamat, $pass, $role) {

        // mengenkripsi password supaya aman
        $password = password_hash($pass, PASSWORD_DEFAULT);

        $sql = "INSERT INTO pengguna 
            (nama_pengguna, email, jenis_kelamin, tanggal_lahir_pengguna, alamat, password, role)
            VALUES 
            ('$nama', '$email', '$jk', '$tgl', '$alamat', '$password', '$role')";

        return mysqli_query($this->koneksi, $sql);
    }

    // fungsi untuk mengubah data user
    public function ubah_data($id, $nama, $email, $jk, $tgl, $alamat, $pass, $role) {

        // jika password diisi → update password
        if (!empty($pass)) {

            // hash password baru
            $password = password_hash($pass, PASSWORD_DEFAULT);

            // query tambahan untuk update password
            $pass_sql = ", password='$password'";
        } else {
            // jika kosong → tidak update password
            $pass_sql = "";
        }

        $sql = "UPDATE pengguna SET
                nama_pengguna='$nama',
                email='$email',
                jenis_kelamin='$jk',
                tanggal_lahir_pengguna='$tgl',
                alamat='$alamat',
                role='$role'
                $pass_sql
            WHERE id_pengguna='$id'";

        return mysqli_query($this->koneksi, $sql);
    }

    // fungsi untuk menghapus user berdasarkan ID
    public function hapus_data($id) {
        return mysqli_query(
            $this->koneksi,
            "DELETE FROM pengguna WHERE id_pengguna='$id'"
        );
    }

    // fungsi login (bisa pakai username atau email)
    public function login($username_email, $password) {
        
        // query mencari user berdasarkan username atau email
        $sql = "SELECT * FROM pengguna 
                WHERE nama_pengguna = '$username_email' 
                   OR email = '$username_email'
                LIMIT 1";

        $query = mysqli_query($this->koneksi, $sql);

        // jika data ditemukan
        if ($query && mysqli_num_rows($query) == 1) {

            $data = mysqli_fetch_object($query);

            // verifikasi password (cocokkan dengan hash)
            if (password_verify($password, $data->password)) {
                return $data; // login berhasil
            }
        }

        // jika gagal login
        return false;
    }
}
?>