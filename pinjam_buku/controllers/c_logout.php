<?php
session_start();
session_unset();
session_destroy();

header("Location: /pinjam_buku/index.php");
exit;