<?php
session_start();
if ($_SESSION['role'] != 'user') {
    // Jika bukan user, arahkan ke halaman error atau halaman login
    header("Location: login.php");
    exit();
}

include_once "../includes/header.php";

?>
<!DOCTYPE html>
<html>
<head>
  <title>Judul Halaman</title>
  <link rel="stylesheet" type="text/css" href="../assets/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
  <body>
  <section class="banner">
  <div class="banner-content">
    <h2>
      <span class="selamat-text">Selamat Datang di</span>
      <!-- Tambahkan class baru pada span -->
      <span class="text-container">
        <span class="warung-text">Warung</span>
        <span class="butitin-text">BuTITIN</span>
      </span>
    </h2>
    <p>Warung makanan di pagi hari yang sehat dan bergizi. Temukan pilihan makanan lezat yang akan memulai hari Anda dengan penuh energi</p>
    
<a href="#" id="orderButton" target="_blank" class="order-button">
  <img src="../assets/images/Wa.png" alt="WhatsApp Icon">
  Chat WhatsApp
</a>

<script>
                // Fungsi untuk mengecek apakah user sudah login
                function isUserLoggedIn() {
                    return <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
                }

                // Mengupdate link button WhatsApp
                function updateOrderButtonHref() {
                    const loggedIn = isUserLoggedIn();  // Cek apakah user login
                    const whatsappMessage = 'Halo... Saya ingin memesan makanan dan minuman, bagaimana cara pemesannya.';
                    const whatsappOrderURL = `https://wa.me/6285158925502?text=${encodeURIComponent(whatsappMessage)}`;
                    
                    // Jika login, arahkan ke WhatsApp, jika tidak, arahkan ke halaman login
                    const orderButton = document.getElementById('orderButton');
                    orderButton.href = loggedIn ? whatsappOrderURL : 'login.php';

                    // Jika user sudah login, beri event listener untuk membuka WhatsApp
                    if (loggedIn) {
                        orderButton.addEventListener('click', function(event) {
                            event.preventDefault(); // Mencegah reload halaman
                            window.location.href = whatsappOrderURL; // Arahkan ke WhatsApp
                        });
                    }
                }

                // Panggil fungsi update ketika halaman siap
                window.onload = updateOrderButtonHref;
            </script>


  </div>
  <div class="banner-image">
    <img src="../assets/images/Lontongpng.png" alt="Deskripsi Gambar">
  </div>
</section>

<section class="products">
  <h3>Menu <span class="kami-highlight">Kami</span></h3>
  <p id="makanan-minuman">Makanan & Minuman</p>
  <div class="scrollable-container">
    <div class="product-list">
    <!-- Card produk -->
    <div class="product-item">
      <img src="../assets/images/ayam.jpg" alt="Product 1">
      <h4>Ayam Bakar</h4>
      <p>Ayam bakar...</p>
    </div>
    <div class="product-item">
      <img src="../assets/images/Nasi Gurih.jpg" alt="Product 2">
      <h4>Nasi Gurih</h4>
      <p>Nasi gurih...</p>
    </div>
    <!-- Tambahkan 4 produk lainnya -->
    <div class="product-item">
      <img src="../assets/images/Lontong.jpg" alt="Product 3">
      <h4>Lontong Sayur</h4>
      <p>Lontong sayur...</p>
    </div>
    <div class="product-item">
      <img src="../assets/images/Serabi.jpg" alt="Product 4">
      <h4>Serabi</h4>
      <p>Serabi manis...</p>
    </div>
    <div class="product-item">
      <img src="../assets/images/Es Teh.jpg" alt="Product 5">
      <h4>Teh Manis</h4>
      <p>Teh manis...</p>
    </div>
    <div class="product-item">
      <img src="../assets/images/Kue Lupis.png" alt="Product 6">
      <h4>Kue Lupis</h4>
      <p>Kue lupis manis...</p>
      </div>
  </div>
</section>
<div class="scroll-buttons">
  <div class="left-button">⬅</div>
  <a href="products.php" class="see-more">
  Lihat Semua Produk
  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 12 12">
    <g transform="rotate(90 6 6) translate(0 12) scale(1 -1)">
      <path fill="currentColor" d="M6 1.5a.75.75 0 0 1 .75.75v5.94l1.97-1.97a.75.75 0 0 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L2.22 7.28a.75.75 0 1 1 1.06-1.06l1.97 1.97V2.25A.75.75 0 0 1 6 1.5Z"/>
    </g>
  </svg>
</a>
  <div class="right-button">➡</div>
</div>
<script src="../assets/script.js"></script>

<section class="about-us">
  <div class="left-section">
    <img src="../assets/images/Warung.png" alt="Foto Warung" style="width: 100%;">
  </div>
  <div class="right-section">
    <h3>Tentang <span class="warung-highlight">Warung</span></h3>
    <p>Warung makan Butitin telah menjadi tempat makan yang terpercaya sejak tahun 2012. Dengan menu yang beragam, warung ini menawarkan hidangan lezat untuk makan pagi dan makan malam. Dari sarapan tradisional hingga hidangan malam yang menggugah selera, Warung Butitin adalah tempat yang sempurna untuk memuaskan rasa lapar Anda sepanjang hari.</p>
  </div>
</section>

<section class="contact-form">
    <div class="form">
    <h4>Hubungi <span class="contac-h4">Kami</span></h4>
<form class="custom-form" action="proses_contact.php" method="POST">
  <input type="text" name="name" placeholder="Nama" required>
  <input type="email" name="email" placeholder="Email" required>
  <textarea name="pesan" placeholder="Pesan" required></textarea>
  <button type="submit">Kirim Pesan</button>
</form>

  </div>
  
  <div class="social-media">
  <h4>Alamat</h4>
  <p>
    <a href="https://www.google.com/maps/place/Warung+Bu+Titin/@3.0353844,99.0833671,3a,75y,180.4h,76.43t/data=!3m6!1e1!3m4!1scDaQZdfLPj6Vbxwuk4MV4w!2e0!7i16384!8i8192!4m6!3m5!1s0x303183156e64e67b:0x5c2cae1b77da1ab0!8m2!3d3.0353323!4d99.0833558!16s%2Fg%2F11sjfnhvf7?entry=ttu" target="_blank">
      <svg version="1.1" id="Icons" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-2.24 -2.24 36.48 36.48" xml:space="preserve" width="157px" height="157px" fill="#000000" stroke="#e60a0a">
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.128"></g>
        <g id="SVGRepo_iconCarrier">
          <style type="text/css"> .st0{fill:none;stroke:#000000;stroke-width:0.704;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;} </style>
          <path d="M16,2c-5,0-9,4-9,8.9c0,7.4,8,13.7,8.4,13.9c0.2,0.1,0.4,0.2,0.6,0.2s0.4-0.1,0.6-0.2C17,24.5,25,18.3,25,10.9 C25,6,21,2,16,2z M16,14c-1.7,0-3-1.3-3-3s1.3-3,3-3s3,1.3,3,3S17.7,14,16,14z"></path>
          <path d="M29.9,28.6l-4-8C25.7,20.2,25.4,20,25,20h-1.2c-2.4,3.6-5.4,6-6,6.4C17.3,26.8,16.7,27,16,27s-1.3-0.2-1.8-0.6 c-0.5-0.4-3.6-2.8-6-6.4H7c-0.4,0-0.7,0.2-0.9,0.6l-4,8c-0.2,0.3-0.1,0.7,0,1S2.7,30,3,30h26c0.3,0,0.7-0.2,0.9-0.5 S30,28.9,29.9,28.6z"></path>
        </g>
      </svg>
      Jl. Gg. Subur No.237, Dolok Ulu, Kec. Tapian Dolok, Kabupaten Simalungun
    </a>
  </p>
  <div class="media">
  <h4>Sosial Media</h4>
    <div class="social-icons">
    <a href="https://www.facebook.com/akun-fb" target="_blank" class="facebook-icon">
    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="0" height="0" viewBox="0 0 48 48">
<linearGradient id="awSgIinfw5_FS5MLHI~A9a_yGcWL8copNNQ_gr1" x1="6.228" x2="42.077" y1="4.896" y2="43.432" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#0d61a9"></stop><stop offset="1" stop-color="#16528c"></stop></linearGradient><path fill="url(#awSgIinfw5_FS5MLHI~A9a_yGcWL8copNNQ_gr1)" d="M42,40c0,1.105-0.895,2-2,2H8c-1.105,0-2-0.895-2-2V8c0-1.105,0.895-2,2-2h32	c1.105,0,2,0.895,2,2V40z"></path><path d="M25,38V27h-4v-6h4v-2.138c0-5.042,2.666-7.818,7.505-7.818c1.995,0,3.077,0.14,3.598,0.208	l0.858,0.111L37,12.224L37,17h-3.635C32.237,17,32,18.378,32,19.535V21h4.723l-0.928,6H32v11H25z" opacity=".05"></path><path d="M25.5,37.5v-11h-4v-5h4v-2.638c0-4.788,2.422-7.318,7.005-7.318c1.971,0,3.03,0.138,3.54,0.204	l0.436,0.057l0.02,0.442V16.5h-3.135c-1.623,0-1.865,1.901-1.865,3.035V21.5h4.64l-0.773,5H31.5v11H25.5z" opacity=".07"></path><path fill="#fff" d="M33.365,16H36v-3.754c-0.492-0.064-1.531-0.203-3.495-0.203c-4.101,0-6.505,2.08-6.505,6.819V22h-4v4	h4v11h5V26h3.938l0.618-4H31v-2.465C31,17.661,31.612,16,33.365,16z"></path>
</svg>
    </a>
    <a href="https://www.instagram.com/akun-instagram" target="_blank" class="instagram-icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="0" height="0" viewBox="0 0 256 256"><g fill="none"><rect width="256" height="256" fill="url(#skillIconsInstagram0)" rx="60"/><rect width="256" height="256" fill="url(#skillIconsInstagram1)" rx="60"/><path fill="#fff" d="M128.009 28c-27.158 0-30.567.119-41.233.604c-10.646.488-17.913 2.173-24.271 4.646c-6.578 2.554-12.157 5.971-17.715 11.531c-5.563 5.559-8.98 11.138-11.542 17.713c-2.48 6.36-4.167 13.63-4.646 24.271c-.477 10.667-.602 14.077-.602 41.236s.12 30.557.604 41.223c.49 10.646 2.175 17.913 4.646 24.271c2.556 6.578 5.973 12.157 11.533 17.715c5.557 5.563 11.136 8.988 17.709 11.542c6.363 2.473 13.631 4.158 24.275 4.646c10.667.485 14.073.604 41.23.604c27.161 0 30.559-.119 41.225-.604c10.646-.488 17.921-2.173 24.284-4.646c6.575-2.554 12.146-5.979 17.702-11.542c5.563-5.558 8.979-11.137 11.542-17.712c2.458-6.361 4.146-13.63 4.646-24.272c.479-10.666.604-14.066.604-41.225s-.125-30.567-.604-41.234c-.5-10.646-2.188-17.912-4.646-24.27c-2.563-6.578-5.979-12.157-11.542-17.716c-5.562-5.562-11.125-8.979-17.708-11.53c-6.375-2.474-13.646-4.16-24.292-4.647c-10.667-.485-14.063-.604-41.23-.604h.031Zm-8.971 18.021c2.663-.004 5.634 0 8.971 0c26.701 0 29.865.096 40.409.575c9.75.446 15.042 2.075 18.567 3.444c4.667 1.812 7.994 3.979 11.492 7.48c3.5 3.5 5.666 6.833 7.483 11.5c1.369 3.52 3 8.812 3.444 18.562c.479 10.542.583 13.708.583 40.396c0 26.688-.104 29.855-.583 40.396c-.446 9.75-2.075 15.042-3.444 18.563c-1.812 4.667-3.983 7.99-7.483 11.488c-3.5 3.5-6.823 5.666-11.492 7.479c-3.521 1.375-8.817 3-18.567 3.446c-10.542.479-13.708.583-40.409.583c-26.702 0-29.867-.104-40.408-.583c-9.75-.45-15.042-2.079-18.57-3.448c-4.666-1.813-8-3.979-11.5-7.479s-5.666-6.825-7.483-11.494c-1.369-3.521-3-8.813-3.444-18.563c-.479-10.542-.575-13.708-.575-40.413c0-26.704.096-29.854.575-40.396c.446-9.75 2.075-15.042 3.444-18.567c1.813-4.667 3.983-8 7.484-11.5c3.5-3.5 6.833-5.667 11.5-7.483c3.525-1.375 8.819-3 18.569-3.448c9.225-.417 12.8-.542 31.437-.563v.025Zm62.351 16.604c-6.625 0-12 5.37-12 11.996c0 6.625 5.375 12 12 12s12-5.375 12-12s-5.375-12-12-12v.004Zm-53.38 14.021c-28.36 0-51.354 22.994-51.354 51.355c0 28.361 22.994 51.344 51.354 51.344c28.361 0 51.347-22.983 51.347-51.344c0-28.36-22.988-51.355-51.349-51.355h.002Zm0 18.021c18.409 0 33.334 14.923 33.334 33.334c0 18.409-14.925 33.334-33.334 33.334c-18.41 0-33.333-14.925-33.333-33.334c0-18.411 14.923-33.334 33.333-33.334Z"/><defs><radialGradient id="skillIconsInstagram0" cx="0" cy="0" r="1" gradientTransform="matrix(0 -253.715 235.975 0 68 275.717)" gradientUnits="userSpaceOnUse"><stop stop-color="#FD5"/><stop offset=".1" stop-color="#FD5"/><stop offset=".5" stop-color="#FF543E"/><stop offset="1" stop-color="#C837AB"/></radialGradient><radialGradient id="skillIconsInstagram1" cx="0" cy="0" r="1" gradientTransform="matrix(22.25952 111.2061 -458.39518 91.75449 -42.881 18.441)" gradientUnits="userSpaceOnUse"><stop stop-color="#3771C8"/><stop offset=".128" stop-color="#3771C8"/><stop offset="1" stop-color="#60F" stop-opacity="0"/></radialGradient></defs></g></svg>
    </a>
    </div>
  </div>
  </div>
  <script>
</script>
</section>
</body>

<?php
include_once "../includes/footer2.php";
?>


