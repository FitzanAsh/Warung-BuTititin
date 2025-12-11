<?php
session_start();

if (!isset($_SESSION['role']) || !isset($_SESSION['user_id'])) {
    // Jika sesi tidak valid, arahkan ke halaman login
    header("Location: login.php");
    exit();
}

// Periksa role
if ($_SESSION['role'] !== 'user') {
    // Jika role bukan 'user', arahkan ke halaman error
    header("Location: error.php");
    exit();
}

// Ambil user ID dari sesi
$userID = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            background-color: #f8f8f8;
            color: #333;
            margin-top: 100px;
            font-family: 'Poppins', sans-serif;
        }

        /* Container utama produk */
        .detail-produk {
            max-width: 1000px;
            margin: 150px auto;
            padding: 35px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .detail-produk h2 {
            margin-top: 0px;
            font-family: 'PublicaSans-Medium', sans-serif;
            font-size: 33px;
            text-align: center;
            margin-bottom: 20px;
            color: #1B1817;
        }

        .detail-produk .detail-highlight {
            color: #F26421;
        }

        /* Bagian produk */
        .produk-item {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            text-align: left;
        }

        .produk-item img {
            width: 300px;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 20px;
            margin-top: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .produk-item h3 {
            font-family: 'PublicaSans-Medium', sans-serif;
            font-size: 24px;
            color: #1B1817;
            margin-bottom: 5px;
        }

        .produk-item p {
            font-family: 'PublicaSans-Light', sans-serif;
            font-size: 16px;
            color: #666;
            margin-bottom: 10px;
        }

        /* Input jumlah */
        .quantity-section {
            margin-bottom: 20px;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }

        .quantity-button {
            background-color: #F26D21;
            color: white;
            border: none;
            padding: 10px;
            font-size: 20px;
            cursor: pointer;
            border-radius: 5px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-button:hover {
            background-color: #D9631E;
        }

        .quantity-input {
            width: 50px;
            text-align: center;
            font-size: 18px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Tombol aksi */
        .button-container {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }

        .orange-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            background-color: #F26D21;
            color: #fff;
            border-radius: 7px;
            transition: background-color 0.3s ease-in-out;
            cursor: pointer;
            border: none;
        }

        .orange-button:hover {
            background-color: #D9631E;
        }

        .orange-button svg {
            margin-left: 5px;
            width: 18px;
            height: 18px;
        }
    </style>
</head>
<body>

<?php
include_once "../includes/header.php";

// Simulasi user ID dari sesi pengguna yang login
// Ganti dengan logika autentikasi yang sebenarnya
$userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

// Fungsi untuk menampilkan detail produk
function tampilkanDetailProduk($productID)
{
    $koneksi = new mysqli("localhost", "root", "", "warungbutitin");

    if ($koneksi->connect_error) {
        die("Koneksi gagal: " . $koneksi->connect_error);
    }

    $query = $koneksi->prepare("SELECT * FROM products WHERE id = ?");
    $query->bind_param("i", $productID);
    $query->execute();
    $result = $query->get_result();

    $produkDetail = null;
    if ($result->num_rows > 0) {
        $produkDetail = $result->fetch_assoc();
    }

    $query->close();
    $koneksi->close();

    return $produkDetail;
}

// Fungsi untuk menambahkan produk ke dalam keranjang
function tambahKeKeranjang($userID, $productID, $quantity)
{
    $koneksi = new mysqli("localhost", "root", "", "warungbutitin");

    if ($koneksi->connect_error) {
        die("Koneksi gagal: " . $koneksi->connect_error);
    }

    // Cek apakah produk sudah ada di keranjang
    $query = $koneksi->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $query->bind_param("ii", $userID, $productID);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        // Update jumlah jika produk sudah ada di keranjang
        $updateQuery = $koneksi->prepare("UPDATE cart SET jumlah = jumlah + ? WHERE user_id = ? AND product_id = ?");
        $updateQuery->bind_param("iii", $quantity, $userID, $productID);
        $updateQuery->execute();
        $updateQuery->close();
    } else {
        // Masukkan produk baru ke keranjang
        $insertQuery = $koneksi->prepare("INSERT INTO cart (user_id, product_id, jumlah) VALUES (?, ?, ?)");
        $insertQuery->bind_param("iii", $userID, $productID, $quantity);
        $insertQuery->execute();
        $insertQuery->close();
    }

    $query->close();
    $koneksi->close();

    // Menampilkan popup dengan SweetAlert
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Produk berhasil ditambahkan ke keranjang!',
            showConfirmButton: true
        }).then(() => {
            window.location.href = 'products.php'; // Redirect ke halaman keranjang
        });
    </script>";
}

// Proses utama
if (isset($_GET['product_id'])) {
    $productID = intval($_GET['product_id']); // Pastikan input adalah integer
    $produkDetail = tampilkanDetailProduk($productID);

    if ($produkDetail) {
        ?>
        <section class="detail-produk">
            <h2>Detail <span class="detail-highlight">Produk</span></h2>
            <div class="produk-item">
                <img src="../assets/images/<?php echo htmlspecialchars($produkDetail['image']); ?>" alt="<?php echo htmlspecialchars($produkDetail['name']); ?>">
                <div>
                    <h3><?php echo htmlspecialchars($produkDetail['name']); ?></h3>
                    <p>Harga: Rp<?php echo number_format($produkDetail['price'], 0, ',', '.'); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($produkDetail['description'])); ?></p>

                    <!-- Input jumlah produk dengan tombol + dan - -->
                    <div class="quantity-section">
                        <label for="quantity">Jumlah:</label>
                        <div class="quantity-controls">
                            <button type="button" class="quantity-button" onclick="updateQuantity(-1)">-</button>
                            <input type="text" id="quantity" name="quantity" value="1" class="quantity-input" readonly>
                            <button type="button" class="quantity-button" onclick="updateQuantity(1)">+</button>
                        </div>
                    </div>

                    <!-- Tombol aksi -->
                    <div class="button-container">
                        <form action="" method="POST" class="form-inline">
                            <input type="hidden" name="product_id" value="<?php echo $produkDetail['id']; ?>">
                            <input type="hidden" id="quantity-input" name="quantity" value="1">
                            <button type="submit" name="add_to_cart" class="orange-button">Tambah Keranjang</button>
                        </form>
                        <a href="checkout.php?product_id=<?php echo $produkDetail['id']; ?>&quantity=1" class="orange-button">Pesan Sekarang</a>
                    </div>
                </div>
            </div>
        </section>

        <?php
        // Proses ketika tombol "Tambah Keranjang" ditekan
        if (isset($_POST['add_to_cart'])) {
            $quantity = intval($_POST['quantity']); // Validasi jumlah
            if ($quantity > 0) {
                tambahKeKeranjang($userID, intval($_POST['product_id']), $quantity);
            } else {
                echo "<p>Jumlah produk harus minimal 1.</p>";
            }
        }
    } else {
        echo "<p>Produk tidak ditemukan.</p>";
    }
} else {
    echo "<p>Invalid product ID.</p>";
}

include_once "../includes/footer.php";
?>


<script>
// Fungsi untuk menambah atau mengurangi jumlah
function updateQuantity(amount) {
    var quantityInput = document.getElementById("quantity");
    var currentQuantity = parseInt(quantityInput.value);

    // Pastikan jumlahnya tidak kurang dari 1
    if (currentQuantity + amount >= 1) {
        quantityInput.value = currentQuantity + amount;
        document.getElementById("quantity-input").value = currentQuantity + amount;
    }
}
</script>
</body>
</html>
