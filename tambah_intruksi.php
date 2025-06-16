<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Instruksi - BacterFly</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background-color: #000;
            color: #FFA347;
            font-family: 'Segoe UI', sans-serif;
            padding: 1rem;
            background-image: url('images/virus.png');
            background-repeat: no-repeat;
            background-position: right center;
            background-size: 40%;
        }
        h2 {
            color: #FFA500;
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            max-width: 400px;
            margin: 0 auto;
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
        button {
            background-color: #800080;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #6A0DAD;
        }
        .back-link {
            color: #FFA500;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2>Form Tambah Instruksi</h2>
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
            <textarea id="content" name="content" required rows="4"></textarea>
        </div>
        <button type="submit" name="action" value="add">Submit</button>
    </form>
    <a href="manajer.php" class="back-link">← Kembali</a>
</body>
</html>