<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BacterFly - Dashboard Lab</title>
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      margin: 0;
      background-color: #000;
      color: #FFA347;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    header {
      background-color: #111;
      padding: 16px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #FFA347;
    }

    header .logo-text {
      font-size: 1rem;
      font-weight: bold;
      color: #FFA347;
    }

    header .lab-name {
      font-size: 0.9rem;
      color: white;
    }

    main {
      flex: 1;
      padding: 20px 16px;
    }

    .grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
      justify-items: center;
    }

    .card {
      background-color: #222;
      color: white;
      padding: 20px;
      border-radius: 12px;
      width: 100%;
      max-width: 140px;
      text-align: center;
      border: 2px solid transparent;
      transition: border 0.3s ease;
      cursor: pointer;
    }

    .card:hover {
      border-color: #FFA347;
    }

    .card img {
      width: 48px;
      height: 48px;
      margin-bottom: 10px;
    }

    .card span {
      display: block;
      font-size: 0.9rem;
    }

    nav {
      display: flex;
      justify-content: space-around;
      background-color: #111;
      padding: 12px 0;
      border-top: 1px solid #FFA347;
    }

    nav a {
      color: #FFA347;
      text-decoration: none;
      font-size: 1.4rem;
    }

    nav a.active {
      color: white;
    }
  </style>
</head>
<body>
  <header>
    <div class="logo-text">Welcome to <span style="color:#fff">Bacter</span>Fly</div>
    <div class="lab-name">Lab</div>
  </header>

  <main>
    <div class="grid">
      <div class="card" onclick="location.href='peternakan.php'">
        <img src="assets/cow-icon.png" alt="Peternakan" />
        <span>Peternakan</span>
      </div>
      <div class="card" onclick="location.href='perikanan.php'">
        <img src="assets/fish-icon.png" alt="Perikanan" />
        <span>Perikanan</span>
      </div>
      <div class="card" onclick="location.href='pertanian.php'">
        <img src="assets/plant-icon.png" alt="Pertanian" />
        <span>Pertanian</span>
      </div>
    </div>
  </main>

  <nav>
    <a href="Pidashboard.php" >🏠</a>
    <a href="Pihome.php "class="active">🕒</a>
    <a href="Piinstruksi.php">📋</a>
    <a href="Piprofile.php">👤</a>
  </nav>
</body>
</html>
