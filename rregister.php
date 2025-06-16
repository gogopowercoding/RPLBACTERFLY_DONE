<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a1a;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            box-sizing: border-box;
        }
        .form-group input[type="checkbox"] {
            width: auto;
            margin-right: 10px;
        }
        .code-group {
            display: flex;
            gap: 10px;
        }
        .code-group input {
            flex: 1;
        }
        .code-group button {
            padding: 10px 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            cursor: pointer;
        }
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            box-sizing: border-box;
            appearance: none; /* Menghapus gaya bawaan browser */
            -webkit-appearance: none; /* Untuk browser berbasis WebKit */
            -moz-appearance: none; /* Untuk Firefox */
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23fff' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
        }
        .signup-btn {
            width: 100%;
            padding: 10px;
            background-color: #ff6200;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        .links {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
        }
        .links a {
            color: #ff6200;
            text-decoration: none;
        }
        .message {
            text-align: center;
            margin-bottom: 15px;
            color: #ff6200;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        session_start();
        if (isset($_SESSION['pesan'])) {
            echo '<div class="message">' . $_SESSION['pesan'] . '</div>';
            unset($_SESSION['pesan']);
        }
        ?>
        <p style="text-align: center;">Only email registration is supported in your office</p>

        <!-- Formulir pendaftaran -->
        <form method="POST" action="register.php">
            <div class="form-group">
                <input type="email" name="email" placeholder="Email Address" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            </div>
            <div class="form-group code-group">
                <input type="text" name="code" placeholder="Code">
                <button type="submit" name="send_code">Send Code</button>
            </div>
            <div class="form-group">
                <label for="division">Pilih Divisi:</label>
                <select name="division" id="division" required>
                    <option value="">Pilih Divisi</option>
                    <option value="Laboratorium">Laboratorium</option>
                    <option value="Produksi">Produksi</option>
                    <option value="Manager">Manager</option>
                </select>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" required>
                    I confirm that i have read, consent and agree to Bacterfly's Terms of Use and Privacy Policy.
                </label>
            </div>
            <button type="submit" name="signup" class="signup-btn">REGISTER</button>
            <div class="links">
                <a href="https://mail.google.com/mail/u/0/?fs=1&to=bacterfly@gmail.com&su=Kendala+Registrasi+Pegawai&body=Halo+Tim+Support,%0A%0ASaya+adalah+pegawai+baru+di+perusahaan+ini+dan+mengalami+kendala+saat+mencoba+melakukan+pendaftaran/membuat+akun.%0A%0ABerikut+detail+kendala+yang+saya+alami:%0A-+Nama+:%0A-+Email+pegawai+yang+ingin+didaftarkan:%0A-+Deskripsi+kendala:%0A%0AMohon+bantuannya.+Terima+kasih.&tf=cm">Call Us</a>
                <a href="login.php">Log In</a>
            </div>
        </form>
    </div>
</body>
</html>