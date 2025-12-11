<?php
session_start();
$koneksi = new mysqli("localhost", "root", "", "warungbutitin");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
 // Pastikan koneksi ke database sudah ada

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $userID = $_POST['user_id'];

    $koneksi = new mysqli("localhost", "root", "", "warungbutitin");
    if ($koneksi->connect_error) {
        die("Koneksi gagal: " . $koneksi->connect_error);
    }

    // Kosongkan keranjang
    $query = $koneksi->prepare("DELETE FROM cart WHERE user_id = ?");
    $query->bind_param("i", $userID);
    $query->execute();
    $query->close();

    $koneksi->close();
    echo "Keranjang berhasil dikosongkan";
}
?>
