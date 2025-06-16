<?php
session_start();

require 'koneksi.php'; // koneksi ke database menggunakan PDO

// Pemeriksaan sesi
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['id'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit();
}

// Inisialisasi variabel
$nama = '';
$divisi = '';
$message;

// Ambil ID pengguna dari sesi
$id_user = $_SESSION['id'];

// Ambil data pengguna saat ini
$query = "SELECT nama, division FROM users WHERE id = :id";
$stmt = $pdo->prepare($query);

try {
    $stmt->execute(['id' => $id_user]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $nama = $row['nama'] ?? 'Tidak Ditemukan';
        $divisi = $row['division'] ?? '-';
    } else {
        $nama = 'Tidak Ditemukan';
        $divisi = '-';
    }
} catch (PDOException $e) {
    $message = "Error: " . $e->getMessage();
}

// Proses pembaruan data jika formulir disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_nama = $_POST['nama'] ?? '';
    $new_divisi = $_POST['division'] ?? '';

    // Validasi input
    if (empty($new_nama)) {
        $message = "Nama tidak boleh kosong.";
    } else {
        // Update data di database
        $update_query = "UPDATE users SET nama = :nama, division = :division WHERE id = :id";
        $update_stmt = $pdo->prepare($update_query);

        try {
            $update_stmt->execute([
                'nama' => $new_nama,
                'division' => $new_divisi,
                'id' => $id_user
            ]);
            $message = "Profil berhasil diperbarui!";
            // Perbarui variabel untuk menampilkan data baru
            $nama = $new_nama;
            $divisi = $new_divisi;
        } catch (PDOException $e) {
            $message = "Error saat memperbarui profil: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - BacterFly</title>
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

        .profile-container {
            text-align: center;
            margin-top: 40px;
            width: 80%;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .avatar img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #666;
        }

        .form-container {
            margin-top: 20px;
        }

        .form-container label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
            font-size: 18px;
        }

        .form-container input {
            width: 100%;
            padding: 8px;
            font-size: 16px;
            font-family: 'Courier New', monospace;
            background-color: #222;
            color: white;
            border: 1px solid #555;
            border-radius: 5px;
        }

        .form-container input:focus {
            outline: none;
            border-color: #FF8C42;
        }

        .form-container button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #FF8C42;
            color: #000;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .form-container button:hover {
            background-color: #FFA500;
        }

        .message {
            margin: 10px 0;
            font-size: 16px;
            color: #FF8C42;
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

            .profile-container {
                width: 90%;
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

        <main class="profile-container">
            <div class="avatar">
                <img src="images/profile-icon.png" alt="Profile Picture">
            </div>
            <h2 class="username"><?= htmlspecialchars($nama) ?></h2>

            <div class="form-container">
                <?php if (!empty($message)): ?>
                    <p class="message"><?= htmlspecialchars($message) ?></p>
                <?php endif; ?>
                <form method="POST" action="edit_profil.php">
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($nama) ?>" required>
                    <label for="division">Divisi</label>
                    <input type="text" id="division" name="division" value="<?= htmlspecialchars($divisi) ?>">
                    <button type="submit">Simpan</button>
                </form>
            </div>
        </main>

        <div class="bottom-nav">
            <a href="lab_dashboard.php">
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
            <a href="lab_profil.php" class="active">
                <span>👤</span>
                <span>Profil</span>
            </a>
        </div>
    </div>
</body>
</html>