<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Warung BuTITIN</title>
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
            margin-top: 37px;
            font-family: 'Montserrat', sans-serif;
        }

        .about-us {
            width: 100%; /* Lebar penuh */
            margin: 60px auto;
            padding-top: 50px;
            background-color: #fff;
        }
        .about-us img {
            width: 100%;
            height: 500px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            object-fit: cover;
        }

        .deskripsi_ {
            width: 80%;
            text-align: center;
            background-color: #F26D21;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
            margin-left: 7%;
            margin-top: 30px;
        }

        .deskripsi_ p {
          font-family: 'PublicaSans-Light', sans-serif;
            font-size: 16px;
            color: #fff;
            line-height: 1.5;
        }

.about-produk {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 60px auto;
    padding: 0 20px;
    max-width: 1200px;
}

.about-produk .image {
    flex-basis: 45%; /* Lebar gambar */
    margin-right: 20px;
}

.about-produk .image img {
    width: 100%;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.about-produk .nasi-lemak {
    flex-basis: 50%; /* Lebar deskripsi */
}

.about-produk .nasi-lemak p {
  font-family: 'PublicaSans-Light', sans-serif;
    font-size: 16px;
    color: #666;
    line-height: 1.5;
}
.about-us h3 {
        margin-bottom: 20px;
        font-family: 'PublicaSans-Medium', sans-serif;
        font-size: 35px;
        margin-left: 41%;
        color: #1B1817;
    }

    .about-us .daftar-highlight {
        color: #F26421;
    }

    </style>
</head>

<body>

    <?php 
    session_start();
    include_once "../includes/header.php"; 
    ?>

    <!-- Konten Halaman Tentang Kami -->
    <section class="about-us">
<h3>Tentang <span class="daftar-highlight">Kami</span></h3>
        <img src="../assets/images/Warung.png" alt="Foto Warung">
        <div class="deskripsi_">
        <p>
    Warung makan Butitin adalah destinasi kuliner terbaik yang telah menjadi kepercayaan pelanggan sejak tahun 2012. Dengan komitmen untuk memberikan pengalaman kuliner yang tak terlupakan, warung ini mempersembahkan beragam menu lezat yang cocok untuk makan pagi dan makan malam.
    Nikmati keberagaman hidangan kami, mulai dari sarapan tradisional yang menggugah selera hingga pilihan hidangan malam yang memikat. Setiap hidangan disajikan dengan teliti dan menggunakan bahan-bahan berkualitas tinggi untuk menjamin kelezatan dan kepuasan pelanggan kami.
    Keindahan Warung Butitin tidak hanya terletak pada cita rasa makanan yang istimewa, tetapi juga pada suasana yang hangat dan ramah. Kami berusaha menciptakan lingkungan yang menyambut dan nyaman bagi setiap pelanggan, sehingga Anda dapat menikmati waktu bersantap dengan suasana yang menyenangkan.
</p>
<p>
    Dengan staf yang profesional dan pelayanan yang cepat, Warung Butitin berkomitmen untuk memberikan pengalaman kuliner yang memuaskan setiap kali Anda mengunjungi kami. Segera kunjungi Warung Butitin dan nikmati sensasi kuliner yang luar biasa di setiap sajian kami.
    Sebagai bagian dari komitmen kami terhadap kualitas, Warung Butitin selalu memperbarui menu kami dengan kreasi baru dan inovatif. Kami menggabungkan cita rasa tradisional dengan sentuhan modern untuk menciptakan pengalaman kuliner yang unik dan tak terlupakan.
    Selain hidangan utama, Warung Butitin juga menyajikan berbagai minuman segar dan pencuci mulut lezat untuk melengkapi pengalaman bersantap Anda. Setiap minuman dan hidangan pencuci mulut kami dirancang untuk menyegarkan dan memuaskan selera Anda.
    Warung Butitin dengan bangga menyambut semua kalangan untuk menikmati hidangan lezat kami.
</p>
        </div>
    </section>

    <section class="about-produk">
      <div class="image">
      <img src="../assets/images/nasi_lemak.jpg" alt="Foto Warung">
      </div>
      <div class="nasi-lemak">
        <p> Nasi Lemak, sang kuliner yang melambangkan kelezatan dan kehangatan, telah memenangkan hati setiap pengunjung di Warung Butitin. Disajikan dalam sepiring nasi yang diaron dan dilengkapi dengan santan yang kental dan harum, Nasi Lemak di Warung Butitin menjadi pilihan utama bagi pencinta kuliner yang mencari kombinasi cita rasa yang autentik dan menggoda.
        </p>
        <p>Nasi Lemak di Warung Butitin tidak hanya sekadar nasi yang dimasak dengan santan, tetapi juga disajikan dengan pelengkap yang melengkapi pengalaman rasa. Ikan teri kacang, telur rebus, irisan mentimun segar, dan sambal yang pedas namun meresap, semuanya menyatu dalam satu hidangan yang kaya akan tekstur dan rasa.
        </p>
      </div>
    </section>

    <?php include_once "../includes/footer.php"; ?>

</body>

</html>
