<?php
require 'koneksi.php'; // koneksi ke database menggunakan PDO

// Pengecekan koneksi
if (!$pdo) {
    die("Koneksi gagal: Periksa pesan sebelumnya di log atau konfigurasi.");
}

// Ambil data pengguna manajer (opsional, berdasarkan id_user)
$id_user = 1; // Ganti dengan id pengguna yang sedang login
$query_user = "SELECT nama, division FROM users WHERE id = :id";
$stmt_user = $pdo->prepare($query_user);
$stmt_user->execute(['id' => $id_user]);
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);
$nama = $user ? $user['nama'] : 'Manajer';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pilih Laporan - BacterFly</title>
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
            height: 100vh;
            justify-content: space-between;
        }
        header {
            text-align: center;
            padding: 1rem;
            background: #000;
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
        .welcome-text {
            font-size: 14px;
            color: #FF8C42;
        }
        .division-buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-grow: 1;
            gap: 20px;
            padding: 20px;
        }
        .division-button {
            background-color: #FF8C42;
            color: #fff;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 10px;
            font-size: 18px;
            display: flex;
            align-items: center;
            width: 200px;
            justify-content: center;
            gap: 10px;
        }
        .division-button img {
            height: 24px;
        }
        .bottom-nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            background: linear-gradient(90deg, #222, #444); /* Gradien untuk latar belakang */
            display: flex;
            justify-content: space-around;
            padding: 10px 0;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.5); /* Bayangan halus */
            border-top: 1px solid #555; /* Garis pemisah */
        }

        .bottom-nav a {
            color: #fff;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 12px;
            transition: transform 0.2s, opacity 0.2s; /* Transisi halus */
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
            transform: scale(1.2); /* Efek pembesaran saat hover atau aktif */
        }

        .bottom-nav a.active {
            color: #FF8C42;
        }

        .bottom-nav a:hover {
            color: #FF8C42;
        }

        @media (max-width: 600px) {
            .division-button {
                width: 150px;
                font-size: 16px;
            }
            .division-button img {
                height: 20px;
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
    <header>
        <img src="logo.png" alt="BacterFly Logo" class="logo-img">
        <h1><span class="logo">Welcome to BacterFly</span></h1>
    </header>

    <div class="division-buttons">
        <a href="laporan_bakteri.php?division=Produksi" class="division-button">
            <img src="images/box.png" alt="Produksi Icon"> Produksi
        </a>
        <a href="Pihome.php?division=Laboratorium" class="division-button">
            <img src="images/microscope.png" alt="Inokulasi Icon"> Inokulasi
        </a>
    </div>

    <div class="bottom-nav">
        <a href="manajer.php">
            <img src="images/home.png" alt="Home">
            <span>Home</span>
        </a>
        <a href="pengawasan.php" class="active">
            <img src="images/timer.png" alt="Timer">
            <span>Pengawasan</span>
        </a>
        <a href="#">
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