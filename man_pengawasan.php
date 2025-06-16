<?php
session_start();

require 'koneksi.php'; // koneksi ke database menggunakan PDO

// Pemeriksaan sesi
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['id'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit();
}

// Pengecekan koneksi
if (!$pdo) {
    die("Koneksi gagal: Periksa pesan sebelumnya di log atau konfigurasi.");
}

// Ambil data pengguna dari sesi
$id_user = $_SESSION['id'];
$query_user = "SELECT nama, division FROM users WHERE id = :id";
$stmt_user = $pdo->prepare($query_user);

try {
    $stmt_user->execute(['id' => $id_user]);
    $user = $stmt_user->fetch(PDO::FETCH_ASSOC);
    $nama = $user ? $user['nama'] : 'Pengguna';
    $divisi = $user ? $user['division'] : '-';
} catch (PDOException $e) {
    $nama = 'Pengguna';
    $divisi = 'Error: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Laporan - BacterFly</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #000;
            color: #fff;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-size: 16px;
        }

        /* Header styling */
        .top-bar {
            padding: 10px;
            background-color: #000;
            border-bottom: 1px solid #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FF8C42;
            font-size: 14px;
            font-family: 'Courier New', monospace;
        }

        .logo span strong {
            color: #fff;
        }

        .division {
            margin-top: 5px;
            font-size: 14px;
            color: #fff;
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

        /* Navigasi bawah styling */
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
            transition: transform 0.2s, opacity 0.2s, color 0.2s;
        }

        .bottom-nav a span {
            margin-top: 2px;
        }

        .bottom-nav a:hover,
        .bottom-nav a.active {
            color: #FF8C42;
        }

        .bottom-nav a:hover span,
        .bottom-nav a.active span {
            transform: scale(1.2);
        }

        /* Responsif untuk layar kecil */
        @media (max-width: 600px) {
            .top-bar {
                padding: 8px;
            }

            .logo {
                font-size: 12px;
            }

            .division {
                font-size: 12px;
            }

            .logout-btn {
                padding: 6px 12px;
                font-size: 12px;
                top: 12px;
                right: 12px;
            }

            .division-button {
                width: 150px;
                font-size: 16px;
            }

            .division-button img {
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
        <header class="top-bar">
            <div class="logo">
                <span>Welcome To <strong>Bacter</strong>Fly</span>
            </div>
            <div class="division"><?= htmlspecialchars($divisi) ?></div>
            <a href="proses.php?logout=true">
                <button type="button" class="logout-btn">Logout</button>
            </a>
        </header>

        <div class="division-buttons">
            <a href="laporan_bakteri.php?division=Produksi" class="division-button">
                <img src="images/box.png" alt="Produksi Icon"> Produksi
            </a>
            <a href="lab_bakteri.php?division=Laboratorium" class="division-button">
                <img src="images/microscope.png" alt="Inokulasi Icon"> Inokulasi
            </a>
        </div>

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
    </div>
</body>
</html>
