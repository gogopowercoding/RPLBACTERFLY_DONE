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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BacterFly - Dashboard Lab</title>
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

        main {
            flex: 1;
            padding: 16px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 16px;
            max-width: 600px;
            margin: 0 auto;
            justify-items: center;
        }

        .card {
            background-color: #222;
            color: #fff;
            padding: 20px;
            border-radius: 12px;
            width: 100%;
            max-width: 140px;
            text-align: center;
            border: 2px solid transparent;
            transition: border-color 0.3s ease, transform 0.2s ease;
            cursor: pointer;
        }

        .card:hover {
            border-color: #FF8C42;
            transform: translateY(-4px);
        }

        .card img {
            width: 48px;
            height: 48px;
            margin-bottom: 12px;
        }

        .card span {
            font-size: 0.9rem;
            font-weight: 400;
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

            main {
                padding: 12px;
            }

            .grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .card {
                max-width: 100%;
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

        <main>
            <div class="grid">
                <div class="card" onclick="location.href='inokulasi_peternakan.php'">
                    <img src="assets/cow-icon.png" alt="Peternakan" />
                    <span>Peternakan</span>
                </div>
                <div class="card" onclick="location.href='inokulasi_perikanan.php'">
                    <img src="assets/fish-icon.png" alt="Perikanan" />
                    <span>Perikanan</span>
                </div>
                <div class="card" onclick="location.href='inokulasi_pertanian.php'">
                    <img src="assets/plant-icon.png" alt="Pertanian" />
                    <span>Pertanian</span>
                </div>
            </div>
        </main>

        <div class="bottom-nav">
            <a href="lab_dashboard.php">
                <span>🏠</span>
                <span>Home</span>
            </a>
            <a href="lab_bakteri.php" class="active">
                <span>🕒</span>
                <span>Data</span>
            </a>
            <a href="lab_intruksi.php">
                <span>📋</span>
                <span>Instruksi</span>
            </a>
            <a href="lab_profil.php">
                <span>👤</span>
                <span>Profil</span>
            </a>
        </div>
    </div>
</body>
</html>