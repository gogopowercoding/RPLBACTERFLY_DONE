<?php
session_start();

// Sertakan file koneksi
require_once 'koneksi.php';

// Aktifkan debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Tangani tombol "Log in"
$pesan = '';
if (isset($_POST['login'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Cari pengguna di database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password']) && $user['is_verified'] == 1) {
        // Regenerasi session ID untuk mencegah session fixation
        session_regenerate_id(true);

        // Login berhasil
        $_SESSION['user_email'] = $email;
        $_SESSION['logged_in'] = true;
        $_SESSION['id'] = $user['id']; // Add user ID to session
        $_SESSION['division'] = $user['division'] ?? '-'; // Ambil divisi dari database

        // Debugging: Tampilkan divisi untuk verifikasi
        error_log("Divisi user: " . $user['division']);

        // Redirect ke halaman sesuai divisi
        $division = strtolower(trim($user['division']));
        switch ($division) {
            case 'laboratorium':
                header("Location: lab_dashboard.php");
                break;
            case 'produksi':
                header("Location: pilih_bidang.php");
                break;
            case 'manajer':
                header("Location: manajer.php");
                break;
            default:
                header("Location: home.php"); // Jika divisi tidak terdefinisi
                error_log("Divisi tidak dikenali: " . $division);
        }
        exit();
    } else {
        $pesan = "Email atau kata sandi salah, atau akun belum diverifikasi.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk</title>
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
        .form-group input[type="checkbox"] {
            width: auto;
            margin-right: 10px;
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .links a {
            color: #ff6200;
            text-decoration: none;
            font-size: 14px;
            padding: 5px 10px;
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

        <p style="text-align: center;">Login using email office only</p>
        <form method="POST" action="">
            <div class="form-group">
                <input type="email" name="email" placeholder="Email Address" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" required>
                    I confirm that i have read, consent and agree to Bacterfly's Terms of Use and Privacy Policy.
                </label>
            </div>
            <button type="submit" name="login" class="login-btn">LOG IN</button>
            <div class="links">
                <a href="forgot_password.php">Forgot Password?</a>
                <a href="rregister.php">Register</a>
            </div>
        </form>
    </div>
</body>
</html>