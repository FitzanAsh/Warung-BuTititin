<?php
session_start();
function login($username, $password) {
    // Koneksi ke database (sesuaikan dengan koneksi Anda)
    $conn = mysqli_connect("localhost", "root", "", "warungbutitin");
  
    // Query ke tabel pengguna untuk mencari pengguna dengan username dan password yang sesuai
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);
  
    // Cek apakah pengguna ditemukan dalam tabel pengguna
    if (mysqli_num_rows($result) == 1) {
        // Jika ditemukan, set session untuk pengguna yang berhasil login
        $row = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];
        mysqli_close($conn); // Menutup koneksi database
        return true;
    } else {
        mysqli_close($conn); // Menutup koneksi database
        return false;
    }
}
?>