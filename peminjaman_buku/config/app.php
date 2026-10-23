<?php
/** Shared application helpers. Base URL is detected for root or sub-folder hosting. */
if (!defined('APP_BASE_URL')) {
    $scriptName = str_replace('\\', '/', (string)($_SERVER['SCRIPT_NAME'] ?? '/'));
    $scriptDir = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
    if ($scriptDir === '.' || $scriptDir === '/') {
        $scriptDir = '';
    }
    $scriptDir = preg_replace('~/(controllers|views)$~', '', $scriptDir) ?? $scriptDir;
    define('APP_BASE_URL', rtrim($scriptDir, '/'));
}

// Konsisten dengan kalender lokal sekolah dan cookie sesi yang lebih aman.
if (!defined('APP_TIMEZONE_SET')) {
    date_default_timezone_set(getenv('APP_TIMEZONE') ?: 'Asia/Jakarta');
    define('APP_TIMEZONE_SET', true);
}
if (session_status() === PHP_SESSION_NONE) {
    $cookiePath = APP_BASE_URL === '' ? '/' : APP_BASE_URL . '/';
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => $cookiePath,
        'secure' => (!empty($_SERVER['HTTPS']) && strtolower((string)$_SERVER['HTTPS']) !== 'off'),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

if (!function_exists('app_url')) {
    function app_url(string $path = ''): string
    {
        $base = APP_BASE_URL;
        $path = ltrim($path, '/');
        return $base . ($path !== '' ? '/' . $path : '/');
    }
}

if (!function_exists('h')) {
    function h($value): string
    {
        return htmlspecialchars((string)($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('redirect_to')) {
    function redirect_to(string $path): void
    {
        header('Location: ' . app_url($path));
        exit;
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (empty($_SESSION['_csrf_token']) || !is_string($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_csrf_token" value="' . h(csrf_token()) . '">';
    }
}

if (!function_exists('require_valid_csrf')) {
    function require_valid_csrf(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            return;
        }
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $known = $_SESSION['_csrf_token'] ?? '';
        $provided = $_POST['_csrf_token'] ?? '';
        if (!is_string($known) || $known === '' || !is_string($provided) || !hash_equals($known, $provided)) {
            http_response_code(403);
            exit('Permintaan tidak valid atau sesi telah kedaluwarsa. Muat ulang halaman lalu coba lagi.');
        }
    }
}

if (!function_exists('require_login')) {
    function require_login(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (empty($_SESSION['id_pengguna']) || empty($_SESSION['role'])) {
            redirect_to('index.php');
        }
    }
}

if (!function_exists('require_admin')) {
    function require_admin(): void
    {
        require_login();
        if (($_SESSION['role'] ?? '') !== 'admin') {
            redirect_to('views/v_dasbord_user.php');
        }
    }
}

if (!defined('DENDA_PER_HARI')) {
    // Ubah nilai ini bila kebijakan sekolah berbeda.
    define('DENDA_PER_HARI', 1000);
}
