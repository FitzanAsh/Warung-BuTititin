<?php
// auth.php

session_start();

function cekOtentikasi() {
    if (!isset($_SESSION['user_id'])) {
        // Redirect ke halaman login jika belum login
        header("Location: login.php");
        exit();
    }
}
?>
