<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "warungbutitin";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $conn->begin_transaction();

    try {
        // Validasi order_id
        $sql_check_order = "SELECT id FROM orders WHERE id = ?";
        $stmt_check_order = $conn->prepare($sql_check_order);
        $stmt_check_order->bind_param("i", $order_id);
        $stmt_check_order->execute();
        $result_check_order = $stmt_check_order->get_result();

        if ($result_check_order->num_rows === 0) {
            throw new Exception("Order ID tidak valid atau tidak ditemukan.");
        }
        $stmt_check_order->close();

        // Update status pesanan dan processed_by menjadi 'Admin'
        $sql_update = "UPDATE orders SET status = ?, processed_by = 'Admin' WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $status, $order_id);
        $stmt_update->execute();
        $stmt_update->close();

        // Ambil status terbaru setelah update
        $sql_status = "SELECT status FROM orders WHERE id = ?";
        $stmt_status = $conn->prepare($sql_status);
        $stmt_status->bind_param("i", $order_id);
        $stmt_status->execute();
        $result_status = $stmt_status->get_result();
        $status_row = $result_status->fetch_assoc();
        $new_status = $status_row['status'];
        $stmt_status->close();

        // Ambil detail pesanan dengan pengelompokkan produk berdasarkan nama
        $sql_items = "
            SELECT p.name AS product_name, SUM(oi.jumlah) AS total_jumlah, SUM(oi.jumlah * oi.harga) AS total_harga
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
            GROUP BY p.name";
        $stmt_items = $conn->prepare($sql_items);
        $stmt_items->bind_param("i", $order_id);
        $stmt_items->execute();
        $result_items = $stmt_items->get_result();

        $message = "";
        $first = true;  // Untuk memastikan format pesan diawali dengan produk pertama
        while ($item = $result_items->fetch_assoc()) {
            $product_name = $item['product_name'];
            $total_jumlah = $item['total_jumlah'];
            $total_harga = $item['total_harga'];
            // Format pesan dengan cara yang lebih sederhana
            if (!$first) {
                $message .= ", "; // Pisahkan dengan koma jika bukan produk pertama
            }
            $message .= "$product_name (Jumlah: $total_jumlah, Harga Total: $total_harga)";
            $first = false;
        }
        $stmt_items->close();

        // Masukkan notifikasi dengan status_pesanan yang sesuai
        $sql_notif = "INSERT INTO notifications (order_id, message, status, status_pesanan) VALUES (?, ?, 'Belum Dibaca', ?)";
        $stmt_notif = $conn->prepare($sql_notif);
        $stmt_notif->bind_param("iss", $order_id, $message, $new_status);
        $stmt_notif->execute();
        $stmt_notif->close();

        $conn->commit();
        echo "Status berhasil diperbarui dan notifikasi ditambahkan.";
    } catch (Exception $e) {
        $conn->rollback();
        die("Gagal memproses: " . $e->getMessage());
    }
}

// Mengambil data pesanan dengan status "Pesanan Baru"
$sql_baru = "SELECT * FROM orders WHERE status = 'Pesanan Baru'";
$result_baru = $conn->query($sql_baru);

// Mengambil data pesanan dengan status "Pesanan Diproses"
$sql_diproses = "SELECT * FROM orders WHERE status = 'Pesanan Diproses'";
$result_diproses = $conn->query($sql_diproses);

// Mengambil data pesanan dengan status "Pesanan Sampai"
$sql_selesai = "SELECT * FROM orders WHERE status = 'Pesanan Sampai'";
$result_selesai = $conn->query($sql_selesai);

// Mengambil data pesanan dengan status "Pesanan Dikirim"
$sql_dikirim = "SELECT * FROM orders WHERE status = 'Pesanan Dikirim'";
$result_dikirim = $conn->query($sql_dikirim);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan</title>
    <style>
         body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    min-height: 100vh;
    background-color: whitesmoke;
}

aside {
    width: 250px;
    background: #333;
    color: #fff;
    padding: 0px;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    box-sizing: border-box;
    position: fixed; /* Tambahkan baris ini untuk membuat sidebar tetap */
    height: 100%; /* Atur tinggi menjadi 100% agar menutupi seluruh tampilan */
}

/* Gaya CSS yang lain tetap sama... */

section.admin {
    width: calc(100% - 250px); /* Sesuaikan lebar untuk menampung sidebar yang tetap */
    /* Gaya CSS yang lain tetap sama... */
}


        header {
            margin-left: 209px;
    background-color: #F26D21;
    color: white;
    padding-top: 10px;
    padding-block:  10px;
    padding-left: 35px;
    text-align: left;
    height: 40px;
    width: 1200px;
    display: flex;
    align-items: center;
}
section.admin {
            width: 75%;
            display: flex;
            justify-content: center;
            flex-direction: column;
            margin-left: 280px;
            background-color: white;
            padding-right: 30px;
            padding-left: 30px;
            padding-bottom: 30px;
            margin-top: 30px;
            padding-top: 6px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            flex: 1;
        }

header h2{
    color: white;
    margin-left: 35px;
}

header svg {
    margin-left: 923px; 
}
aside button {
    margin-left: 30px;
    margin-top: 325px; 
}

       
        .admin-title {
            width: 196px;
            display: flex;
            align-items: center;
            margin-bottom: 0px;
            margin-top: 0px;
            margin-left: 20px;
            border-bottom: 4px solid #716E6E;
        }

        .admin-title img {
            height: 35px;
            margin-right: 5px;
            margin-top: 10px;
            margin-left: 25px;
            margin-bottom: 12px;
        }

        .add-product-btn {
            background-color: #F26D21;
            color: white;
            font-size: 16px; 
            padding: 10px 10px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            border-radius: 5px;
            width: 160px;
            height: 20px;
            margin-top: 16px;
            margin-bottom: 5px;
            margin-left: 27px;
            transition: background-color 0.3s ease;
        }

        .add-product-btn:hover {
            background-color: #D9631E;
        }

        .add-product-btn svg {
        width: 20px;
        height: 20px;
        margin-right: 10px;
        margin-left: 10px;
        fill: #ffffff;
    }

    .ulasan-product-btn {
            background-color: #595959;
            color: #E6E6E6;
            font-size: 16px; 
            padding: 10px 10px;
            border: 1px solid #BABABA;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            border-radius: 5px;
            width: 160px;
            height: 20px;
            margin-top: 15px;
            margin-bottom: 5px;
            margin-left: 27px;
            transition: background-color 0.3s ease;
        }

        .ulasan-product-btn:hover {
            background-color: #595959;
            color: #E6E6E6;
        }

        .ulasan-product-btn svg {
        width: 20px;
        height: 20px;
        margin-right: 10px;
        margin-left: 10px;
        fill: #E6E6E6;
    }

    .pelanggan-product-btn {
            background-color: #414141;
            color: #BABABA;
            font-size: 16px; 
            padding: 10px 10px;
            border: 1px solid #BABABA;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            border-radius: 5px;
            width: 160px;
            height: 20px;
            margin-top: 15px;
            margin-bottom: 15px;
            margin-left: 27px;
            transition: background-color 0.3s ease;
        }

        .pelanggan-product-btn:hover {
            background-color: #595959;
            color: #E6E6E6;
        }

        .pelanggan-product-btn svg {
        width: 20px;
        height: 20px;
        margin-right: 10px;
        margin-left: 10px;
        fill: #BABABA;
    }

    .ulasan-product-btn svg,
.pelanggan-product-btn svg {
    transition: fill 0.3s ease;
}

.ulasan-product-btn:hover svg {
    fill: #E6E6E6;
}

.pelanggan-product-btn:hover svg {
    fill: #E6E6E6;
}

button {
    padding: 10px 20px;
    background-color: #dc3545; /* Warna latar belakang tombol */
    color: #fff; /* Warna teks tombol */
    border: none;
    border-radius: 5px;
    cursor: pointer;
}


button:hover {
    background-color: #c82333; /* Warna latar belakang tombol saat dihover */
}

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #F26D21;
            color: white;
        }

        /* CSS untuk baris dengan status 'Pesanan Dikirim' */
    .pesanan-dikirim {
        background-color: yellow; /* Memberikan latar belakang kuning */
    }

    /* Menyembunyikan tombol aksi untuk status 'Pesanan Dikirim' */
    .pesanan-dikirim .no-action {
        display: none;
    }

    #orderTable {
    width: 100%;
    border-collapse: collapse; /* Untuk menghindari spasi antar border */
    margin-bottom: 20px; /* Margin bawah 20px, Anda bisa sesuaikan sesuai kebutuhan */
}
#orderTable th, #orderTable td {
    padding: 10px;
    border: 1px solid #ddd; /* Gaya border untuk tabel */
    text-align: left;
}

.tabs {
    list-style: none;
    padding: 0;
    display: flex;
    border-bottom: 2px solid #ddd;
}

.tab-link {
    padding: 10px 20px;
    cursor: pointer;
    border: 1px solid #ddd;
    border-bottom: none;
    background: #f9f9f9;
    margin-right: 5px;
    transition: background 0.3s;
}

.tab-link.active {
    background: #F26D21;
    font-weight: bold;
    color: #fff;
    border-bottom: 2px solid #fff;
}

.tab-content {
    display: none;
    padding: 20px;
    border: 1px solid #ddd;
    background: #fff;
}

.tab-content.active {
    display: block;
}

.btn-kirim {
    background-color: #11B331;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
}
.btn-kirim:hover {
    background-color: #0F8C28;
}


    </style>
</head>

<body>
<aside>
    <a href="admin.php">
        <div class="admin-title">
            <img src="../../assets/images/WarungButitin2.png" alt="Warung BuTITIN">
        </div>
    </a>
    <a href="../admin/add_product.php" class="add-product-btn"> <!-- Tambahkan href ke halaman add_products.php -->
        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ffffff">
            <g id="SVGRepo_iconCarrier">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M2 4.5C2 3.11929 3.11929 2 4.5 2H19.5C20.8807 2 22 3.11929 22 4.5V19.5C22 20.8807 20.8807 22 19.5 22H4.5C3.11929 22 2 20.8807 2 19.5V4.5ZM12.5 5.5C13.0523 5.5 13.5 5.94772 13.5 6.5V10.5H17.5C18.0523 10.5 18.5 10.9477 18.5 11.5V12.5C18.5 13.0523 18.0523 13.5 17.5 13.5H13.5V17.5C13.5 18.0523 13.0523 18.5 12.5 18.5H11.5C10.9477 18.5 10.5 18.0523 10.5 17.5V13.5H6.5C5.94772 13.5 5.5 13.0523 5.5 12.5V11.5C5.5 10.9477 5.94772 10.5 6.5 10.5H10.5V6.5C10.5 5.94772 10.9477 5.5 11.5 5.5H12.5Z"
                    fill="#ffffff"></path>
            </g>
        </svg>
        Tambah Produk
    </a>
    <a href="../admin/pesanan.php" class="ulasan-product-btn">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart-check-fill" viewBox="0 0 16 16">
  <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0m-1.646-7.646-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L8 8.293l2.646-2.647a.5.5 0 0 1 .708.708"/>
</svg>
    Pesanan
</a>

<a href="../admin/pelanggan.php" class="pelanggan-product-btn">
    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="#f5f5f5" stroke="#f5f5f5" stroke-width="0.00024000000000000003">
        <g id="SVGRepo_iconCarrier">
            <path fill="none" d="M0 0h24v24H0z"></path>
            <path d="M2 22a8 8 0 1 1 16 0H2zm8-9c-3.315 0-6-2.685-6-6s2.685-6 6-6 6 2.685 6 6-2.685 6-6 6zm7.363 2.233A7.505 7.505 0 0 1 22.983 22H20c0-2.61-1-4.986-2.637-6.767zm-2.023-2.276A7.98 7.98 0 0 0 18 7a7.964 7.964 0 0 0-1.015-3.903A5 5 0 0 1 21 8a4.999 4.999 0 0 1-5.66 4.957z"></path>
        </g>
    </svg>
    Pelanggan
</a>
    <form action="" method="POST">
            <button type="submit" name="logout">Logout</button>
        </form>
</aside>


    <div style="flex: 1;">

    <header>
    <h2>Pesanan</h2>
</header>

<section class="admin">
    <!-- Tab navigation -->
    <ul class="tabs">
        <li class="tab-link active" data-tab="pesanan-baru">Pesanan Baru</li>
        <li class="tab-link" data-tab="pesanan-diproses">Pesanan Diproses</li>
        <li class="tab-link" data-tab="pesanan-dikirim">Pesanan Dikirim</li>
        <li class="tab-link" data-tab="pesanan-selesai">Pesanan Selesai</li>
    </ul>

    <!-- Tab content -->
    <div id="pesanan-baru" class="tab-content active">
        <h3>Pesanan Baru</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pengguna</th>
                    <th>Nomor Telepon</th>
                    <th>Alamat</th>
                    <th>Waktu Pemesanan</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($order = $result_baru->fetch_assoc()) :
                    $order_id = $order['id'];
                    $sql_items = "SELECT oi.jumlah, oi.harga, p.name 
                                  FROM order_items oi 
                                  JOIN products p ON oi.product_id = p.id 
                                  WHERE oi.order_id = ?";
                    $stmt_items = $conn->prepare($sql_items);
                    $stmt_items->bind_param("i", $order_id);
                    $stmt_items->execute();
                    $result_items = $stmt_items->get_result();
                    $total_price = 0;
                    $produk = [];
                    $jumlah = [];
                    while ($item = $result_items->fetch_assoc()) {
                        $produk[] = $item['name'];
                        $jumlah[] = $item['jumlah'];
                        $total_price += $item['harga'] * $item['jumlah'];
                    }
                    $stmt_items->close();
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $order['username']; ?></td>
                    <td><?= $order['nomor_telepon']; ?></td>
                    <td><?= $order['alamat']; ?></td>
                    <td><?= $order['waktu_pemesanan']; ?></td>
                    <td><?= implode(", ", $produk); ?></td>
                    <td><?= implode(", ", $jumlah); ?></td>
                    <td><?= "Rp. " . number_format($total_price, 2, ',', '.'); ?></td>
                    <td><?= $order['status']; ?></td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="order_id" value="<?= $order['id']; ?>">
                            <button type="submit" name="status" value="Pesanan Diproses" class="btn-kirim">Proses</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Konten untuk tab lainnya diimplementasikan mirip dengan Pesanan Baru -->
    <div id="pesanan-diproses" class="tab-content">
    <h3>Pesanan Diproses</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pengguna</th>
                <th>Nomor Telepon</th>
                <th>Alamat</th>
                <th>Waktu Pemesanan</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if ($result_diproses->num_rows > 0) {
                while ($order = $result_diproses->fetch_assoc()) :
                    $order_id = $order['id'];
                    $sql_items = "SELECT oi.jumlah, oi.harga, p.name 
                                  FROM order_items oi 
                                  JOIN products p ON oi.product_id = p.id 
                                  WHERE oi.order_id = ?";
                    $stmt_items = $conn->prepare($sql_items);
                    $stmt_items->bind_param("i", $order_id);
                    $stmt_items->execute();
                    $result_items = $stmt_items->get_result();
                    $total_price = 0;
                    $produk = [];
                    $jumlah = [];
                    while ($item = $result_items->fetch_assoc()) {
                        $produk[] = $item['name'];
                        $jumlah[] = $item['jumlah'];
                        $total_price += $item['harga'] * $item['jumlah'];
                    }
                    $stmt_items->close();
            ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $order['username']; ?></td>
                <td><?= $order['nomor_telepon']; ?></td>
                <td><?= $order['alamat']; ?></td>
                <td><?= $order['waktu_pemesanan']; ?></td>
                <td><?= implode(", ", $produk); ?></td>
                <td><?= implode(", ", $jumlah); ?></td>
                <td><?= "Rp. " . number_format($total_price, 2, ',', '.'); ?></td>
                <td><?= $order['status']; ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="order_id" value="<?= $order['id']; ?>">
                        <button type="submit" name="status" value="Pesanan Dikirim" class="btn-kirim">Kirim</button>
                    </form>
                </td>
            </tr>
            <?php endwhile;
            } else {
                echo "<tr><td colspan='10'>Tidak ada data untuk Pesanan Diproses.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<div id="pesanan-dikirim" class="tab-content">
    <h3>Pesanan Dikirim</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pengguna</th>
                <th>Nomor Telepon</th>
                <th>Alamat</th>
                <th>Waktu Pemesanan</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Total Harga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if ($result_dikirim->num_rows > 0) {
                while ($order = $result_dikirim->fetch_assoc()) :
                    $order_id = $order['id'];
                    $sql_items = "SELECT oi.jumlah, oi.harga, p.name 
                                  FROM order_items oi 
                                  JOIN products p ON oi.product_id = p.id 
                                  WHERE oi.order_id = ?";
                    $stmt_items = $conn->prepare($sql_items);
                    $stmt_items->bind_param("i", $order_id);
                    $stmt_items->execute();
                    $result_items = $stmt_items->get_result();
                    $total_price = 0;
                    $produk = [];
                    $jumlah = [];
                    while ($item = $result_items->fetch_assoc()) {
                        $produk[] = $item['name'];
                        $jumlah[] = $item['jumlah'];
                        $total_price += $item['harga'] * $item['jumlah'];
                    }
                    $stmt_items->close();
            ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $order['username']; ?></td>
                <td><?= $order['nomor_telepon']; ?></td>
                <td><?= $order['alamat']; ?></td>
                <td><?= $order['waktu_pemesanan']; ?></td>
                <td><?= implode(", ", $produk); ?></td>
                <td><?= implode(", ", $jumlah); ?></td>
                <td><?= "Rp. " . number_format($total_price, 2, ',', '.'); ?></td>
                <td><?= $order['status']; ?></td>
            </tr>
            <?php endwhile;
            } else {
                echo "<tr><td colspan='10'>Tidak ada data untuk Pesanan Dikirim.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<div id="pesanan-selesai" class="tab-content">
    <h3>Pesanan Selesai</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pengguna</th>
                <th>Nomor Telepon</th>
                <th>Alamat</th>
                <th>Waktu Pemesanan</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Total Harga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if ($result_selesai->num_rows > 0) {
                while ($order = $result_selesai->fetch_assoc()) :
                    // Ambil item pesanan
                    $order_id = $order['id'];
                    $sql_items = "SELECT oi.jumlah, oi.harga, p.name 
                                  FROM order_items oi 
                                  JOIN products p ON oi.product_id = p.id 
                                  WHERE oi.order_id = ?";
                    $stmt_items = $conn->prepare($sql_items);
                    $stmt_items->bind_param("i", $order_id);
                    $stmt_items->execute();
                    $result_items = $stmt_items->get_result();
                    $total_price = 0;
                    $produk = [];
                    $jumlah = [];
                    while ($item = $result_items->fetch_assoc()) {
                        $produk[] = $item['name'];
                        $jumlah[] = $item['jumlah'];
                        $total_price += $item['harga'] * $item['jumlah'];
                    }
                    $stmt_items->close();
            ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($order['username']); ?></td>
                <td><?= htmlspecialchars($order['nomor_telepon']); ?></td>
                <td><?= htmlspecialchars($order['alamat']); ?></td>
                <td><?= htmlspecialchars($order['waktu_pemesanan']); ?></td>
                <td><?= htmlspecialchars(implode(", ", $produk)); ?></td>
                <td><?= htmlspecialchars(implode(", ", $jumlah)); ?></td>
                <td><?= "Rp. " . number_format($total_price, 2, ',', '.'); ?></td>
                <td><?= htmlspecialchars($order['status']); ?></td>
            </tr>
            <?php endwhile;
            } else {
                echo "<tr><td colspan='10'>Tidak ada data untuk Pesanan Selesai.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</section>

<script>
// JavaScript untuk mengelola tab aktif
document.querySelectorAll('.tab-link').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.tab-link').forEach(tabLink => {
            tabLink.classList.remove('active');
        });
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });
        tab.classList.add('active');
        document.getElementById(tab.dataset.tab).classList.add('active');
    });
});
</script>
</body>
</html>

<?php
// Tutup koneksi
$conn->close();
?>