<?php
session_start();

// Fungsi logout
if (isset($_POST['logout'])) {
    // Hapus data sesi
    session_unset();
    session_destroy();

    // Hapus cookie sesi (jika digunakan)
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Arahkan ke halaman login
    header("Location: ../login.php");
    exit;
}

// Fungsi untuk menampilkan daftar produk dari basis data
function tampilkanProduk()
{
    $koneksi = new mysqli("localhost", "root", "", "warungbutitin");

    if ($koneksi->connect_error) {
        die("Koneksi gagal: " . $koneksi->connect_error);
    }

    $query = "SELECT p.*, c.name AS category_name FROM Products p
              JOIN Categories c ON p.category_id = c.id";
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


// Fungsi untuk menghapus produk berdasarkan ID
function hapusProduk($id)
{
    $koneksi = new mysqli("localhost", "root", "", "warungbutitin");

    if ($koneksi->connect_error) {
        die("Koneksi gagal: " . $koneksi->connect_error);
    }

    $query = "DELETE FROM Products WHERE id = $id"; // Sesuaikan dengan struktur tabel dan nama kolom

    if ($koneksi->query($query) === TRUE) {
        // Jika penghapusan berhasil, arahkan kembali ke halaman admin.php
        header("Location: admin.php");
        exit;
    } else {
        echo "Error: " . $query . "<br>" . $koneksi->error;
    }

    $koneksi->close();
}

// Proses penghapusan produk jika parameter delete_id ditemukan
if (isset($_GET['delete_id'])) {
    $id_to_delete = $_GET['delete_id'];
    hapusProduk($id_to_delete);
}

// Tampilkan produk yang sudah ditambahkan
$produk = tampilkanProduk();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <style>
        @font-face {
  font-family: 'PublicaSans-Medium'; /* Nama yang Anda inginkan untuk font ini */
  src: url('../assets/PublicaSans-Medium.woff') format('woff'); /* Lokasi file font WOFF */
  /* Opsional: Anda dapat menambahkan varian font seperti bold atau italic jika diperlukan */
  font-weight: normal;
  font-style: normal;

  font-family: 'PublicaSans-Light'; /* Nama yang Anda inginkan untuk font ini */
  src: url('../assets/PublicaSans-Light.woff') format('woff'); /* Lokasi file font WOFF */
  /* Opsional: Anda dapat menambahkan varian font seperti bold atau italic jika diperlukan */
  font-weight: 500;
  font-style: normal;

}

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
            margin-left: 239px;
    background-color: #F26D21;
    color: #fff;
    padding-top: 10px;
    padding-block:  10px;
    padding-left: 35px;
    text-align: left;
    height: 40px;
    width: 995px;
    display: flex;
    align-items: center;
}

header svg {
    margin-left: 75%; /* Membuat SVG rata kanan */
}


        section.admin {
            width: 71%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin-left: 280px;
            background-color: white;
            padding-left: 30px;
            padding-right: 30px;
            padding-bottom: 30px;
            margin-top: 30px;
            padding-top: 6px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            flex: 1;
        }

        .admin h3 {
            color: #333;
            margin-bottom: 15px;
        }

        .admin-products {
            margin-top: 0px;
            width: 100%;
        }

        .admin-products ul {
            list-style: none;
            padding: 0;
        }

        .admin-products ul li {
            margin-bottom: 10px;
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
        fill: #BABABA;
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


        h4 {
            color: #333;
            margin-top: 20px;
        }

        table {
            margin-top: 10px;
            width: 100%;
            border-collapse: collapse;
        }

        th.product-name,
        td.product-name,
        th.category,
        td.category,
        th.price,
        td.price,
        th.image,
        td.image,
        th.actions,
        td.actions {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #F26D21;;
            color: white;
        }

        tbody tr:hover {
            background-color: #f5f5f5;
        }
        /* Gaya untuk kolom Nama Produk */
th.product-name,
td.product-name {
    width: 35%;
}

/* Gaya untuk kolom Kategori */
th.category,
td.category {
    width: 20%;
}

/* Gaya untuk kolom Harga */
th.price,
td.price {
    /* Atur lebar sesuai kebutuhan */
    width: 15%;
}

/* Gaya untuk kolom Gambar */
th.image,
td.image {
    /* Atur lebar sesuai kebutuhan */
    width: 10%;
}

/* Gaya untuk kolom Aksi */
th.actions,
td.actions {
    /* Atur lebar sesuai kebutuhan */
    width: 10%;
}


        /* Gaya umum untuk tombol */
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

/* Gaya untuk tombol di dalam aside */
aside button {
    margin-left: 30px;
    margin-top: 325px; 
}

.action-links {
    display: flex;
    align-items: center;
    flex-direction: column; /* Menata elemen secara vertikal */
}

.action-link {
    margin-left: 10px;
    margin-right: 10px;
    display: flex;
    align-items: center;
    text-decoration: none;
    margin-bottom: 10px; /* Memberikan margin bawah untuk memberi jarak antar elemen */
}

.edit-link,
.delete-link {
    width: 100px;
    height: 30px;
    border-radius: 6px;
    transition: background-color 0.3s ease;
}

.edit-link {
    margin-top: 5px;
    background-color: #28a745;
    color: #fff;
    border: 2px solid #4AB964;
}

.edit-link:hover {
    background-color: #218838;
}

.delete-link {
    background-color: white;
    border: 2px solid #E80021;
    color: #E80021;
}

.delete-link:hover {
    background-color:#E80021;
    color: white;
}

.edit-link svg,
.delete-link svg {
    width: 18px; /* Sesuaikan dengan ukuran yang Anda inginkan */
    height: 18px;
    margin-right: 5px; /* Tambahkan margin jika diperlukan */
    margin-left: 12px;
}

.delete-link:hover svg {
    fill: white; /* Ganti dengan warna yang diinginkan saat tombol dihover */
}


        .admin-title {
            width: 196px;
  display: flex;
  align-items: center;
  margin-bottom:0px;
  margin-top: 0px;
  margin-left: 20px;
  border-bottom: 4px solid #716E6E;
}

.admin-title img {
  height: 35px; /* Sesuaikan dengan ukuran yang diinginkan */
  margin-right: 5px; /* Sesuaikan margin kanan sesuai kebutuhan */
  margin-top: 10px;
  margin-left: 25px;
  margin-bottom:12px;
}
    </style>
</head>

<body>

    <!-- Sidebar -->
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

        <!-- Header -->
        <header>
            <h2>Admin Dashboard</h2>
            <svg fill="#ffffff" height="30px" width="30px" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-4.08 -4.08 32.16 32.16" enable-background="new 0 0 24 24" xml:space="preserve" stroke="#ffffff" stroke-width="0.768">
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
        <g id="SVGRepo_iconCarrier">
            <g id="user-admin">
                <path d="M22.3,16.7l1.4-1.4L20,11.6l-5.8,5.8c-0.5-0.3-1.1-0.4-1.7-0.4C10.6,17,9,18.6,9,20.5s1.6,3.5,3.5,3.5s3.5-1.6,3.5-3.5 c0-0.6-0.2-1.2-0.4-1.7l1.9-1.9l2.3,2.3l1.4-1.4l-2.3-2.3l1.1-1.1L22.3,16.7z M12.5,22c-0.8,0-1.5-0.7-1.5-1.5s0.7-1.5,1.5-1.5 s1.5,0.7,1.5,1.5S13.3,22,12.5,22z"></path>
                <path d="M2,19c0-3.9,3.1-7,7-7c2,0,3.9,0.9,5.3,2.4l1.5-1.3c-0.9-1-1.9-1.8-3.1-2.3C14.1,9.7,15,7.9,15,6c0-3.3-2.7-6-6-6 S3,2.7,3,6c0,1.9,0.9,3.7,2.4,4.8C2.2,12.2,0,15.3,0,19v5h8v-2H2V19z M5,6c0-2.2,1.8-4,4-4s4,1.8,4,4s-1.8,4-4,4S5,8.2,5,6z"></path>
            </g>
        </g>
    </svg>
        </header>

        <!-- Admin Page Content -->
        <section class="admin">
            <div class="admin-products">
                <ul>
                </ul>
                <h4>Daftar Produk</h4>
                <table>
                    <thead>
                        <tr>
                            <th class="product-name">Nama Produk</th>
                            <th class="category">Kategori</th>
                            <th class="price">Harga</th>
                            <th class="image">Gambar</th>
                            <th class="actions">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produk as $item) : ?>
                            <tr>
                                <td class="product-name"><?php echo $item['name']; ?></td>
                                <td class="category"><?php echo $item['category_name']; ?></td>
                                <td class="price"><?php echo number_format($item['price'], 2); ?></td>
                                <td class="image"><img src="../../assets/images/<?php echo $item['image']; ?>"
                                        alt="Product Image" style="width: 70px;"></td>
                                <td class="actions">
                                <div class="action-links">
                                    <a href="../admin/edit_product.php?id=<?php echo $item['id']; ?>" class="action-link edit-link">
                                        <svg fill="#FFFFFF" width="18px" height="18px"  viewBox="0 0 24.00 24.00" xmlns="http://www.w3.org/2000/svg">
                                            <g id="SVGRepo_iconCarrier">
                                                <path d="M20.7,5.2a1.024,1.024,0,0,1,0,1.448L18.074,9.276l-3.35-3.35L17.35,3.3a1.024,1.024,0,0,1,1.448,0Zm-4.166,5.614-3.35-3.35L4.675,15.975,3,21l5.025-1.675Z"></path>
                                            </g>
                                        </svg>
                                        <span>Ubah</span>
                                    </a>
                                    <a href="?delete_id=<?php echo $item['id']; ?>" class="action-link delete-link">
                                        <svg fill="#f50000" viewBox="0 0 256 256" id="Flat" xmlns="http://www.w3.org/2000/svg" width="18px" height="18px">
                                            <g id="SVGRepo_iconCarrier">
                                                <path d="M215.99609,48H180V36A28.03146,28.03146,0,0,0,152,8H104A28.03146,28.03146,0,0,0,76,36V48H39.99609a12,12,0,0,0,0,24h4V208a20.0226,20.0226,0,0,0,20,20h128a20.0226,20.0226,0,0,0,20-20V72h4a12,12,0,0,0,0-24ZM100,36a4.00458,4.00458,0,0,1,4-4h48a4.00458,4.00458,0,0,1,4,4V48H100Zm87.99609,168h-120V72h120ZM116,104v64a12,12,0,0,1-24,0V104a12,12,0,0,1,24,0Zm48,0v64a12,12,0,0,1-24,0V104a12,12,0,0,1,24,0Z"></path>
                                            </g>
                                        </svg>
                                        <span>Hapus</span>
                                    </a>
                                </div>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
    </section>

    <!-- Footer -->
    <footer>
        <!-- Your Footer Content -->
    </footer>

</body>

</html>
