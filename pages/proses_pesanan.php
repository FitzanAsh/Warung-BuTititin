<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama = $_POST['nama'];
    $telepon = $_POST['telepon'];
    $alamat = $_POST['alamat'];
    $total_harga = $_POST['total_harga'];
    $user_id = $_SESSION['user_id']; // Mengambil user_id dari sesi login

    // Koneksi ke database
    $koneksi = new mysqli("localhost", "root", "", "warungbutitin");

    if ($koneksi->connect_error) {
        die("Koneksi gagal: " . $koneksi->connect_error);
    }

    // Mulai transaksi untuk memastikan atomicity
    $koneksi->begin_transaction();

    try {
        // Masukkan data pesanan ke tabel 'orders'
        $query = "INSERT INTO orders (user_id, username, nomor_telepon, alamat, status, processed_by) 
                  VALUES (?, ?, ?, ?, 'Pesanan Baru', 'User')";
        $stmt = $koneksi->prepare($query);
        if (!$stmt) {
            throw new Exception("Error preparing statement for orders: " . $koneksi->error);
        }

        $stmt->bind_param("isss", $user_id, $nama, $telepon, $alamat);
        if (!$stmt->execute()) {
            throw new Exception("Error executing order insert: " . $stmt->error);
        }

        $order_id = $stmt->insert_id; // Ambil ID pesanan yang baru saja dimasukkan

        // Ambil produk dari keranjang belanja untuk dimasukkan ke order_items
        $query_keranjang = "SELECT * FROM cart WHERE user_id = ?";
        $stmt_keranjang = $koneksi->prepare($query_keranjang);
        if (!$stmt_keranjang) {
            throw new Exception("Error preparing statement for cart: " . $koneksi->error);
        }

        $stmt_keranjang->bind_param("i", $user_id);
        $stmt_keranjang->execute();
        $result_keranjang = $stmt_keranjang->get_result();

        if ($result_keranjang->num_rows === 0) {
            throw new Exception("Keranjang kosong untuk user_id: $user_id");
        }

        // Masukkan produk ke tabel 'order_items'
        while ($item = $result_keranjang->fetch_assoc()) {
            $product_id = $item['product_id'];
            $jumlah = $item['jumlah'];

            // Ambil harga produk dari tabel products
            $query_harga = "SELECT price FROM products WHERE id = ?";
            $stmt_harga = $koneksi->prepare($query_harga);
            if (!$stmt_harga) {
                throw new Exception("Error preparing statement for products: " . $koneksi->error);
            }

            $stmt_harga->bind_param("i", $product_id);
            $stmt_harga->execute();
            $result_harga = $stmt_harga->get_result();

            if ($result_harga->num_rows === 0) {
                // Produk tidak ditemukan, log dan lanjutkan
                error_log("Produk tidak ditemukan untuk product_id: $product_id");
                continue;
            }
            $harga = $result_harga->fetch_assoc()['price'];

            // Masukkan data item ke tabel order_items
            $query_order_items = "INSERT INTO order_items (order_id, product_id, jumlah, harga) 
                                  VALUES (?, ?, ?, ?)";
            $stmt_order_items = $koneksi->prepare($query_order_items);
            if (!$stmt_order_items) {
                throw new Exception("Error preparing statement for order_items: " . $koneksi->error);
            }

            $stmt_order_items->bind_param("iiid", $order_id, $product_id, $jumlah, $harga);
            if (!$stmt_order_items->execute()) {
                throw new Exception("Error inserting order_items for order_id=$order_id: " . $stmt_order_items->error);
            }
        }

        // Hapus produk dari keranjang setelah pesanan dibuat
        $query_hapus_keranjang = "DELETE FROM cart WHERE user_id = ?";
        $stmt_hapus_keranjang = $koneksi->prepare($query_hapus_keranjang);
        if (!$stmt_hapus_keranjang) {
            throw new Exception("Error preparing statement for deleting cart: " . $koneksi->error);
        }

        $stmt_hapus_keranjang->bind_param("i", $user_id);
        if (!$stmt_hapus_keranjang->execute()) {
            throw new Exception("Error deleting cart items: " . $stmt_hapus_keranjang->error);
        }

        // Commit transaksi jika semua query berhasil
        $koneksi->commit();

        // Mengirimkan response ke frontend
        echo 'Pesanan berhasil dibuat!';
    } catch (Exception $e) {
        // Jika ada error, rollback transaksi
        $koneksi->rollback();
        error_log($e->getMessage());
        echo "Terjadi kesalahan saat memproses pesanan.";
    } finally {
        // Menutup koneksi dan statement
        if (isset($stmt)) $stmt->close();
        if (isset($stmt_keranjang)) $stmt_keranjang->close();
        if (isset($stmt_harga)) $stmt_harga->close();
        if (isset($stmt_order_items)) $stmt_order_items->close();
        if (isset($stmt_hapus_keranjang)) $stmt_hapus_keranjang->close();
        $koneksi->close();
    }
}
?>
