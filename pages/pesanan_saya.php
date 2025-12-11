<?php
session_start();

// Cek role user
if ($_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['user_id'];

// Koneksi database menggunakan PDO
$host = '127.0.0.1';
$dbname = 'warungbutitin';
$username = 'root'; 
$password = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

$statuses = ['Pesanan Baru', 'Pesanan Diproses', 'Pesanan Dikirim', 'Pesanan Sampai'];
$orders = [];

foreach ($statuses as $status) {
    $stmt = $pdo->prepare("
    SELECT o.id AS order_id, 
           GROUP_CONCAT(oi.product_id) AS product_ids, 
           GROUP_CONCAT(oi.jumlah) AS jumlahs,
           GROUP_CONCAT(oi.harga) AS harga,
           GROUP_CONCAT(p.image) AS gambar_produk, 
           GROUP_CONCAT(p.name) AS nama_produk, 
           DATE_FORMAT(o.waktu_pemesanan, '%Y-%m-%d %H:%i:%s') AS waktu_pemesanan, 
           o.status, 
           SUM(oi.harga * oi.jumlah) AS total_harga
    FROM orders o
    LEFT JOIN order_items oi ON o.id = oi.order_id
    LEFT JOIN products p ON oi.product_id = p.id
    WHERE o.user_id = :user_id AND o.status = :status
    GROUP BY o.id, o.status, o.waktu_pemesanan
    ORDER BY o.waktu_pemesanan DESC
    ");
    $stmt->execute(['user_id' => $userID, 'status' => $status]);
    $orders[$status] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_POST['update_status']) && isset($_POST['order_id'])) {
    // Mendapatkan order_id dari form
    $order_id = $_POST['order_id'];

    // Query untuk memperbarui status pesanan dan processed_by
    $query = "UPDATE orders 
              SET status = 'Pesanan Sampai', processed_by = 'User'
              WHERE id = :order_id
              AND user_id = :user_id
              AND status = 'Pesanan Dikirim'";

    // Persiapkan query dan bind parameter
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $userID, PDO::PARAM_INT);

    // Eksekusi query
    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
            echo "Status pesanan berhasil diperbarui menjadi 'Pesanan Sampai'.";
        } else {
            echo "Tidak ada perubahan status. Pastikan pesanan Anda sudah dalam status 'Pesanan Dikirim'.";
        }
    } else {
        echo "Gagal mempersiapkan query.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya</title>
    <?php include_once "../includes/header.php"; ?>
    <style>
         @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@1,800&family=Poppins:ital,wght@0,300;0,400;0,700;0,800;1,200;1,300&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Titan+One&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Lemon&display=swap');

    @font-face {
      font-family: 'PublicaSans-Medium';
      src: url('../assets/PublicaSans-Medium.woff') format('woff');
      font-weight: normal;
      font-style: normal;
    }
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            margin-top: 100px;
            padding: 0;
            padding-top: 10px;
        }
        .container {
            width: 90%;
            margin: 120px auto;
            background: #f9f9f9;
            border-radius: 8px;
            padding-top: 10px;
        }
        h1, h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .tabs {
    margin-bottom: 0px;
}

.tab-list {
    list-style: none;
    display: flex;
    justify-content: center;
    padding: 0;
    margin-bottom: 30px;
}

.tab-list a {
    text-decoration: none;
    padding: 13px 83px; /* Memperbesar padding kiri dan kanan */
    background: #fff;
    color: #171717;
    transition: background 0.3s;
    margin: 0;
    border-bottom: 3px solid #C9C9C9;
}

        .tab-list a:hover {
            color: #D20000;
            border-bottom: 3px solid #D20000;
        }
        .tab-list a.active {
            color: #D20000;
            border-bottom: 3px solid #D20000;
        }
        .tab-content .tab-pane {
            display: none;
            margin-top: 20px;
        }
        .tab-content .tab-pane.active {
            display: block;
        }
        .order-list {
            list-style: none;
            padding: 0;
        }
        .order-list li {
            background: #fff;
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 5px;
            padding: 20px 30px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }
        .order-item {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 100%;
}

.status {
    text-align: right; /* Letakkan status di bagian kanan */
    margin-bottom: 22px;
    color: #F26D21; /* Warna orange untuk teks status */
    font-size: 16px; 
}

.status strong {
    color: #000; /* Warna teks "Status:" tetap hitam */
}


.product-item {
    display: flex;
    align-items: center;
    gap: 10px; /* Jarak antar elemen */
}

.order-image {
    width: 80px; /* Perbesar ukuran gambar */
    height: 80px;
    object-fit: cover; /* Menjaga proporsi gambar */
    border: 1px solid #ccc; /* Tambahkan border opsional */
}

.product-details {
    display: flex;
    flex-grow: 1;
    justify-content: space-between;
}

.product-name {
    flex: 2;
    line-height: 1.8;
    position: relative;
    top: -10px
}

.product-name strong {
    font-size: 18px; /* Ukuran teks lebih besar */
}


.product-price {
    text-align: right; /* Harga di kanan sekali */
    white-space: nowrap; /* Mencegah harga terpotong */
    margin-top: 20px;
}

.order-summary {
    display: flex; /* Menyusun elemen secara horizontal */
    justify-content: space-between; /* Memisahkan elemen kiri dan kanan */
    align-items: center; /* Menjaga elemen sejajar vertikal */
}

.order-summary .order-total {
    font-size: 22px; /* Ukuran font lebih besar untuk total harga */
    color: #F26D21; /* Warna orange pada harga */
}

.order-summary .order-total strong {
    font-size: 16px; /* Ukuran font yang sedikit lebih kecil untuk "Total Harga:" */
    color: #000; /* Warna teks "Total Harga:" tetap hitam */
}

.order-summary .order-total span {
    font-size: 20px; /* Ukuran font lebih besar untuk angka harga */
    font-weight: bold; /* Membuat angka harga lebih tebal */
}


.order-date {
    text-align: left; /* Tanggal di kiri */
    flex: 1; /* Memberikan ruang untuk tanggal di sisi kiri */
    margin-left: 90px;
}

.order-total {
    text-align: right; /* Total harga di kanan */
}

.wa-container {
    display: flex; /* Flexbox untuk teks dan tombol */
    justify-content: space-between; /* Memisahkan teks dan tombol */
    align-items: center; /* Menjaga elemen sejajar vertikal */
    margin-top: 10px; /* Menambahkan jarak atas antara order summary dan wa-container */
}

.wa-text {
    text-align: left; /* Teks di kiri */
    flex: 1; /* Menggunakan ruang kiri */
    font-size: 13px;
    color: #414141;
}

a button {
    padding: 10px 20px; /* Menambahkan padding atas/bawah dan kiri/kanan */
    font-size: 16px; /* Menyesuaikan ukuran font agar lebih terlihat jelas */
    border: 1.5px solid #25d366; /* Menambahkan border hijau sesuai warna WhatsApp */
    background-color: #fff; /* Latar belakang putih */
    color: #25d366; /* Warna teks hijau sesuai dengan warna WhatsApp */
    cursor: pointer; /* Menampilkan pointer saat hover */
    border-radius: 3px; /* Membuat sudut tombol melengkung */
    transition: background-color 0.3s ease, color 0.3s ease; /* Efek transisi saat hover */
}

a button:hover {
    background-color: #25d366; /* Warna latar belakang hijau saat hover */
    color: white; /* Ubah teks menjadi putih saat hover */
}



hr {
    border: 1px solid #ccc;
    margin: 10px 0;
    width: 100%;
}


        .container h1 {
            font-family: 'PublicaSans-Medium', sans-serif;
    font-size: 35px;
}

.word1 {
        font-size: 35px;
        color: #1B1817; 
}

.word2 {
    color: #F26421;
}

.no-orders-message {
    margin-left: 15px; /* Geser teks ke kanan */
    font-size: 16px;   /* Ukuran font bisa disesuaikan */
    color: #333;       /* Warna teks bisa disesuaikan */
}

/* Styling button Pesanan Diterima */
.wa-container .btn-accept {
    padding: 10px 20px;
    font-size: 16px;
    border: 1.5px solid #F26D21; /* Border orange */
    background-color: #F26D21; /* Background orange */
    color: #fff; /* Text white */
    cursor: pointer;
    border-radius: 3px; /* Rounded corners */
    margin-right: 10px; /* Spacing between buttons */
    text-decoration: none; /* Remove underline from links */
    transition: background-color 0.3s ease, color 0.3s ease;
}

.wa-container .btn-accept:hover {
    background-color: #CA5716; /* White background on hover */
    color: #f9f9f9; /* Orange text on hover */
    border: 1.5px solid #CA5716;
}

/* Styling untuk tombol Nilai Pesanan */
.btn-rate {
    background-color: white; /* Warna latar belakang putih */
    color: #333; /* Warna teks abu-abu gelap */
    font-size: 16px; /* Ukuran font */
    padding: 10px 20px; /* Jarak dalam tombol */
    border: 2px solid #ccc; /* Border abu-abu muda */
    border-radius: 5px; /* Sudut melengkung */
    cursor: pointer; /* Menunjukkan pointer saat hover */
    transition: all 0.3s ease; /* Transisi halus untuk semua efek */
    text-align: center; /* Mengatur posisi teks */
    display: inline-block; /* Membuat tombol sejajar dengan elemen lain */
}

/* Efek hover untuk tombol */
.btn-rate:hover {
    background-color: #f1f1f1; /* Latar belakang sedikit lebih gelap saat hover */
    border-color: #999; /* Border menjadi lebih gelap saat hover */
    color: #333;
}

/* Efek saat tombol ditekan */
.btn-rate:active {
    background-color: #e0e0e0; /* Latar belakang lebih gelap saat ditekan */
    border-color: #666; /* Border lebih gelap saat ditekan */
    color: #333;
}

/* Menambahkan efek disabled pada tombol */
.btn-rate:disabled {
    background-color: #f9f9f9; /* Latar belakang lebih terang untuk tombol non-aktif */
    border-color: #ddd; /* Border lebih terang */
    cursor: not-allowed; /* Menampilkan cursor not-allowed */
}

    </style>
</head>
<body>
    <div class="container">
    <h1>
        <span class="word1">Pesanan</span> 
        <span class="word2">Saya</span>
    </h1>
        <div class="tabs">
            <ul class="tab-list">
                <?php foreach ($statuses as $status): ?>
                    <li><a href="#tab-<?= str_replace(' ', '-', strtolower($status)) ?>" class="<?= $status === $statuses[0] ? 'active' : '' ?>"><?= $status ?></a></li>
                <?php endforeach; ?>
            </ul>
            
            <div class="tab-content">
                <?php foreach ($statuses as $status): ?>
                    <div id="tab-<?= str_replace(' ', '-', strtolower($status)) ?>" class="tab-pane <?= $status === $statuses[0] ? 'active' : '' ?>">
                        <?php if (!empty($orders[$status])): ?>
                            <ul class="order-list">
    <?php foreach ($orders[$status] as $order): ?>
        <li>
            <div>
                <?php
                    $productIds = explode(',', $order['product_ids']);
                    $jumlahs = explode(',', $order['jumlahs']);
                    $hargas = explode(',', $order['harga']);
                    $gambarProduks = explode(',', $order['gambar_produk']);
                    $namaProduks = explode(',', $order['nama_produk']);
                    $totalHarga = number_format($order['total_harga'], 0, ',', '.');
                ?>

                <!-- Membuat satu card per order_id -->
                <div class="order-item">
    <div class="status">
        <strong>Status:</strong> <?= $order['status'] ?><br>
    </div>

    <?php foreach ($productIds as $index => $productId): ?>
        <div class="product-item">
            <img src="../assets/images/<?= $gambarProduks[$index] ?>" alt="Produk" class="order-image">
            <div class="product-details">
                <div class="product-name">
                <strong><?= $namaProduks[$index] ?></strong><br>
                    <?= "x{$jumlahs[$index]}" ?><br>
                </div>
                <div class="product-price">
                    <?= "Rp " . number_format($hargas[$index], 0, ',', '.') ?><br>
                </div>
            </div>
        </div>
        <hr>
    <?php endforeach; ?>

    <div class="order-summary">
    <span class="order-date">
        <?= date('d-m-Y', strtotime($order['waktu_pemesanan'])) ?>
    </span>
    <span class="order-total">
        <strong>Total Harga:</strong> Rp <?= $totalHarga ?>
    </span>
</div>

<div class="wa-container">
    <span class="wa-text">
        <?php if ($order['status'] === 'Pesanan Diproses'): ?>
            Pesanan sedang diproses, mohon ditunggu.
        <?php elseif ($order['status'] === 'Pesanan Dikirim'): ?>
            Jika pesanan sudah sampai, tolong klik 'Pesanan Diterima'.
        <?php elseif ($order['status'] === 'Pesanan Sampai'): ?>
            Silahkan beri nilai untuk produk kami.
        <?php else: ?>
            Silahkan chat WhatsApp jika pesanan belum diproses.
        <?php endif; ?>
    </span>

    <?php if ($order['status'] === 'Pesanan Dikirim'): ?>
        <!-- Form untuk mengubah status pesanan menjadi "Pesanan Sampai" -->
        <form method="POST">
            <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
            <button type="submit" name="update_status" class="btn-accept">Pesanan Diterima</button>
        </form>
    <?php endif; ?>

    <?php if ($order['status'] === 'Pesanan Sampai'): ?>
        <!-- Tombol Nilai Pesanan jika statusnya sudah sampai -->
        <a href="rate_product.php?order_id=<?= $order['order_id'] ?>">
    <button class="btn-rate">Nilai Pesanan</button>
</a>
    <?php else: ?>
        <!-- Tombol WhatsApp hanya jika status bukan 'Pesanan Sampai' -->
        <a href="https://wa.me/6285158925502?text=<?= urlencode("Apakah pesanan saya sudah diproses? Pesanan ID: {$order['order_id']}") ?>" target="_blank">
            <button>Chat WhatsApp</button>
        </a>
    <?php endif; ?>
</div>


</div>

        </li>
    <?php endforeach; ?>
</ul>

<?php else: ?>
    <p class="no-orders-message">Tidak ada pesanan di status ini.</p>
<?php endif; ?>

                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tabs = document.querySelectorAll('.tab-list a');
            const panes = document.querySelectorAll('.tab-pane');

            tabs.forEach(tab => {
                tab.addEventListener('click', (e) => {
                    e.preventDefault();

                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');

                    panes.forEach(pane => pane.classList.remove('active'));
                    const target = document.querySelector(tab.getAttribute('href'));
                    target.classList.add('active');
                });
            });
        });
    </script>
</body>
</html>

<?php
include_once "../includes/footer.php";
?>