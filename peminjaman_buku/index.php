<?php
require_once __DIR__ . '/config/app.php';
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (!empty($_SESSION['role'])) {
    redirect_to($_SESSION['role'] === 'admin' ? 'views/v_dasbord_admin.php' : 'views/v_dasbord_user.php');
}
include __DIR__ . '/views/v_login.php';
