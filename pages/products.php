<?php
session_start();
if ($_SESSION['role'] != 'user') {
    // Jika bukan user, arahkan ke halaman error atau halaman login
    header("Location: login.php");
    exit();
}
// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika tidak terlogin, arahkan ke halaman login
    header("Location: login.php");
    exit();
}

// ID user yang terlogin
$userID = $_SESSION['user_id'];
// Fungsi untuk menampilkan produk berdasarkan kategori dan kata kunci pencarian
function tampilkanProdukByCategory($category, $searchKeyword = '')
{
    $koneksi = new mysqli("localhost", "root", "", "warungbutitin");

    if ($koneksi->connect_error) {
        die("Koneksi gagal: " . $koneksi->connect_error);
    }

    // Query untuk mencari produk berdasarkan kategori dan kata kunci pencarian
    $query = "SELECT * FROM Products WHERE category_id = '$category' AND name LIKE '%$searchKeyword%'";
    $result = $koneksi->query($query);

    $produk = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $produk[] = $row;
        }
    }

    $koneksi->close();

    return $produk;
}

// Menangani input pencarian jika ada
$searchKeyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

// Tampilkan produk berdasarkan kategori Makanan dan pencarian
$produkMakanan = tampilkanProdukByCategory(1, $searchKeyword); // 1 adalah ID kategori Makanan

// Tampilkan produk berdasarkan kategori Minuman dan pencarian
$produkMinuman = tampilkanProdukByCategory(2, $searchKeyword); // 2 adalah ID kategori Minuman
?>

<!DOCTYPE html>
<html>
<title>Produk BuTITIN</title>
<head>
  <link rel="stylesheet" type="text/css" href="../assets/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<?php
include_once "../includes/header.php";
?>
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

    .produk {
        margin: 50px;
        margin-top: 150px;
    }

    .daftar-produk {
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
    }

    .produk-item {
        width: 300px;
        border-radius: 15px;
        padding: 16px;
        margin-left: 16px;
        margin-top: 16px;
        margin-right: 16px;
        margin-bottom: 25px;
        transition: transform 0.3s ease-in-out;
        display: inline-block;
        border: 5px solid white;
        vertical-align: top; 
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
    }

    .produk-item:hover {
        transform: scale(1.05);
    }

    .produk-item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        margin-bottom: 12px;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        border-bottom-left-radius: 70px;
    }

    .produk-item h5 {
        font-family: 'PublicaSans-Medium', sans-serif;
        margin-bottom: 5px;
        margin-top: 0;
        font-size: 18px;
        color: #333;
    }

    .produk-item p {
        font-family: 'PublicaSans-Light', sans-serif;
        margin-bottom: 12px;
        color: #666;
    }

    .orange-button {
        display: flex; 
        justify-content: center;
        align-items: center; 
        padding-top: 5px;
        padding-bottom: 6px;
        font-size: 17px;
        font-weight: bold;
        text-align: center;
        text-decoration: none;
        background-color: #ffffff;
        color:  #F26421;
        border: 2px solid  #F26421;
        border-radius: 10px;
        transition: background-color 0.3s ease-in-out;
        width: 295px;
    }

    .orange-button:hover {
        background-color: #F26421;
        color: #ffffff;
    }

    .orange-button svg {
        margin-top: 4px;
        margin-bottom: 4px;
        width: 23px; 
        height: 23px;
        fill:  #F26421;
        margin-left: 8px;
    }

    .produk h3 {
        margin-bottom: 0px;
        font-family: 'PublicaSans-Medium', sans-serif;
        font-size: 35px;
        margin-left: 41%;
        color: #1B1817;
    }

    .produk .daftar-highlight {
        color: #F26421;
    }

    .kategori-makanan h4 {
        font-family: 'PublicaSans-Medium', sans-serif;
        margin-bottom: 10px;
        font-size: 25px;
        color: #1B1817;
        margin-left: 45%;
    }

    .kategori-minuman h4 {
        font-family: 'PublicaSans-Medium', sans-serif;
        margin-bottom: 10px;
        font-size: 25px;
        color: #1B1817;
        margin-left: 45%;
    }

    .kategori .daftar-produk h5 {
        margin-bottom: 5px;
        color: #1B1817;
    }

    .produk-item p {
    font-family: 'PublicaSans-Light', sans-serif;
    margin-bottom: 12px;
    color: #666;
    overflow: hidden; /* Mengatasi overflow */
    white-space: nowrap; /* Mencegah wrapping teks */
    text-overflow: ellipsis; /* Menampilkan ellipsis (...) jika teks terlalu panjang */
}

</style>

<section class="produk">
    <h3>Daftar <span class="daftar-highlight">Produk</span></h3>

    <!-- Kategori Makanan -->
    <?php if (count($produkMakanan) > 0 || ($searchKeyword && !empty($produkMakanan))) : ?>
        <div class="kategori-makanan">
            <h4>Makanan</h4>
            <div class="daftar-produk">
                <?php foreach ($produkMakanan as $item) : ?>
                    <div class="produk-item">
                        <img src="../assets/images/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                        <h5><?php echo $item['name']; ?></h5>
                        <p>Harga: Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></p>
                        <p><?php echo $item['description']; ?></p>
                        <a href="product_detail.php?product_id=<?php echo $item['id']; ?>" class="orange-button">
                            Detail Produk
                            <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11 16L15 12M15 12L11 8M15 12H3M4.51555 17C6.13007 19.412 8.87958 21 12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C8.87958 3 6.13007 4.58803 4.51555 7" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
    <?php endif; ?>

    <!-- Kategori Minuman -->
    <?php if (count($produkMinuman) > 0 || ($searchKeyword && !empty($produkMinuman))) : ?>
        <div class="kategori-minuman">
            <h4>Minuman</h4>
            <div class="daftar-produk">
                <?php foreach ($produkMinuman as $item) : ?>
                    <div class="produk-item">
                        <img src="../assets/images/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                        <h5><?php echo $item['name']; ?></h5>
                        <p>Harga: Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></p>
                        <p><?php echo $item['description']; ?></p>
                        <a href="product_detail.php?product_id=<?php echo $item['id']; ?>" class="orange-button">
                            Detail Produk
                            <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11 16L15 12M15 12L11 8M15 12H3M4.51555 17C6.13007 19.412 8.87958 21 12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C8.87958 3 6.13007 4.58803 4.51555 7" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
    <?php endif; ?>
</section>

<?php
include_once "../includes/footer.php";
?>
