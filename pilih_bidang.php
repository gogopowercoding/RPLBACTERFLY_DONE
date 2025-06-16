<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !in_array($_SESSION['division'], ['Laboratorium', 'Produksi'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['field'])) {
    $field = $_GET['field'];
    $_SESSION['selected_field'] = $field; // Simpan bidang yang dipilih di sesi
    $division = $_SESSION['division'];

    // Tentukan halaman tujuan berdasarkan divisi dan bidang
    $page = '';
    switch ($division) {
        case 'Laboratorium':
            $page = "lab_{$field}.php";
            break;
        case 'Produksi':
            $page = "produksi_{$field}.php";
            break;
    }
    header("Location: " . $page);
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Bidang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a1a;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"%3E%3Ccircle cx="50" cy="50" r="40" fill="%23a67b5b" stroke="%23a67b5b" stroke-width="2" fill-opacity="0.5"/%3E%3Cpath d="M30 70 Q50 30 70 70" stroke="%23a67b5b" stroke-width="2" fill="none"/%3E%3C/svg%3E');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 200px;
        }
        .container {
            text-align: center;
            padding-top: 20px;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .button-group {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .field-button {
            width: 100px;
            height: 100px;
            background-color: #ff6200;
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }
        .field-button svg {
            width: 40px;
            height: 40px;
            margin-bottom: 10px;
        }
        .nav {
            position: fixed;
            bottom: 20px;
            display: flex;
            justify-content: space-around;
            width: 80%;
            background-color: #333;
            padding: 10px;
            border-radius: 10px;
        }
        .nav a {
            color: #fff;
            text-decoration: none;
            font-size: 14px;
        }
        .nav a.active {
            color: #ff6200;
        }
    </style>
</head>
<body>