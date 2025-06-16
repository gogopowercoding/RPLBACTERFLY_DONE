<?php
session_start(); // Mulai sesi jika belum dimulai

// Hapus semua data sesi
session_unset();
session_destroy();

// Alihkan ke halaman login
header("Location: login.php"); // Ganti "login.php" dengan halaman login Anda
exit();
?>