<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | BuTITIN</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&display=swap">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Titan+One&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Lemon&display=swap');

        @font-face {
            font-family: 'PublicaSans-Medium';
            src: url('../assets/PublicaSans-Medium.woff') format('woff');
            font-weight: normal;
            font-style: normal;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .login {
            position: relative;
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


        .form-login label {
            font-family: 'Poppins', sans-serif;
            margin-left: 50px;
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 14px;
        }

        .form-login input,
        .form-login button {
            margin-left: 50px;
            display: block;
            width: 80%;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-bottom: 2px solid #e1e1e1;
            transition: border-bottom 0.3s ease;
        }

        .form-login input:focus {
            border-bottom: 2px solid #F26421;
            outline: none;
        }

        .form-login button {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            background: linear-gradient(to right, #F26421, #F27521);
            color: #fff;
            cursor: pointer;
            border-radius: 10px;
            transition: background-color 0.3s ease;
            border-top: 2px solid #FDB58F;
            border-right: 2px solid #FDB58F;
            border-left: 2px solid #F79460;
            border-bottom: 2px solid #F79460;
            width: 88%;
        }

        .form-login button:hover {
            background: linear-gradient(to right, #E1672F, #E1672F);
        }

        .form-login p {
            font-family: 'PublicaSans-Light', sans-serif;
            margin-top: 15px;
            margin-left: 76px;
        }

        .form-login a {
            letter-spacing: 1px;
            color: #F26421;
            text-decoration: none;
            font-weight: bold;
            font-family: 'PublicaSans-Medium', sans-serif;
        }

        .form-login h2 {
            letter-spacing: 2px;
            margin-top: 0px;
            font-size: 37px;
            margin-left: 120px;
            margin-right: 0px;
            color: #1B1817;
            font-family: 'PublicaSans-Medium', sans-serif;
        }

        .form-login .login-highlight {
            color: #F26421;
        }

        .form-login a:hover {
            text-decoration: underline;
        }

        .image-login {
            margin-left: 20px;
        }

        .image-login img {
            margin-left: 90px;
            width: 70%;
            border-radius: 8px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            margin-top: 0px;
        }

        .remember-me input[type="checkbox"] {
            margin-top: 8px;
            margin-right: 0px;
            width: 15px !important;
            height: 15px !important;
            border: 4px solid #555 !important;
            border-radius: 3px;
            cursor: pointer;
        }

        .remember-me input[type="checkbox"]:checked {
            background-color: #F26421;
            border-color: #F26421;
        }

        .remember-me label {
            font-family: 'PublicaSans-Light', sans-serif;
            color: #555;
            cursor: pointer;
            font-size: 13px;
            margin-left: 6px;
            margin-top: 0px;
        }
    </style>
</head>
<body>
    <section class="login">
        <!-- Ikon kembali -->
        <a href="../index.php" class="back-icon">
            <img src="../assets/images/icon_kembali.png" alt="Ikon Kembali">
        </a>

        <div class="form-login">
            <h2>Ma<span class="login-highlight">suk</span></h2>
            <form action="login_process.php" method="POST">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" placeholder="Username" required>

                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="Password" required>

                <div class="remember-me">
                    <input type="checkbox" name="remember" id="remember" value="1">
                    <label for="remember">Remember Me</label>
                </div>

                <button type="submit" name="login">Masuk</button>
            </form>
            <p>Belum punya akun? <a href="../pages/register.php">Daftar</a></p>
        </div>
        <div class="image-login">
            <img src="../assets/images/login-image.png" alt="Login Illustration">
        </div>
    </section>

    <script>
        <?php
        if (isset($_SESSION['login_error'])) {
            echo "alert('".$_SESSION['login_error']."');";
            unset($_SESSION['login_error']);
        }
        ?>
    </script>
</body>
</html>
