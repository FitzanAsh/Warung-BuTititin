<?php
session_start();
include_once "../db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $remember = isset($_POST["remember"]) ? $_POST["remember"] : 0;

    // Query untuk mencari user berdasarkan username
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Membandingkan password yang di-hash
        if (password_verify($password, $user['password'])) {
            // Set session variabel
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role"];  // Menyimpan role di session

            // Mengatur cookie jika 'remember me' dipilih
            if ($remember) {
                $cookie_value = bin2hex(random_bytes(32)); // Menggunakan token acak yang lebih aman
                
                // Menentukan durasi cookie berdasarkan role
                if ($user['role'] == 'admin') {
                    $cookie_expiry = time() + 3600 * 24; // 1 hari untuk admin
                } else {
                    $cookie_expiry = time() + 3600; // 1 jam untuk user
                }

                // Set cookie
                setcookie("remember_me", $cookie_value, $cookie_expiry, "/", "", true, true);

                // Menyimpan token di database untuk validasi saat login berikutnya
                $cookie_hash = password_hash($cookie_value, PASSWORD_DEFAULT);
                $update_query = "UPDATE users SET remember_token = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $update_query);
                mysqli_stmt_bind_param($stmt, "si", $cookie_hash, $user["id"]);
                mysqli_stmt_execute($stmt);
            }

            // Redirect ke halaman sesuai dengan role
            if ($user['role'] == 'admin') {
                header("Location: admin/admin.php");
            } elseif ($user['role'] == 'user') {
                header("Location: user.php");
            }
            exit();
        } else {
            $_SESSION["login_error"] = "Password salah.";
        }
    } else {
        $_SESSION["login_error"] = "Username tidak ditemukan.";
    }
}

header("Location: login.php");
exit();
?>
