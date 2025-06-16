<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit();
}

// Tangkap nilai kategori dari URL, default ke 'Peternakan' jika tidak ada
$kategori = isset($_GET['kategori']) ? htmlspecialchars($_GET['kategori']) : 'Peternakan';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tambah Bakteri - BacterFly</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body { background: #000; color: #FFA347; font-family: 'Segoe UI'; margin: 0; padding: 20px; }
    form { background: #111; padding: 20px; border-radius: 10px; }
    input, select { width: 100%; padding: 10px; margin: 10px 0; background: #222; border: 1px solid #FFA347; color: white; border-radius: 5px; }
    button { background: #FFA347; color: #000; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
    a { color: #FFA347; display: inline-block; margin-top: 15px; }
  </style>
</head>
<body>
  <h2>Tambah Data Bakteri - <?= $kategori ?></h2>

  <form method="POST" action="lab_prosesTB.php">
    <input type="hidden" name="kategori" value="<?= $kategori ?>">

    <label>Nama Bakteri</label>
    <input type="text" name="nama_bakteri" required>

    <label>Media</label>
    <select name="media" required>
      <option value="NA">NA</option>
      <option value="TSA">TSA</option>
      <option value="MRSA">MRSA</option>
      <option value="PDA">PDA</option>
    </select>

    <label>Metode Inokulasi</label>
    <input type="text" name="metode_inokulasi" required>

    <label>Tanggal Inokulasi</label>
    <input type="date" name="tanggal_inokulasi" required>

    <label>Status Kualitas</label>
    <select name="status_kualitas" required>
      <option value="proses">proses</option>
      <option value="baik">baik</option>
      <option value="gagal">gagal</option>
    </select>

    <label>Jumlah Bakteri</label>
    <input type="text" name="jumlah_bakteri">

    <label>Tanggal Keluar</label>
    <input type="date" name="tanggal_keluar">

    <label>Inokulasi Berhasil</label>
    <select name="inokulasi_berhasil" required>
      <option value="belum">belum</option>
      <option value="ya">ya</option>
      <option value="tidak">tidak</option>
    </select>

    <button type="submit">Simpan</button>
  </form>

  <a href="lab_<?= strtolower($kategori) ?>.php">← Kembali</a>
</body>
</html>
