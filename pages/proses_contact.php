<?php
// Memulai sesi
session_start();

// Masukkan koneksi ke database (sesuaikan dengan informasi database Anda)
include_once "../db_connect.php";

// Fungsi validasi untuk membersihkan dan memeriksa data
function validate_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Fungsi untuk melakukan autentikasi login
function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Periksa apakah pengguna sudah login
    if (!isUserLoggedIn()) {
        echo '<script>alert("Anda harus login untuk mengirim pesan");</script>';
        echo '<script>window.location.href = "login.php";</script>';
        exit();
    }

    // Tangkap data formulir dan bersihkan
    $nama = isset($_POST["name"]) ? validate_input($_POST["name"]) : '';
    $email = isset($_POST["email"]) ? validate_input($_POST["email"]) : '';
    $pesan = isset($_POST["pesan"]) ? validate_input($_POST["pesan"]) : '';

    // Validasi data (tambahkan validasi sesuai kebutuhan)
    if (empty($nama) || empty($email) || empty($pesan)) {
        // Pesan kesalahan jika ada data yang kosong
        echo '<script>alert("Mohon isi semua field.");</script>';
    } else {
        // Siapkan statement SQL untuk menyimpan data ke database
        $stmt = $conn->prepare("INSERT INTO hubungikami (nama, email, pesan) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nama, $email, $pesan);

        if ($stmt->execute()) {
            // Pesan sukses jika data berhasil disimpan
            echo '<script>alert("Pesan Anda berhasil dikirim!");</script>';
            echo '<script>window.location.href = "index.php";</script>';
        } else {
            // Pesan kesalahan jika ada masalah dalam menyimpan data
            echo '<script>alert("Terjadi kesalahan. Silakan coba lagi. ' . $stmt->error . '");</script>';
            echo '<script>window.location.href = "index.php";</script>';
        }        

        // Tutup statement
        $stmt->close();
    }
} else {
    // Jika bukan metode POST, kembalikan ke halaman lain atau tampilkan pesan sesuai kebutuhan
    echo '<script>alert("Metode yang tidak valid.");</script>';
}

// Tutup koneksi database
$conn->close();
?>
