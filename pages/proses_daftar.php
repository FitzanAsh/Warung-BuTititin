<?php
// Masukkan koneksi ke database (sesuaikan dengan informasi database Anda)
include_once "../db_connect.php";

// Fungsi validasi untuk membersihkan dan memeriksa data
function validate_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Inisialisasi variabel kesalahan password
$password_error = "";

// Periksa apakah formulir telah dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Tangkap data formulir dan bersihkan
    $username = validate_input($_POST["username"]);
    $password = validate_input($_POST["password"]);
    $confirm_password = validate_input($_POST["confirm_password"]);

    // Validasi data (tambahkan validasi sesuai kebutuhan)
    if (empty($username) || empty($password) || empty($confirm_password)) {
        // Pesan kesalahan jika ada data yang kosong
        echo "Mohon isi semua field.";
    } else {
        // Cek apakah password dan konfirmasi password cocok
        if ($password != $confirm_password) {
            // Pesan kesalahan jika password tidak cocok
            $password_error = "Password dan konfirmasi password tidak cocok.";
        } else {
            // Hash password untuk keamanan
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Role yang akan diisikan
            $role = 'user';

            // Check username uniqueness before inserting into the database
            $check_username_query = "SELECT * FROM users WHERE username = ?";
            $check_stmt = $conn->prepare($check_username_query);
            $check_stmt->bind_param("s", $username);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows > 0) {
                // Username already exists
                echo '<script>alert("Username sudah digunakan. Pilih username lain.");</script>';
                echo '<script>window.location.href = "../pages/register.php";</script>';
                exit();
            }

            // Siapkan statement SQL untuk menyimpan data ke database (gunakan parameter terikat untuk keamanan)
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashed_password, $role);

            // Eksekusi statement SQL
            if ($stmt->execute()) {
                // Pesan sukses jika data berhasil disimpan
                echo '<script>alert("Pendaftaran berhasil!");</script>';

                // Redirect ke halaman login
                header("Location: login.php");
                exit();
            } else {
                // Pesan kesalahan jika ada masalah dalam menyimpan data
                echo "Terjadi kesalahan. Silakan coba lagi.";
            }

            // Tutup statement dan koneksi database
            $stmt->close();
            $check_stmt->close();
        }
    }
}

// Cek apakah ada kesalahan password dan arahkan kembali ke register.php jika ada
if (!empty($password_error)) {
    echo '<script>alert("' . htmlspecialchars($password_error) . '");</script>';
    echo '<script>window.location.href = "../pages/register.php";</script>';
    exit();
}
?>
