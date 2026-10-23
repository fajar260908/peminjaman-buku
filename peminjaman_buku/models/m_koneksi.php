<?php
require_once __DIR__ . '/../config/app.php';

class m_koneksi
{
    public mysqli $koneksi;

    public function __construct()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $config = require __DIR__ . '/../config/database.php';

        try {
            $this->koneksi = new mysqli(
                (string)$config['host'],
                (string)$config['user'],
                (string)$config['pass'],
                (string)$config['name']
            );
            $this->koneksi->set_charset('utf8mb4');
        } catch (mysqli_sql_exception $e) {
            error_log('Database connection error: ' . $e->getMessage());
            http_response_code(500);
            exit('Koneksi database gagal. Periksa konfigurasi database pada config/database.php.');
        }
    }
}
