<?php
include("../db_connect.php");

// Pastikan user_id sudah ada dalam session dan valid
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Cek jika ada notifikasi yang dibaca
    if (isset($_GET['notification_id'])) {
        $notification_id = $_GET['notification_id'];

        // Update status notifikasi menjadi 'Dibaca'
        $update_query = "UPDATE notifications SET status = 'Dibaca' WHERE id = ? AND order_id IN (SELECT id FROM orders WHERE user_id = ?)";
        if ($update_stmt = mysqli_prepare($conn, $update_query)) {
            mysqli_stmt_bind_param($update_stmt, 'ii', $notification_id, $user_id);
            mysqli_stmt_execute($update_stmt);
            mysqli_stmt_close($update_stmt);
        } else {
            echo "Error updating notification status: " . mysqli_error($conn);
        }
    }

    // Query untuk mendapatkan notifikasi dengan status 'Belum Dibaca'
    $query = "
    SELECT 
        n.id AS notification_id,
        n.order_id,
        n.message,
        n.status,
        n.created_at,
        n.status_pesanan, 
        o.status AS order_status,
        o.processed_by
    FROM 
        notifications n
    JOIN 
        orders o ON n.order_id = o.id
    WHERE 
        o.user_id = ? AND n.status = 'Belum Dibaca'  -- Menyaring notifikasi yang belum dibaca
    ORDER BY 
        n.created_at DESC
    ";

    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, 'i', $user_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $notifications = [];
            while ($notification = mysqli_fetch_assoc($result)) {
                $notifications[] = $notification;
            }
        } else {
            echo "Error executing query: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Query preparation failed: " . mysqli_error($conn);
    }
} else {
    echo "User is not logged in.";
}
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>BuTITIN Eatery</title>
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
    /* Style untuk tata letak header */
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }
    
    header {
      background-color: #161413;
      padding-left: 40px;
      padding-right: 0px;
      padding-bottom: 0px;
      padding-top: 20px;
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1000;
      display: flex;
      flex-direction: column;
      justify-content: center;
      justify-content: space-between; /* Perubahan di sini */
      height: 92px;
     
    }

    .warung-title {
  display: flex;
  align-items: center;
  margin-bottom:10px;
}

.warung-title img {
  height: 35px; /* Sesuaikan dengan ukuran yang diinginkan */
  margin-right: 10px; /* Sesuaikan margin kanan sesuai kebutuhan */
  margin-top: 0;
}

    .search-form {
      position: absolute;
      top: 20px;
      right: 90px; /* Sesuaikan jarak dari kanan */
      display: flex;
      align-items: center;
    }

    .search-form form {
      display: flex;
      align-items: center;
      margin-right: 20px;
    }

    .search-form input {
      font-family: 'PublicaSans-Light',sans-serif;
      font-weight: 500;
      width: 200px;
      margin-right: 5px;
      background: transparent;
      border: 1px solid white;
      color: #161413;
      background-color: white;
      border-radius: 20px;
      text-align: center;
      height: 20px;
    }
    .search-container {
  position: relative;
  display: flex;
  align-items: center;
}

.search-container input {
  font-family: 'PublicaSans-Light', sans-serif;
  font-weight: 500;
  width: 250px;
  padding: 2px 0px 2px 15px;
  background: transparent;
  border: 1px solid white;
  color: #161413;
  background-color: white;
  border-radius: 20px;
  height: 30px;
  text-align: left; /* Teks diinput sejajar kiri */
}

.search-container .search-button {
  position: absolute;
  right: 18px; /* Tempatkan ikon di kanan input */
  background: none;
  border: none;
  padding: 0;
  cursor: pointer;
}

.search-container .search-icon {
  width: 20px; /* Ukuran ikon */
  height: 20px;
}


    .user-info {
  display: flex;
  align-items: center;
}

.cart-icon {
  width: 25px;
  height: 25px;
  fill: white; /* Ubah warna ikon keranjang menjadi putih */
  margin-right: 10px;
}

.username {
  color: white; /* Menetapkan teks username menjadi putih */
  font-family: 'PublicaSans-reguler', sans-serif;
}



a {
    color: white;
    text-decoration: none;
    margin-right: 10px;
    margin-bottom: 0px;
}
#dropdown-menu a {
    color: black;
}


    nav {
      width: 93%;
      text-align: left;
      margin-top: 5px;
    }

    nav ul {
      background-color: #F26421;
      list-style-type: none;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: left;
      border-top-right-radius: 10px;
      border-top-left-radius: 10px;
      padding-left: 20px;
    }

    nav ul li {
      font-family: 'PublicaSans-Medium', sans-serif;
  margin: 0 10px;
  padding: 10px 15px;
  position: relative;
  overflow: hidden;
  margin-left: 9px;
  padding-left: 25px;
  transition: background-color 0.3s, font-size 0.3s;
}

nav ul li::after {
  content: '';
  position: absolute;
  left: 0;
  bottom: 0;
  width: 0;
  height: 2px; /* Ubah tinggi garis bawah sesuai kebutuhan */
  background-color: white;
  transition: width 0.3s ease; /* Atur efek animasi saat lebar berubah */
}

nav ul li:hover::after {
  width: 100%; /* Lebarkan garis saat item disentuh */
}

nav ul li:hover {
  background-color: #F79460; /* Ganti warna latar belakang saat item disentuh */
}

nav ul li a {
  transition: transform 0.2s ease-in-out;
}

nav ul li a:active,
nav ul li a:focus {
  transform: scale(1.1); /* Misalnya, perbesar sedikit saat diklik */
}


    .burger-icon {
      display: none;
    }

    /* Warna default */
.cart-icon {
  margin-right: 10px;
  width: 25px;
  height: 25px;
  fill: #F26421; /* Warna ikon keranjang */
}


/* Warna saat hover */
.cart-link:hover .cart-icon {
  fill: #F7783D; /* Oranye lebih terang saat hover */
}


    @media screen and (max-width: 768px) {

      header {
        flex-direction: column;
        align-items: flex-start;
        height: 80px;
      }

      .admin-link {
        margin-top: 27px;
        margin-right: 85px;
        margin-left: 20px
    }

      .search-form {
        margin-left: 0;
        margin-top: 42px;
        margin-right: 15px;
      }

      nav {
        margin-top: 10px;
      }

      nav ul {
        flex-direction: column;
        align-items: center;
        background-color: #161413;
        position: absolute;
        top: 70px;
        left: 0;
        width: 200px;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-in;
        border-top-left-radius: 0px;
        border-bottom-right-radius: 15px;
        margin-top: 29px;
      }

      nav ul.show-nav {
        margin-top: 29px;
        max-height: 300px;
      }

      nav ul li {
        margin: 0;
        padding: 10px 0;
        border-bottom: 1px solid white;
        width: 100%;
        text-align: center;
        background-color:  #F26421;
      }

      .burger-icon {
        padding-left: 5px;
        padding-bottom: 1px;
        background-color: #F26421;
        border-radius: 3px;
        width: 20px;
        display: block;
        cursor: pointer;
        color:  white;
        margin-bottom: 15px;
      }

    }

    .order-button-pesanan {
    display: block;
    padding: 10px;
    background-color: #fff; 
    color: #171717;
    text-decoration: none;
    margin-bottom: 10px;
    border-radius: 2px;
    text-align: left;
}

.order-button-pesanan:hover {
    background-color: #F5F5F5;
    color: #F7783D;
}


  </style>
</head>
<body>
<header>
  <a href="user.php">
    <div class="warung-title">
      <img src="../assets/images/WarungButitin2.png" alt="Warung BuTITIN">
    </div>
  </a>
  <div class="search-form">
    <form action="../pages/products.php" method="GET">
      <div class="search-container">
        <input type="text" name="keyword" placeholder="Cari Produk">
        <button type="submit" class="search-button">
          <img src="../assets/images/icon_search.png" alt="Search" class="search-icon">
        </button>
      </div>
    </form>
  
    <div class="user-info">
      <a href="../pages/keranjang.php" class="cart-link">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="cart-icon">
          <path d="M253.3 35.1c6.1-11.8 1.5-26.3-10.2-32.4s-26.3-1.5-32.4 10.2L117.6 192 32 192c-17.7 0-32 14.3-32 32s14.3 32 32 32L83.9 463.5C91 492 116.6 512 146 512L430 512c29.4 0 55-20 62.1-48.5L544 256c17.7 0 32-14.3 32-32s-14.3-32-32-32l-85.6 0L365.3 12.9C359.2 1.2 344.7-3.4 332.9 2.7s-16.3 20.6-10.2 32.4L404.3 192l-232.6 0L253.3 35.1zM192 304l0 96c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-96c0-8.8 7.2-16 16-16s16 7.2 16 16zm96-16c8.8 0 16 7.2 16 16l0 96c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-96c0-8.8 7.2-16 16-16zm128 16l0 96c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-96c0-8.8 7.2-16 16-16s16 7.2 16 16z"/>
        </svg>
      </a>

      <?php if (isset($_SESSION['user_id'])): ?> 
    <span class="username" onclick="toggleDropdown()">Hello, <?= htmlspecialchars($_SESSION['username']) ?></span>
    <div id="dropdown-menu" class="dropdown-menu">
        <div class="dropdown-arrow"></div>

        <?php if (isset($notifications) && count($notifications) > 0): ?>
            <?php foreach ($notifications as $notification): ?>
                <?php if (isset($notification['notification_id'], $notification['message'], $notification['status_pesanan'])): ?>
                    <a href="pesanan_saya.php?notification_id=<?= $notification['notification_id'] ?>" class="notification-link" onclick="markAsRead(<?= $notification['notification_id'] ?>)">
                        <span class="status-pesanan"><?= htmlspecialchars($notification['status_pesanan']) ?></span>
                        <p class="notification-message"><?= htmlspecialchars($notification['message']) ?></p>
                    </a>
                <?php else: ?>
                    <p>Notifikasi tidak lengkap! ID atau pesan hilang.</p>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-notification">Tidak ada notifikasi saat ini.</p>
        <?php endif; ?>

        <a href="pesanan_saya.php" class="order-button-pesanan">Pesanan Saya</a>
        <a href="../pages/logout.php" class="logout-button">Logout</a>
    </div>
<?php endif; ?>

    </div>
  </div>

  <nav>
    <ul>
      <li><a href="../pages/user.php">Beranda</a></li>
      <li><a href="../pages/products.php">Produk</a></li>
      <li><a href="../pages/about.php">Tentang Warung</a></li>
    </ul>
    <div class="burger-icon" onclick="toggleNav()">&#9776;</div>
  </nav>
</header>

<script>
    function markAsRead(notificationId) {
        // Menyembunyikan notifikasi setelah diklik
        const notificationLink = document.querySelector(`a.notification-link[href='pesanan_saya.php?notification_id=${notificationId}']`);
        if (notificationLink) {
            notificationLink.style.display = 'none';
        }
    }
</script>

<script>
  function toggleNav() {
    const navList = document.querySelector('nav ul');
    navList.classList.toggle('show-nav');
  }

  // Fungsi untuk menampilkan atau menyembunyikan dropdown menu
  function toggleDropdown() {
    const dropdown = document.getElementById('dropdown-menu');
    dropdown.classList.toggle('show-dropdown');
  }

  // Fungsi untuk menandai notifikasi sebagai dibaca
  function markAsRead(notificationId) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'mark_as_read.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
      if (xhr.status === 200) {
        // Jika status berhasil, tandai notifikasi sebagai 'Dibaca' (ganti tampilannya atau lakukan apa yang diperlukan)
      }
    };
    xhr.send('notification_id=' + notificationId);
  }
</script>
</body>

<!-- Style tambahan untuk dropdown -->
<style>


  .dropdown-menu {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    min-width: 160px;
    z-index: 1;
    right: 0;
    top: 110%;
    padding: 10px 0 10px 10px;
    border-radius: 2px; /* Agar dropdown memiliki sudut tumpul */
  }

  .show-dropdown {
    display: block;
  }

  .dropdown-arrow {
    position: absolute;
    top: -10px;
    right: 25px; /* Atur posisi segitiga */
    width: 0;
    height: 0;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-bottom: 10px solid #f9f9f9; /* Warna segitiga sama dengan background dropdown */
  }

  .logout-button {
    padding: 10px 20px;
    color: #fff !important; /* Teks putih */
    background-color: red; /* Tombol merah */
    text-decoration: none;
    display: block;
    border-radius: 2px;
    text-align: left;
  }

  .logout-button:hover {
    background-color: darkred; /* Warna merah gelap saat hover */
  }

  .username {
    cursor: pointer;
  }

  .notification {
  padding: 10px;
  margin: 5px 0;
  border-radius: 5px;
  background-color: #f9f9f9;
  box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
}

.status-pesanan {
    font-weight: bold;
    font-size: 18px;  /* Ukuran font lebih besar untuk status pesanan */
    color: #007BFF;   /* Warna biru, sesuaikan dengan desain */
    margin-bottom: 5px; /* Memberikan sedikit jarak sebelum pesan */
}

.notification-message {
    font-size: 14px;  /* Ukuran font normal untuk pesan */
    color: #000000;   /* Warna teks hitam untuk pesan */
}

.notification-link {
    display: block;
    padding: 10px;
    background-color: #f9f9f9;
    margin: 5px 0;
    border-radius: 5px;
    text-decoration: none;
    color: #000000;
}

.notification-link:hover {
    background-color: #e0e0e0; /* Warna saat hover */
}


.notification a {
  text-decoration: none;
  color: #000000;
}

.notification.unread {
  background-color: #ffecb3; /* Warna latar belakang untuk notifikasi yang belum dibaca */
}

.notification.unread:hover {
  background-color: #ffd54f; /* Warna latar belakang saat hover */
}

.notification.read {
  background-color: #c8e6c9; /* Warna latar belakang untuk notifikasi yang sudah dibaca */
}

.notification.read:hover {
  background-color: #a5d6a7; /* Warna latar belakang saat hover */
}

</style>

</html>