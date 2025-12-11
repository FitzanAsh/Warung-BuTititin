<?php
// Fungsi untuk mengambil data produk dari database
function getProducts($category_id = null) {
  global $conn;

  $query = "SELECT * FROM Products";
  if ($category_id) {
    $query .= " WHERE category_id = $category_id";
  }

  $result = mysqli_query($conn, $query);

  $products = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
  }

  return $products;
}

// Fungsi untuk memfilter produk berdasarkan kategori
function filterProductsByCategory($category_id) {
  return getProducts($category_id);
}

// Fungsi untuk memvalidasi login admin
function isLoggedIn() {
  // Implementasikan logika validasi login admin sesuai kebutuhan
  // Contoh: return true jika sudah login, return false jika belum login
}
?>