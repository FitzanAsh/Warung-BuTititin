<?php
// Koneksi ke database
include('../db_connect.php');

// Ambil ID notifikasi dari request
if (isset($_POST['notification_id'])) {
  $notificationId = $_POST['notification_id'];
  
  // Update status notifikasi menjadi 'Dibaca'
  $sql = "UPDATE notifications SET status = 'Dibaca' WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $notificationId);
  $stmt->execute();
  
  // Response success
  echo "Notifikasi telah dibaca";
}
?>
