<?php
session_start(); // Memulai session

// Periksa apakah pengguna sudah login
if (isset($_SESSION['user_id'])) {
    // Jika sudah login, arahkan ke halaman users
    header("Location: pages/user.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warung BuTITIN</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
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
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: url('assets/images/ayam.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
        }

        /* Tambahkan overlay untuk efek gelap */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: -1;
        }

        .logo {
            position: absolute;
            top: 60px;
            z-index: 1;
        }

        .logo img {
            width: 180px;
            height: auto;
        }

        .container {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 20px;
            padding-left: 40px;
            padding-right: 40px;
            background: rgba(43, 43, 43, 0.6);
            border-radius: 40px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
            border: 3px solid #ffffff;
            backdrop-filter: blur(10px);
        }

        .container h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .container h1 span.warung {
            font-size: 1.5em;
            font-family: 'Lemon', serif !important;
            color: #1B1817 !important;
            -webkit-text-stroke: 0.5px #FFF;
        }

        .container h1 span.butitin {
            font-size: 1.5em;
            font-family: 'Lemon', serif !important;
            color: #F26421;
        }

        .container p {
            font-size: 1.2em;
            margin-bottom: 30px;
            color: #fff;
        }

        .cta-buttons a {
            display: inline-block;
            text-decoration: none;
            padding: 12px 35px;
            margin: 10px;
            margin-bottom: 20px;
            border-radius: 10px;
            font-size: 1em;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        /* Button Pesan Sekarang - Gradien oranye */
        .btn-primary {
            background: linear-gradient(to right, #F26421, #F27521);
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease; 
            border-top: 2px solid #FDB58F;
            border-right: 2px solid #FDB58F;
            border-left: 2px solid #F79460;
            border-bottom: 2px solid #F79460;
        }

        .btn-primary:hover {
            background: linear-gradient(to right, #E1672F, #E1672F);
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <div class="logo">
        <img src="assets/images/WarungButitin2.png" alt="Logo Warung BuTITIN">
    </div>

    <div class="container">
        <h1>
            Selamat Datang di <span class="warung">Warung</span> <span class="butitin">BuTITIN</span>
        </h1>
        <p>Warung pagi dengan menu sehat dan bergizi. Temukan makanan favoritmu hanya di sini!</p>
        
        <div class="cta-buttons">
            <a href="pages/login.php" class="btn btn-primary">Pesan Sekarang</a>
        </div>
    </div>
</body>
</html>
