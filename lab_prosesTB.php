<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit();
}


// Ambil ID user dari session
$Laboratorium_id = $_SESSION['id']; // Asumsi ID user disimpan di $_SESSION['id']
$Manager_id = 9; // Sesuaikan kalau manajer beda user, atau ambil dari session jika dinamis
$kategori = isset($_POST['kategori']) ? trim($_POST['kategori']) : '';
$nama_bakteri = isset($_POST['nama_bakteri']) ? trim($_POST['nama_bakteri']) : '';
$media = isset($_POST['media']) ? trim($_POST['media']) : '';
$metode_inokulasi = isset($_POST['metode_inokulasi']) ? trim($_POST['metode_inokulasi']) : '';
$tanggal_inokulasi = isset($_POST['tanggal_inokulasi']) ? trim($_POST['tanggal_inokulasi']) : '';
$status_kualitas = isset($_POST['status_kualitas']) ? trim($_POST['status_kualitas']) : '';
$jumlah_bakteri = isset($_POST['jumlah_bakteri']) ? trim($_POST['jumlah_bakteri']) : '';
$tanggal_keluar = isset($_POST['tanggal_keluar']) ? trim($_POST['tanggal_keluar']) : '';
$inokulasi_berhasil = isset($_POST['inokulasi_berhasil']) ? trim($_POST['inokulasi_berhasil']) : '';

// Validasi kategori
$valid_kategori = ['Peternakan', 'Pertanian','Perikanan']; // Sesuaikan dengan kategori yang valid
if (!in_array($kategori, $valid_kategori)) {
    die("Kategori tidak valid.");
}

// Validasi enum media
$valid_media = ['NA', 'TSA', 'MRSA', 'PDA'];
if (!in_array($media, $valid_media)) {
    die("Media tidak valid.");
}

// Validasi status dan hasil
$valid_status = ['proses', 'baik', 'gagal'];
$valid_hasil = ['belum', 'ya', 'tidak'];
if (!in_array($status_kualitas, $valid_status) || !in_array($inokulasi_berhasil, $valid_hasil)) {
    die("Status atau hasil inokulasi tidak valid.");
}

// Validasi tanggal
if (!empty($tanggal_inokulasi) && !DateTime::createFromFormat('Y-m-d', $tanggal_inokulasi)) {
    die("Format tanggal inokulasi tidak valid.");
}
if (!empty($tanggal_keluar) && !DateTime::createFromFormat('Y-m-d', $tanggal_keluar)) {
    die("Format tanggal keluar tidak valid.");
}

// Query insert
$query = "INSERT INTO DataInokulasi (
    Laboratorium_id, Manager_id, kategori,
    nama_bakteri, media, metode_inokulasi,
    tanggal_inokulasi, status_kualitas,
    jumlah_bakteri, tanggal_keluar, inokulasi_berhasil
) VALUES (
    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
)";

try {
    $stmt = $pdo->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . implode(", ", $pdo->errorInfo()));
    }

    // Bind parameters
    $stmt->execute([
        $Laboratorium_id,
        $Manager_id,
        $kategori,
        $nama_bakteri,
        $media,
        $metode_inokulasi,
        $tanggal_inokulasi,
        $status_kualitas,
        $jumlah_bakteri,
        $tanggal_keluar,
        $inokulasi_berhasil
    ]);

    // Redirect based on kategori
    $redirect_page = strtolower($kategori) . '.php';
    header("Location: $redirect_page");
    exit();
} catch (PDOException $e) {
    echo "Gagal menyimpan data: " . $e->getMessage();
}
?>