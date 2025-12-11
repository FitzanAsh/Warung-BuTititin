<title>Daftar | BuTITIN</title>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,700;0,800;1,200;1,300&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,600;0,700;0,800;1,600;1,700&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Titan+One&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Lemon&display=swap');

@font-face {
  font-family: 'PublicaSans-Medium'; /* Nama yang Anda inginkan untuk font ini */
  src: url('../assets/PublicaSans-Medium.woff') format('woff'); /* Lokasi file font WOFF */
  /* Opsional: Anda dapat menambahkan varian font seperti bold atau italic jika diperlukan */
  font-weight: normal;
  font-style: normal;
}
     body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
}
    .daftar {
        position: relative;
        padding-left: 0px;
        margin-top: 50px;
        margin-bottom: 35px;
        margin-left: 190px;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 500px;
        width: 900px;
        background-color: #ffffff;
        border-radius: 20px;
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
    }

    .back-icon {
            position: absolute;
            top: 35px;
            left: 40px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .back-icon img {
            width: 65px;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .back-icon:hover img {
            transform: scale(1.1);
        }


    .image-daftar {
        margin-left: 0px;
    }

    .image-daftar img {
        margin-left: 80px;
        width: 70%;
        border-radius: 8px;
    }

    .form-daftar {
        margin-right: 110px;
       margin-left: 20px;
       margin-bottom: 30px;
    }

    .form-daftar h2 {
        letter-spacing: 2px;
        font-size: 37px;
        color: #1B1817;
        font-family: 'PublicaSans-Medium', sans-serif;
        margin-left: 80px;
    }

    .form-daftar .daftar-highlight {
        color:#F26421 ;
    }

    .form-daftar label {
        font-family: 'Poppins', sans-serif;
        margin-top: 15px;
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        font-size: 14px;
    }

    .form-daftar input,
    .form-daftar button {
        display: block;
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: none;
        border-bottom: 2px solid #e1e1e1;
        transition: border-bottom 0.3s ease;
    }

    .form-daftar input:focus {
        border-bottom: 2px solid #F26421;
        outline: none;
    }

    .form-daftar button {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        background: linear-gradient(to right, #F26421, #F27521);
        color: #fff;
        cursor: pointer;
        border-radius: 10px;
        border-top: 2px solid #FDB58F;
        border-right: 2px solid #FDB58F;
        border-left: 2px solid #F79460;
        border-bottom: 2px solid #F79460;
        transition: background-color 0.3s ease;
        width: 110%;
    }

    .form-daftar button:hover {
        background: linear-gradient(to right, #E1672F, #E1672F);
    }

    .form-daftar p {
        font-family: 'PublicaSans-Light', sans-serif;
        margin-top: 15px;
        margin-left: 40px;
    }

    .form-daftar a {
        letter-spacing: 1px;
        color: #F26421;
        text-decoration: none;
        font-weight: bold;
        font-family: 'PublicaSans-Medium', sans-serif;
    }

    .form-daftar a:hover {
        text-decoration: underline;
    }
</style>

<section class="daftar">
    <!-- Ikon kembali -->
    <a href="../index.php" class="back-icon">
            <img src="../assets/images/icon_kembali.png" alt="Ikon Kembali">
        </a>

<div class="image-daftar">
        <img src="../assets/images/login-image.png" alt="nasi-lemak.png">
    </div>
<div class="form-daftar">
    <h2>Daf<span class="daftar-highlight">tar</span></h2>
    <form action="proses_daftar.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" placeholder="Username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" placeholder="Password" required>

        <label for="confirm_password">Konfirmasi Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>

        <button type="submit" name="register">Daftar</button>
    </form>
    <p>Sudah punya akun? <a href="../pages/login.php">Masuk</a></p>
</div>
</section>

