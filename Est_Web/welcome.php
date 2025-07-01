<?php
session_start();
if (!isset($_SESSION['correo']) || !isset($_SESSION['nombre'])) {
    header("Location: index.php");
    exit();
}

// Variables de sesión o por defecto
$nombre = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Invitado';
$correo = isset($_SESSION['correo']) ? $_SESSION['correo'] : 'No disponible';
$telefono = isset($_SESSION['telefono']) ? $_SESSION['telefono'] : 'No disponible';
$vehiculo = isset($_SESSION['vehiculo']) ? $_SESSION['vehiculo'] : 'No disponible';
$placas = isset($_SESSION['placas']) ? $_SESSION['placas'] : 'No disponible';
$rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : 'usuario';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Smart Parking</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #ccc;
    }

    body.menu-open {
      overflow: hidden;
    }

    nav {
      width: 100%;
      background-color: rgb(188, 45, 45);
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: fixed;
      top: 0;
      left: 0;
      z-index: 1000;
    }

    nav h1 {
      color: white;
      margin: 0;
      font-size: 24px;
    }

    .icon-btn {
      background: none;
      border: none;
      cursor: pointer;
      padding: 0;
      width: 32px;
      height: 32px;
    }

    .icon-img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      display: block;
    }

    .side-menu-left,
    .side-menu-right {
      width: 300px;
      height: 100%;
      position: fixed;
      top: 0;
      background-color: #fff;
      z-index: 999;
      padding-top: 70px;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
      transition: transform 0.3s ease-in-out;
    }

    .side-menu-left {
      left: 0;
      transform: translateX(-100%);
    }

    .side-menu-right {
      right: 0;
      transform: translateX(100%);
      box-shadow: -2px 0 5px rgba(0, 0, 0, 0.2);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .side-menu-left.open,
    .side-menu-right.open {
      transform: translateX(0);
    }

    .side-menu-left h2,
    .side-menu-left p,
    .side-menu-right .info p {
      padding: 10px 20px;
      margin: 0;
    }

    .side-menu-left .links a {
      display: block;
      padding: 10px 20px;
      text-decoration: none;
      color: #222;
      font-weight: bold;
      border-bottom: 1px solid #eee;
    }

    .side-menu-left .links a:hover {
      background-color: #f0f0f0;
    }

    .side-menu-right .info {
      padding: 20px;
      flex-grow: 1;
    }

    .logout {
      background-color: rgb(188, 45, 45);
      color: white;
      text-align: center;
      padding: 15px;
      font-weight: bold;
      cursor: pointer;
    }

    .overlay {
      position: fixed;
      display: none;
      top: 0;
      left: 0;
      height: 100%;
      width: 100%;
      background: rgba(0, 0, 0, 0.3);
      z-index: 998;
    }

    main#mainContent {
      padding-top: 80px;
      position: relative;
      z-index: 1;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(5, 100px);
      gap: 10px;
      justify-content: center;
      padding: 20px;
    }

    .cajon {
      width: 100px;
      height: 100px;
      text-align: center;
      line-height: 1.2;
      padding-top: 25px;
      font-size: 14px;
      font-weight: bold;
      border-radius: 8px;
      color: white;
      cursor: pointer;
      border: none;
    }

    .libre { background-color: green; }
    .ocupado { background-color: red; }
    .discapacitado { border: 3px dashed yellow; }
  </style>
</head>
<body>

<!-- NAV -->
<nav>
  <button class="icon-btn" onclick="toggleLeftMenu()">
    <img src="https://img.icons8.com/ios-filled/50/ffffff/menu--v1.png" class="icon-img" alt="Menú" />
  </button>
  <h1>Smart Parking</h1>
  <button class="icon-btn" onclick="toggleRightMenu()">
    <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" class="icon-img" alt="Usuario" />
  </button>
</nav>

<!-- CONTENIDO -->
<main id="mainContent">
  <!-- Mapa de espacios (esto carga el diseño tipo cuadrícula) -->
  <div class="grid">
    <?php
    require 'db.php';
    $query = "SELECT * FROM espacios ORDER BY numero_cajon";
    $resultado = $conn->query($query);
    while ($cajon = $resultado->fetch_assoc()):
      $clase = $cajon['estado'];
      if ($cajon['tipo'] === 'discapacitado') $clase .= ' discapacitado';
    ?>
      <div class="cajon <?= $clase ?>">
        <?= $cajon['numero_cajon'] ?><br><?= strtoupper($cajon['estado']) ?>
      </div>
    <?php endwhile; ?>
  </div>
</main>

<!-- MENÚ IZQUIERDO -->
<div id="leftMenu" class="side-menu-left">
  <h2>Bienvenido, <?= htmlspecialchars($nombre) ?> 👋</h2>
  <div class="links">
    <?php if ($rol === 'admin'): ?>
      <a href="admin_usuarios.php">👤 Panel de Usuarios</a>
      <a href="admin_espacios.php">🅿️ Panel de Espacios</a>
    <?php endif; ?>
    <a href="logout.php">🔒 Cerrar sesión</a>
  </div>
</div>

<!-- MENÚ DERECHO -->
<div id="rightMenu" class="side-menu-right">
  <div class="info">
    <p><strong>Nombre:</strong> <?= htmlspecialchars($nombre) ?></p>
    <p><strong>Correo:</strong> <?= htmlspecialchars($correo) ?></p>
    <p><strong>Teléfono:</strong> <?= htmlspecialchars($telefono) ?></p>
    <p><strong>Placas:</strong> <?= htmlspecialchars($placas) ?></p>
    <p><strong>Vehículo:</strong> <?= htmlspecialchars($vehiculo) ?></p>
  </div>
  <div class="logout" onclick="cerrarSesion()">Cerrar sesión</div>
</div>

<!-- FONDO OSCURO -->
<div id="overlay" class="overlay" onclick="closeMenus()"></div>

<!-- JS MENÚS -->
<script>
  function toggleLeftMenu() {
    document.getElementById("leftMenu").classList.toggle("open");
    document.getElementById("rightMenu").classList.remove("open");
    document.getElementById("overlay").style.display = "block";
  }

  function toggleRightMenu() {
    document.getElementById("rightMenu").classList.toggle("open");
    document.getElementById("leftMenu").classList.remove("open");
    document.getElementById("overlay").style.display = "block";
  }

  function closeMenus() {
    document.getElementById("leftMenu").classList.remove("open");
    document.getElementById("rightMenu").classList.remove("open");
    document.getElementById("overlay").style.display = "none";
  }

  function cerrarSesion() {
    window.location.href = "logout.php";
  }
</script>

</body>
</html>
