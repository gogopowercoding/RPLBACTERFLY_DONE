<?php
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start(); // Mulai sesi

require 'koneksi.php'; // Koneksi database menggunakan PDO

// Debug sesi
error_log("User ID di sesi: " . ($_SESSION['id'] ?? 'tidak ada'));

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['id'])) {
    error_log("Sesi id tidak ditemukan di profile.php");
    header("Location: login.php?error=session_expired");
    exit;
}

if (!$pdo) {
    error_log("Koneksi PDO gagal di profile.php");
    die("Koneksi gagal: Periksa konfigurasi database.");
}

$user_id = $_SESSION['id'];
$query = "SELECT nama, division FROM users WHERE id = :id";
$stmt = $pdo->prepare($query);

try {
    $stmt->execute(['id' => $user_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        error_log("Pengguna dengan ID $user_id tidak ditemukan di database");
        header("Location: login.php?error=user_not_found");
        exit;
    }
    $nama = $row['nama'] ?: 'Tidak Ditemukan';
    $division = $row['division'] ?: 'Tidak Diketahui';
} catch (PDOException $e) {
    error_log("Error query: " . $e->getMessage());
    $nama = 'Error';
    $division = 'Error: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil - <?= htmlspecialchars($division) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            font-family: 'Courier New', monospace;
            background-color: #000;
            color: white;
        }

        .top-bar {
            padding: 10px;
            background-color: #000;
            border-bottom: 1px solid #333;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FF8C42;
            font-size: 14px;
        }

        .logo img {
            height: 30px;
            margin-right: 10px;
        }

        .nav-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            width: 80%;
            max-width: 300px;
        }

        .nav-bar a,
        .nav-bar span {
            color: white;
            font-size: 16px;
            text-decoration: none;
        }

        .nav-bar .title {
            font-weight: bold;
        }

        .profile-container {
            text-align: center;
            margin-top: 40px;
        }

        .avatar img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #666;
        }

        .username {
            font-family: Georgia, serif;
            margin-top: 10px;
            font-size: 24px;
        }

        .user-info {
            text-align: left;
            width: 80%;
            margin: 20px auto;
            font-size: 18px;
        }

        .bottom-nav {
            display: flex;
            justify-content: space-around;
            background: #000;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.5);
            border-top: 1px solid #555;
        }

        .bottom-nav a {
            color: #fff;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 12px;
            transition: transform 0.2s, opacity 0.2s;
        }

        .bottom-nav img {
            width: 24px;
            height: 24px;
            margin-bottom: 2px;
            opacity: 0.7;
        }

        .bottom-nav a:hover img,
        .bottom-nav a.active img {
            opacity: 1;
            transform: scale(1.2);
        }

        .bottom-nav a.active {
            color: #FF8C42;
        }

        .bottom-nav a:hover {
            color: #FF8C42;
        }

        @media (max-width: 600px) {
            .bottom-nav img {
                width: 20px;
                height: 20px;
            }
            .bottom-nav a {
                font-size: 10px;
            }
            .nav-bar {
                width: 90%;
                max-width: 250px;
            }
        }
    </style>
</head>
<body>

    <header class="top-bar">
        <div class="logo"> 
            <img src="logo.png" alt="Logo BacterFly">
            <span>Selamat Datang di <strong>BacterFly</strong></span>
        </div>
        <div class="nav-bar">
            <a href="javascript:history.back()" class="back">< Kembali</a>
            <span class="title">Profil</span>
            <a href="edit_profil.php" class="edit">Ubah</a>
        </div>
    </header>

    <main class="profile-container">
        <div class="avatar">
            <img src="images/profile-icon.png" alt="Foto Profil">
        </div>
        <h2 class="username"><?= htmlspecialchars($nama) ?></h2>

        <div class="user-info">
            <p>Divisi: <?= htmlspecialchars($division) ?></p>
        </div>
    </main>

    <div class="bottom-nav">
        <a href="man_dashboard.php">
                <span>🏠</span>
                <span>Home</span>
            </a>
            <a href="man_pengawasan.php" class="active">
                <span>🕒</span>
                <span>Data</span>
            </a>
            <a href="man_intruksi.php">
                <span>📋</span>
                <span>Instruksi</span>
            </a>
            <a href="man_profil.php">
                <span>👤</span>
                <span>Profil</span>
            </a>
    </div>

</body>
</html>
