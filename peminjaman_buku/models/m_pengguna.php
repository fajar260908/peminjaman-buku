<?php
require_once __DIR__ . '/m_koneksi.php';

class m_pengguna
{
    private mysqli $koneksi;

    public function __construct()
    {
        $this->koneksi = (new m_koneksi())->koneksi;
    }

    public function tampil_data(): array
    {
        $result = $this->koneksi->query('SELECT id_pengguna, nama_pengguna, jenis_kelamin, alamat, role, email, tanggal_lahir_pengguna FROM pengguna ORDER BY id_pengguna DESC');
        $data = [];
        while ($row = $result->fetch_object()) $data[] = $row;
        return $data;
    }

    public function tampil_data_by_id($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) return null;
        $stmt = $this->koneksi->prepare('SELECT * FROM pengguna WHERE id_pengguna=? LIMIT 1');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_object() ?: null;
    }

    public function tambah_data($nama, $email, $jk, $tgl, $alamat, $pass, $role): bool
    {
        $nama = trim((string)$nama);
        $email = trim((string)$email);
        $jk = strtolower(trim((string)$jk));
        $tgl = trim((string)$tgl);
        $alamat = trim((string)$alamat);
        $role = trim((string)$role);
        if ($nama === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !in_array($jk, ['laki-laki', 'perempuan'], true) || !in_array($role, ['admin', 'user'], true) || strlen((string)$pass) < 8 || !$this->validDate($tgl)) return false;
        $check = $this->koneksi->prepare('SELECT id_pengguna FROM pengguna WHERE nama_pengguna=? OR email=? LIMIT 1');
        $check->bind_param('ss', $nama, $email);
        $check->execute();
        if ($check->get_result()->fetch_object()) return false;
        $hash = password_hash((string)$pass, PASSWORD_DEFAULT);
        $stmt = $this->koneksi->prepare('INSERT INTO pengguna (nama_pengguna,email,jenis_kelamin,tanggal_lahir_pengguna,alamat,password,role) VALUES (?,?,?,?,?,?,?)');
        $stmt->bind_param('sssssss', $nama, $email, $jk, $tgl, $alamat, $hash, $role);
        try { return $stmt->execute(); } catch (mysqli_sql_exception $e) { error_log('User insert failed: ' . $e->getMessage()); return false; }
    }

    public function ubah_data($id, $nama, $email, $jk, $tgl, $alamat, $pass, $role): bool
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        $nama = trim((string)$nama);
        $email = trim((string)$email);
        $jk = strtolower(trim((string)$jk));
        $tgl = trim((string)$tgl);
        $alamat = trim((string)$alamat);
        $role = trim((string)$role);
        if (!$id || $nama === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !in_array($jk, ['laki-laki', 'perempuan'], true) || !in_array($role, ['admin', 'user'], true) || !$this->validDate($tgl)) return false;
        $check = $this->koneksi->prepare('SELECT id_pengguna FROM pengguna WHERE (nama_pengguna=? OR email=?) AND id_pengguna<>? LIMIT 1');
        $check->bind_param('ssi', $nama, $email, $id);
        $check->execute();
        if ($check->get_result()->fetch_object()) return false;

        if ((string)$pass !== '') {
            if (strlen((string)$pass) < 8) return false;
            $hash = password_hash((string)$pass, PASSWORD_DEFAULT);
            $stmt = $this->koneksi->prepare('UPDATE pengguna SET nama_pengguna=?,email=?,jenis_kelamin=?,tanggal_lahir_pengguna=?,alamat=?,role=?,password=? WHERE id_pengguna=?');
            $stmt->bind_param('sssssssi', $nama, $email, $jk, $tgl, $alamat, $role, $hash, $id);
        } else {
            $stmt = $this->koneksi->prepare('UPDATE pengguna SET nama_pengguna=?,email=?,jenis_kelamin=?,tanggal_lahir_pengguna=?,alamat=?,role=? WHERE id_pengguna=?');
            $stmt->bind_param('ssssssi', $nama, $email, $jk, $tgl, $alamat, $role, $id);
        }
        try { return $stmt->execute(); } catch (mysqli_sql_exception $e) { error_log('User update failed: ' . $e->getMessage()); return false; }
    }

    public function hapus_data($id): bool
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) return false;
        $stmt = $this->koneksi->prepare('DELETE FROM pengguna WHERE id_pengguna=?');
        $stmt->bind_param('i', $id);
        try { return $stmt->execute() && $stmt->affected_rows === 1; } catch (mysqli_sql_exception $e) { error_log('User delete failed: ' . $e->getMessage()); return false; }
    }

    public function login($username_email, $password)
    {
        $key = trim((string)$username_email);
        $stmt = $this->koneksi->prepare('SELECT * FROM pengguna WHERE nama_pengguna=? OR email=? LIMIT 1');
        $stmt->bind_param('ss', $key, $key);
        $stmt->execute();
        $data = $stmt->get_result()->fetch_object();
        return ($data && password_verify((string)$password, $data->password)) ? $data : false;
    }

    private function validDate(string $date): bool
    {
        $parsed = DateTimeImmutable::createFromFormat('!Y-m-d', $date);
        return $parsed !== false && $parsed->format('Y-m-d') === $date;
    }
}
