<?php
session_start();

// Sertakan file koneksi
require_once 'koneksi.php';

// Pemeriksaan session
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['id'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit();
}

// Fetch user data with prepared statement
$id_user = $_SESSION['id'];
$query = "SELECT nama, division FROM users WHERE id = ?";
$stmt = $pdo->prepare($query);

try {
    $stmt->execute([$id_user]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $nama = $row['nama'] ?? 'Tidak Ditemukan';
        $divisi = $row['division'] ?? '-';
    } else {
        $divisi = '-';
    }
} catch (PDOException $e) {
    $divisi = 'Error: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajer - BacterFly</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background: #000;
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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
            font-family: 'Courier New', monospace;
        }
        .logo img {
            height: 30px;
            margin-right: 10px;
        }
        .division {
            margin-top: 5px;
            font-size: 14px;
        }
        main {
            flex: 1;
            padding: 16px;
        }
        .reminder {
            background-color: #800080;
            padding: 12px;
            border-radius: 5px;
            text-align: center;
            margin-top: 20px;
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
        }
        /* Styling untuk tombol logout */
        .logout-btn {
            background-color: #FF4500;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s, transform 0.2s;
            position: absolute;
            top: 16px;
            right: 16px;
        }
        .logout-btn:hover {
            background-color: #FF6347;
            transform: scale(1.05);
        }
        .logout-btn:active {
            background-color: #E03A2E;
            transform: scale(0.98);
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="top-bar">
            <div class="logo">
                <img src="logo.png" alt="BacterFly Logo">
                <span>Welcome To <strong>BacterFly</strong></span>
            </div>
            <div class="division"><?= htmlspecialchars($divisi) ?></div>
            <a href="proses.php?logout=true">
                <button type="button" class="logout-btn">Logout</button>
            </a>
        </header>

        <main>
            <div class="reminder">
                <b>📣 Pengingat</b><br>
                Anda sedang di Beranda
            </div>
        </main>

        <div class="bottom-nav">
            <a href="manajer.php" class="active">
                <img src="images/home.png" alt="Home">
                <span>Home</span>
            </a>
            <a href="pengawasan.php">
                <img src="images/clock.png" alt="Pengawasan">
                <span>Pengawasan</span>
            </a>
            <a href="list_manajer.php">
                <img src="images/list.png" alt="List">
                <span>List</span>
            </a>
            <a href="profil.php">
                <img src="images/profile.png" alt="Profile">
                <span>Profile</span>
            </a>
        </div>
    </div>
</body>
</html>