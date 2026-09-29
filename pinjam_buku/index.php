<?php
session_start();

// Jika sudah login arahkan ke dashboard
if(isset($_SESSION['role'])){
    if($_SESSION['role'] == "admin"){
        header("Location: views/v_dasbord_admin.php");
    }else{
        header("Location: views/v_dasbord_user.php");
    }
    exit;
}
?>

<?php include "views/v_login.php"; ?>