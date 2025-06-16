<?php
session_start();

// Sertakan file koneksi
require 'koneksi.php';

if (!$pdo) {
    die("Koneksi gagal: Periksa pesan sebelumnya di log atau konfigurasi.");
}

// Pemeriksaan sesi
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Ambil ID dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("ID instruksi tidak valid.");
}

// Ambil data instruksi berdasarkan ID
$query = "SELECT * FROM instructions WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$id]);
$instruction = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$instruction) {
    die("Instruksi tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Instruksi - BacterFly</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background: #000;
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('images/virus.png');
            background-repeat: no-repeat;
            background-position: right center;
            background-size: 40%;
        }
        header {
            text-align: center;
            padding: 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        .logo-img {
            height: 30px;
            vertical-align: middle;
        }
        .logo {
            color: #FFA500;
            font-size: 24px;
            margin: 0;
        }
        h1 {
            font-size: 18px;
            margin: 0;
            color: #FFA500;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
        }
        .detail-item {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .detail-item p {
            margin: 5px 0;
        }
        .back-btn {
            background-color: #1E90FF;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }
        .bottom-nav {
            display: flex;
            justify-content: space-around;
            background: linear-gradient(90deg, #222, #444);
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
            .container {
                margin: 10px;
                padding: 10px;
            }
            .bottom-nav img {
                width: 20px;
                height: 20px;
            }
            .bottom-nav a {
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <img src="logo.png" alt="BacterFly Logo" class="logo-img">
            <h1><span class="logo">Welcome to BacterFly</span></h1>
        </header>

        <div class="detail-item">
            <p><strong>Judul:</strong> <?php echo htmlspecialchars($instruction['title']); ?></p>
            <p><strong>Divisi:</strong> <?php echo htmlspecialchars($instruction['division']); ?></p>
            <p><strong>Isi:</strong> <?php echo nl2br(htmlspecialchars($instruction['content'])); ?></p>
            <p><strong>Status:</strong> <?php echo ucfirst($instruction['status']); ?></p>
            <a href="javascript:history.back()" class="back-btn">Kembali</a>
        </div>
    </div>

    <div class="bottom-nav">
        <a href="manajer.php" class="active">
            <img src="images/home.png" alt="Home">
            <span>Home</span>
        </a>
        <a href="pengawasan.php">
            <img src="images/clock.png" alt="Clock">
            <span>Pengawasan</span>
        </a>
        <a href="list_manajer">
            <img src="images/list.png" alt="List">
            <span>List</span>
        </a>
        <a href="profil_manajer.php">
            <img src="images/profile.png" alt="Profile">
            <span>Profile</span>
        </a>
    </div>
</body>
</html>