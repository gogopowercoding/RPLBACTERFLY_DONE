<?php
require 'koneksi.php'; // koneksi ke database menggunakan PDO

// Pengecekan koneksi
if (!$pdo) {
    die("Koneksi gagal: Periksa pesan sebelumnya di log atau konfigurasi.");
}

// Ambil data dari tabel datainokulasi dengan kategori 'Peternakan' dan pengurutan berdasarkan inokulasi_id
$query = "SELECT inokulasi_id, Laboratorium_id, Manager_id, kategori, nama_bakteri, media, metode_inokulasi, tanggal_inokulasi, status_kualitas, jumlah_bakteri, tanggal_keluar, inokulasi_berhasil FROM datainokulasi WHERE kategori = 'Peternakan' ORDER BY inokulasi_id DESC";
$stmt = $pdo->prepare($query);

try {
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Inokulasi - BacterFly</title>
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
        .report-section {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
            padding-bottom: 70px; /* Sesuaikan dengan tinggi bottom-nav (60px + padding) */
        }
        /* Pencantikan Scrollbar */
        .report-section::-webkit-scrollbar {
            width: 10px;
        }
        .report-section::-webkit-scrollbar-track {
            background: #222;
            border-radius: 5px;
        }
        .report-section::-webkit-scrollbar-thumb {
            background: #FF8C42;
            border-radius: 5px;
        }
        .report-section::-webkit-scrollbar-thumb:hover {
            background: #FFA500;
        }
        /* Firefox */
        .report-section {
            scrollbar-width: thin;
            scrollbar-color: #FF8C42 #222;
        }
        .bacteria-item {
            background-color: #FF8C42;
            color: #000;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .bacteria-item img {
            height: 40px;
        }
        .bacteria-info {
            flex-grow: 1;
        }
        .bacteria-info p {
            margin: 5px 0;
            font-size: 14px;
        }
        .bottom-nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            background: linear-gradient(90deg, #222, #444);
            display: flex;
            justify-content: space-around;
            padding: 10px 0;
            height: 60px; /* Tinggi tetap untuk bottom-nav */
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
            .bacteria-item img {
                height: 30px;
            }
            .bacteria-info p {
                font-size: 12px;
            }
            .bottom-nav img {
                width: 20px;
                height: 20px;
            }
            .bottom-nav a {
                font-size: 10px;
            }
            .report-section {
                padding-bottom: 60px; /* Penyesuaian untuk layar kecil */
            }
        }
    </style>
</head>
<body>
    <header>
        <img src="logo.png" alt="BacterFly Logo" class="logo-img">
        <h1><span class="logo">Welcome to BacterFly</span></h1>
    </header>

    <div class="report-section">
        <h2>Inokulasi Peternakan</h2>
        <?php if (empty($results)): ?>
            <p>Tidak ada data inokulasi.</p>
        <?php else: ?>
            <?php foreach ($results as $row): ?>
                <div class="bacteria-item">
                    <img src="images/bacteria.png" alt="Bacteria Icon">
                    <div class="bacteria-info">
                        <p><strong>ID Inokulasi :</strong> <?= htmlspecialchars($row['inokulasi_id']) ?></p>
                        <p><strong>Kategori :</strong> <?= htmlspecialchars($row['kategori']) ?></p>
                        <p><strong>Nama Bakteri :</strong> <?= htmlspecialchars($row['nama_bakteri']) ?></p>
                        <p><strong>Media :</strong> <?= htmlspecialchars($row['media']) ?></p>
                        <p><strong>Metode Inokulasi :</strong> <?= htmlspecialchars($row['metode_inokulasi']) ?></p>
                        <p><strong>Tanggal Inokulasi :</strong> <?= htmlspecialchars($row['tanggal_inokulasi']) ?></p>
                        <p><strong>Status Kualitas :</strong> <?= htmlspecialchars($row['status_kualitas']) ?></p>
                        <p><strong>Jumlah Bakteri :</strong> <?= htmlspecialchars($row['jumlah_bakteri']) ?></p>
                        <p><strong>Tanggal Keluar :</strong> <?= htmlspecialchars($row['tanggal_keluar']) ?></p>
                        <p><strong>Inokulasi Berhasil :</strong> <?= htmlspecialchars($row['inokulasi_berhasil']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <div style="text-align: center; margin: 10px;">
        <button onclick="location.href='lab_tambahbakteri.php?kategori=Peternakan'" style="background:#FFA347; color:black; padding:10px 20px; border:none; border-radius:5px;">
        ➕
        </button>
    </div>
    </div>

    <div class="bottom-nav">
        <a href="lab_dashboard.php" class="active">
                <span>🏠</span>
                <span>Home</span>
            </a>
            <a href="lab_bakteri.php">
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
</body>
</html>