```php
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

// Ambil data pengguna untuk divisi
$id_user = $_SESSION['id'];
$query_user = "SELECT nama, division FROM users WHERE id = :id";
$stmt_user = $pdo->prepare($query_user);

try {
    $stmt_user->execute(['id' => $id_user]);
    $row_user = $stmt_user->fetch(PDO::FETCH_ASSOC);

    if ($row_user) {
        $nama = $row_user['nama'] ?? 'Tidak Ditemukan';
        $divisi = $row_user['division'] ?? '-';
    } else {
        $divisi = '-';
    }
} catch (PDOException $e) {
    $divisi = 'Error: ' . $e->getMessage();
}

// Ambil semua instruksi
$query = "SELECT * FROM instructions ORDER BY id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajer - BacterFly</title>
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
            /* Tidak ada garis coret */
        }

        .actions a {
            color: #fff;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            margin-left: 10px;
        }

        .detail {
            background-color: #FFA500;
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
            main {
                padding: 12px;
            }

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

            .add-button a {
                font-size: 24px;
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
                            <a href="proses.php?action=mark_done&id=<?php echo $row['id']; ?>" class="done <?= $row['status'] === 'done' ? 'completed' : '' ?>">Mark as Done</a>
                            <a href="proses.php?action=delete&id=<?php echo $row['id']; ?>" class="delete">Delete</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>

    <div class="bottom-nav">
        <a href="lab_dashboard.php">
            <span>🏠</span>
            <span>Home</span>
        </a>
        <a href="lab_bakteri.php">
            <span>🕒</span>
            <span>Data</span>
        </a>
        <a href="lab_intruksi.php" class="active">
            <span>📋</span>
            <span>Instruksi</span>
        </a>
        <a href="profil_manajer.php">
            <span>👤</span>
            <span>Profil</span>
        </a>
    </div>
</body>
</html>