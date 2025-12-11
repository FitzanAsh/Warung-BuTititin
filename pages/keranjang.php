<?php 
session_start();
include_once "../includes/header.php";
 // Jika Anda memiliki header khusus

// Fungsi untuk mendapatkan data keranjang
function getKeranjang($userID)
{
    $koneksi = new mysqli("localhost", "root", "", "warungbutitin");

    if ($koneksi->connect_error) {
        die("Koneksi gagal: " . $koneksi->connect_error);
    }

    $query = $koneksi->prepare("
        SELECT c.id as cart_id, p.image, p.name, p.price, c.jumlah, p.id as product_id
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?
    ");
    $query->bind_param("i", $userID);
    $query->execute();
    $result = $query->get_result();
    $keranjang = $result->fetch_all(MYSQLI_ASSOC);

    $query->close();
    $koneksi->close();

    return $keranjang;
}

// User ID dari sesi (ganti dengan sistem autentikasi Anda)
$userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

// Proses pembaruan jumlah produk
if (isset($_POST['update_quantity'])) {
    $cartID = intval($_POST['cart_id']);
    $newQuantity = intval($_POST['new_quantity']);

    $koneksi = new mysqli("localhost", "root", "", "warungbutitin");
    if ($koneksi->connect_error) {
        die("Koneksi gagal: " . $koneksi->connect_error);
    }

    $query = $koneksi->prepare("UPDATE cart SET jumlah = ? WHERE id = ?");
    $query->bind_param("ii", $newQuantity, $cartID);
    $query->execute();
    $query->close();
    $koneksi->close();

    header("Location: keranjang.php");
    exit;
}

if (isset($_POST['hapus_produk'])) {
    $cartID = intval($_POST['cart_id']);

    $koneksi = new mysqli("localhost", "root", "", "warungbutitin");
    if ($koneksi->connect_error) {
        die("Koneksi gagal: " . $koneksi->connect_error);
    }

    $query = $koneksi->prepare("DELETE FROM cart WHERE id = ?");
    $query->bind_param("i", $cartID);
    $query->execute();
    $query->close();
    $koneksi->close();

    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
        header("Location: keranjang.php");
        exit;
    }
}


// Mendapatkan data keranjang
$keranjang = getKeranjang($userID);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Produk</title>
    <link rel="stylesheet" href="../assets/styles/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Fungsi untuk memperbarui jumlah di halaman
        function updateQuantity(button, cartID, isIncrement) {
            const quantityInput = button.parentNode.querySelector('.quantity-input');
            let currentQuantity = parseInt(quantityInput.value);

            // Perbarui jumlah berdasarkan tombol yang diklik
            if (isIncrement) {
                currentQuantity++;
            } else if (currentQuantity > 1) {
                currentQuantity--;
            }

            quantityInput.value = currentQuantity;

            // Kirim permintaan AJAX ke server
            const formData = new FormData();
            formData.append('update_quantity', true);
            formData.append('cart_id', cartID);
            formData.append('new_quantity', currentQuantity);

            fetch('keranjang.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(() => location.reload()) // Refresh halaman setelah berhasil
            .catch(error => console.error('Error:', error));
        }
    </script>
</head>
<body>
    <section class="keranjang-container">
        <h2>Keranjang <span class="keranjang-highlight">Produk</span></h2>
        <table class="keranjang-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalHarga = 0;
                $totalProduk = 0;

                if (!empty($keranjang)) {
                    foreach ($keranjang as $item) {
                        $subTotal = $item['price'] * $item['jumlah'];
                        $totalHarga += $subTotal;
                        $totalProduk += $item['jumlah'];
                        ?>
                        <tr>
                            <td>
                                <img src="../assets/images/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="produk-image">
                                <?php echo htmlspecialchars($item['name']); ?>
                            </td>
                            <td>Rp<?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                            <td>
                                <div class="quantity-controls">
                                    <button class="quantity-button" onclick="updateQuantity(this, <?php echo $item['cart_id']; ?>, false)">-</button>
                                    <input type="text" value="<?php echo $item['jumlah']; ?>" class="quantity-input" readonly>
                                    <button class="quantity-button" onclick="updateQuantity(this, <?php echo $item['cart_id']; ?>, true)">+</button>
                                </div>
                            </td>
                            <td>Rp<?php echo number_format($subTotal, 0, ',', '.'); ?></td>
                            <td>
                            <form onsubmit="hapusProduk(event, <?php echo $item['cart_id']; ?>)" class="hapus-form">
    <button type="submit" class="hapus-button">Hapus <i class="fa fa-trash"></i></button>
</form>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='5'>Keranjang Anda kosong.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="keranjang-footer">
            <p>Total (<?php echo $totalProduk; ?> produk): <span>Rp<?php echo number_format($totalHarga, 0, ',', '.'); ?></span></p>
            <button class="orange-button">Pesan</button>
        </div>
    </section>

<!-- Modal -->
<div id="modalPesan" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h3>Konfirmasi Pesanan</h3>
        <div class="modal-body">
            <h4>Produk yang dipesan:</h4>
            <ul id="product-list">
                <!-- Produk akan ditampilkan secara dinamis -->
            </ul>
            <h4>Detail Pengiriman</h4>
            <form id="formPesanan" method="POST">
                <label for="nama">Nama Lengkap:</label>
                <input type="text" id="nama" name="nama" required>
                
                <label for="telepon">Nomor Telepon:</label>
                <input type="text" id="telepon" name="telepon" required>
                
                <label for="alamat">Alamat Lengkap:</label>
                <textarea id="alamat" name="alamat" required></textarea>
                
                <h4>Total Harga: Rp<span id="total-harga-modal">0</span></h4>
                <button type="submit" class="orange-button">Pesan Sekarang</button>
            </form>
        </div>
    </div>
</div>

<script>
function hapusProduk(event, cartID) {
    event.preventDefault(); // Mencegah refresh halaman

    // Kirim permintaan ke server
    fetch('keranjang.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `hapus_produk=true&cart_id=${cartID}`
    })
    .then(response => response.text())
    .then(() => {
        // Hapus baris produk dari tabel tanpa reload
        const row = event.target.closest('tr');
        row.remove();

        // Perbarui total harga dan jumlah produk
        const hargaProduk = parseInt(row.querySelector('td:nth-child(4)').innerText.replace(/[^0-9]/g, ''));
        const totalHargaElement = document.querySelector('.keranjang-footer p span');
        const totalProdukElement = document.querySelector('.keranjang-footer p');

        let totalHarga = parseInt(totalHargaElement.innerText.replace(/[^0-9]/g, '')) - hargaProduk;
        let totalProduk = parseInt(totalProdukElement.textContent.match(/\d+/)[0]) - parseInt(row.querySelector('.quantity-input').value);

        totalHargaElement.innerText = `Rp${totalHarga.toLocaleString('id-ID')}`;
        totalProdukElement.innerHTML = `Total (${totalProduk} produk): <span>Rp${totalHarga.toLocaleString('id-ID')}</span>`;
    })
    .catch(error => console.error('Error:', error));
}
</script>

<script>
    const modal = document.getElementById("modalPesan");
    const closeModal = document.querySelector(".close-modal");
    const productList = document.getElementById("product-list");
    const totalHargaModal = document.getElementById("total-harga-modal");

    document.querySelector(".orange-button").addEventListener("click", () => {
    // Tampilkan modal
    modal.style.display = "block";

    // Ambil data produk dari keranjang
    const keranjang = <?php echo json_encode($keranjang); ?>;
    productList.innerHTML = "";
    let totalHarga = 0;

    if (keranjang.length === 0) {
        Swal.fire({
            title: 'Keranjang Anda kosong',
            text: 'Tidak ada produk untuk dipesan.',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }

    keranjang.forEach(item => {
        const listItem = document.createElement("li");
        listItem.textContent = `${item.name} x ${item.jumlah} = Rp${(item.price * item.jumlah).toLocaleString('id-ID')}`;
        productList.appendChild(listItem);
        totalHarga += item.price * item.jumlah;
    });

    totalHargaModal.textContent = totalHarga.toLocaleString('id-ID');
});

document.getElementById('formPesanan').addEventListener('submit', function(e) {
    e.preventDefault(); // Mencegah pengiriman form secara default

    var nama = document.getElementById('nama').value;
    var telepon = document.getElementById('telepon').value;
    var alamat = document.getElementById('alamat').value;

    if (!nama || !telepon || !alamat) {
        Swal.fire({
            title: 'Data tidak lengkap',
            text: 'Harap isi semua detail pengiriman.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        return;
    }

    var totalHarga = document.getElementById('total-harga-modal').innerText;

    // Kirim data pesanan ke server menggunakan AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'proses_pesanan.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Pesanan berhasil, tampilkan SweetAlert dan arahkan ke halaman pesanan_saya.php
            Swal.fire({
                title: 'Pesanan Berhasil!',
                text: 'Pesanan Anda telah dibuat.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'pesanan_saya.php'; // Redirect ke halaman pesanan_saya.php
            });
        } else {
            // Jika ada error dalam pemrosesan pesanan
            Swal.fire('Terjadi kesalahan', 'Pesanan Anda gagal diproses.', 'error');
        }
    };

    // Kirimkan data pesanan (nama, telepon, alamat, total harga)
    xhr.send('nama=' + nama + '&telepon=' + telepon + '&alamat=' + alamat + '&total_harga=' + totalHarga);

    // Setelah data dikirim, tutup modal dan kosongkan keranjang
    document.getElementById('modalPesan').style.display = 'none';

    // Hapus data cart dengan AJAX
    var xhrDeleteCart = new XMLHttpRequest();
    xhrDeleteCart.open('POST', 'hapus_cart.php', true);
    xhrDeleteCart.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhrDeleteCart.onload = function() {
        if (xhrDeleteCart.status === 200) {
            console.log('Keranjang telah dikosongkan');
        } else {
            console.log('Gagal mengosongkan keranjang');
        }
    };
    xhrDeleteCart.send('user_id=' + <?php echo $userID; ?>);  // Kirimkan user_id untuk menghapus keranjang yang sesuai
});
</script>
</body>
</html>

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
/* Style untuk Halaman Keranjang */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    margin-top: 150px;
    padding: 0;
    background-color: #f9f9f9;
}
.keranjang-container {
    max-width: 90%;
    width: 85%;
    margin: 160px auto;
    padding: 20px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
h2 {
    font-family: 'PublicaSans-Medium', sans-serif;
    font-size: 35px;
    text-align: center;
    color: #333;
}
.keranjang-highlight {
    color: #F26D21;
}
.keranjang-table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}
.keranjang-table th, .keranjang-table td {
    padding: 10px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}
.produk-image {
    width: 50px;
    height: 50px;
    border-radius: 5px;
    margin-right: 10px;
}
.quantity-controls {
    display: flex;
    align-items: center;
    justify-content: center;
}
.quantity-button {
    background-color: #F26D21;
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    font-size: 14px;
    margin: 0 5px;
    border-radius: 5px;
}
.quantity-input {
    width: 40px;
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 5px;
}
.hapus-button {
    background-color: red;
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    border-radius: 5px;
}
.orange-button {
    background-color: #F26D21;
    color: white;
    border: none;
    padding: 10px 50px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}
.keranjang-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    font-size: 18px;
    font-weight: bold;
}
.keranjang-footer span {
    color: #F26D21;
}

/* Gaya untuk modal */
.modal {
    display: none; /* Modal tersembunyi secara default */
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto; /* Scroll jika konten terlalu besar */
    background-color: rgba(0, 0, 0, 0.6); /* Latar belakang transparan */
}

/* Konten modal */
.modal-content {
    position: relative;
    background-color: #fff;
    margin: 10% auto;
    padding: 20px;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    animation: fadeIn 0.4s ease;
}

/* Animasi fade-in untuk modal */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10%);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Tombol close di pojok kanan atas */
.close-modal {
    position: absolute;
    top: 10px;
    right: 15px;
    color: #333;
    font-size: 20px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.3s ease;
}

.close-modal:hover {
    color: #f00;
}

/* Gaya input dan textarea */
.modal-content input,
.modal-content textarea {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    box-sizing: border-box;
}

.modal-content input:focus,
.modal-content textarea:focus {
    outline: none;
    border-color: #3F51B5;
    box-shadow: 0 0 5px rgba(63, 81, 181, 0.5);
}

/* Gaya tombol */
.modal-content button {
    background-color: #3F51B5;
    color: #fff;
    border: none;
    padding: 10px 15px;
    font-size: 16px;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
    transition: background-color 0.3s ease;
}

.modal-content button:hover {
    background-color: #303f9f;
}

.modal-content button:active {
    background-color: #283593;
}

/* Responsif untuk layar kecil */
@media (max-width: 480px) {
    .modal-content {
        width: 95%;
        padding: 15px;
    }
}


</style>

<?php
include_once "../includes/footer.php";
?>
