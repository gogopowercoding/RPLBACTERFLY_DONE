<?php
session_start();

// Sertakan file koneksi
require_once 'koneksi.php';

// Sertakan PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Aktifkan debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inisialisasi pesan
$pesan = '';
$show_reset_form = false;

// Tangani tombol "Kirim Kode Reset"
if (isset($_POST['send_reset_code'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Periksa apakah email terdaftar
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() == 0) {
        $pesan = "Email tidak terdaftar!";
    } else {
        // Buat kode reset 6 digit
        $reset_code = str_pad(rand(0, 999999), 6, "0", STR_PAD_LEFT);

        // Simpan kode reset di session
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_code'] = $reset_code;

        // Kirim email dengan kode reset menggunakan PHPMailer
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'hieropurbandono@gmail.com';
            $mail->Password = 'edfs ovlm pnqs vsdc';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('hieropurbandono@gmail.com', 'BacterFLY');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Kode Reset Kata Sandi Anda';
            $mail->Body = "Kode reset kata sandi Anda adalah: <b>$reset_code</b>";
            $mail->AltBody = "Kode reset kata sandi Anda adalah: $reset_code";

            $mail->send();
            $pesan = "Kode reset telah dikirim ke email Anda!";
            $show_reset_form = true;
        } catch (Exception $e) {
            $pesan = "Gagal mengirim kode reset. Kesalahan: {$mail->ErrorInfo}";
        }
    }
}

// Tangani tombol "Reset Kata Sandi"
if (isset($_POST['reset_password'])) {
    $email = $_SESSION['reset_email'];
    $code = $_POST['reset_code'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_new_password'];

    if ($new_password !== $confirm_password) {
        $pesan = "Kata sandi baru tidak cocok!";
        $show_reset_form = true;
    } elseif (!isset($_SESSION['reset_code']) || $code !== $_SESSION['reset_code']) {
        $pesan = "Kode reset salah!";
        $show_reset_form = true;
    } else {
        // Hash kata sandi baru
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update kata sandi di database
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$hashed_password, $email]);

        // Simpan pesan sukses ke session
        $_SESSION['pesan'] = "Kata sandi berhasil direset! Silakan login.";
        
        // Bersihkan session
        unset($_SESSION['reset_email']);
        unset($_SESSION['reset_code']);
        
        // Redirect ke halaman login
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a1a;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
        }
        .login-btn {
            width: 100%;
            padding: 10px;
            background-color: #ff6200;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        .links {
            margin-top: 10px;
            text-align: center;
        }
        .links a {
            color: #ff6200;
            text-decoration: none;
        }
        .message {
            text-align: center;
            margin-bottom: 15px;
            color: #ff6200;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($pesan): ?>
            <div class="message"><?php echo $pesan; ?></div>
        <?php endif; ?>

        <?php if ($show_reset_form): ?>
            <!-- Formulir Reset Kata Sandi -->
            <form method="POST" action="">
                <div class="form-group">
                    <input type="text" name="reset_code" placeholder="Input Reset Code" required>
                </div>
                <div class="form-group">
                    <input type="password" name="new_password" placeholder="New Password" required>
                </div>
                <div class="form-group">
                    <input type="password" name="confirm_new_password" placeholder="Confirm New Password" required>
                </div>
                <button type="submit" name="reset_password" class="login-btn">Password Reset</button>
                <div class="links">
                    <a href="login.php">Back To Log In</a>
                </div>
            </form>
        <?php else: ?>
            <!-- Formulir Kirim Kode Reset -->
            <p style="text-align: center;">Office email only</p>
            <form method="POST" action="">
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email Address" required>
                </div>
                <button type="submit" name="send_reset_code" class="login-btn">Send Reset Code</button>
                <div class="links">
                    <a href="login.php">Back To Login</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>