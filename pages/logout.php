<?php
// functions.php

// Fungsi untuk logout
function logout() {
  // Hapus session atau lakukan proses logout sesuai kebutuhan
  session_start();
  session_destroy();
}
?>
<?php
include_once "functions.php";

// Panggil fungsi logout
logout();

// Redirect ke halaman login
header("Location: login.php");
exit;
?>