<?php
require 'koneksi.php'; // koneksi ke database menggunakan PDO

if (!$pdo) {
    die("Koneksi gagal: Periksa pesan sebelumnya di log atau konfigurasi.");
}

$query = "SELECT * FROM instructions ORDER BY id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        .reminder {
            background-color: #800080;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin: 0 10px 20px;
        }
        .instructions-list {
            margin-bottom: 20px;
            padding: 0 10px;
        }
        .instruction-item {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .instruction-item p {
            margin: 5px 0;
            flex-grow: 1;
        }
        .instruction-status {
            margin-left: 10px;
        }
        .instruction-status.done {
            /* Tidak ada perubahan warna atau garis coret */
        }
        .actions a {
            color: #fff;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            margin-left: 10px;
        }
        .delete {
            background-color: #FF4500;
        }
        .done {
            background-color: #32CD32;
        }
        .done.completed {
            background-color: #808080;
            cursor: not-allowed;
            text-decoration: line-through;
        }
        .add-instruction {
            display: none;
            padding: 0 10px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, textarea, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
        }
        .submit {
            background-color: #800080;
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
        }
        .add-button {
            text-align: center;
            margin: 20px 0;
        }
        .add-button a {
            font-size: 30px;
            color: #FFA500;
            text-decoration: none;
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
            .container {
                padding: 10px;
            }
            .add-button a {
                font-size: 24px;
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
        <header class="top-bar">
            <div class="logo"> 
                <img src="logo.png" alt="BacterFly Logo">
                <span>Welcome To <strong>BacterFly</strong></span>
            </div>
        </header>

        <h2 style="padding: 0 10px;">Instruksi</h2>

        <!-- Notifikasi jika tidak ada instruksi -->
        <?php if (empty($result)): ?>
            <div class="reminder">
                Pesan !! Tidak ada instruksi yang diberikan!!
            </div>
        <?php endif; ?>

        <!-- Daftar Instruksi -->
        <div class="instructions-list">
            <?php foreach ($result as $row): ?>
                <div class="instruction-item">
                    <p><?php echo htmlspecialchars($row['title'] . ' (' . $row['division'] . ')'); ?>
                        <span class="instruction-status <?= $row['status'] === 'done' ? 'done' : '' ?>">
                            [Status: <?php echo htmlspecialchars($row['status'] ?? 'pending'); ?>]
                        </span>
                    </p>
                    <div class="actions">
                        <a href="detail.php?id=<?php echo $row['id']; ?>" class="detail">Lihat Detail</a>
                        <a href="proses.php?action=delete&id=<?php echo $row['id']; ?>" class="delete">Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Formulir Tambah Instruksi -->
        <div class="add-instruction" id="addForm">
            <form action="proses.php" method="POST">
                <div class="form-group">
                    <label for="division">Divisi:</label>
                    <select id="division" name="division" required>
                        <option value="">Pilih Divisi</option>
                        <option value="Produksi">Produksi</option>
                        <option value="Laboratorium">Laboratorium</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="title">Judul Instruksi:</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="content">Isi Instruksi:</label>
                    <textarea id="content" name="content" required></textarea>
                </div>
                <button type="submit" name="action" value="add" class="submit">Submit</button>
            </form>
        </div>

        <!-- Tombol Tambah Instruksi -->
        <div class="add-button">
            <a href="tambah_intruksi.php" onclick="document.getElementById('addForm').style.display = 'block'; this.style.display = 'none';">+</a>
        </div>
    </div>

    <div class="bottom-nav">
        <a href="manajer.php">
            <img src="images/home.png" alt="Home">
            <span>Home</span>
        </a>
        <a href="pengawasan.php">
            <img src="images/timer.png" alt="Timer">
            <span>Pengawasan</span>
        </a>
        <a href="list_manajer.php" class="active">
            <img src="images/list.png" alt="List">
            <span>List</span>
        </a>
        <a href="profil_manajer.php">
            <img src="images/profile.png" alt="Profile">
            <span>Profile</span>
        </a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addButton = document.querySelector('.add-button a');
            const addForm = document.getElementById('addForm');
            addButton.addEventListener('click', function(e) {
                e.preventDefault();
                addForm.style.display = 'block';
                this.style.display = 'none';
            });
        });
    </script>
</body>
</html>