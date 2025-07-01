<?php
session_start();
require 'db.php';

// Solo admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: welcome.php");
    exit();
}

// Actualizar rol
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cambiar_rol'])) {
    $id = intval($_POST['usuario_id']);
    $nuevo_rol = $_POST['nuevo_rol'];
    $sql = "UPDATE usuarios SET rol = '$nuevo_rol' WHERE id = $id";
    $conn->query($sql);
    header("Location: admin_usuarios.php");
    exit();
}

// Eliminar usuario
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conn->query("DELETE FROM usuarios WHERE id = $id");
    header("Location: admin_usuarios.php");
    exit();
}

$resultado = $conn->query("SELECT id, nombre, correo, telefono, tipo_vehiculo, placas, discapacidad, rol FROM usuarios ORDER BY id");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Usuarios</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-container {
            margin-top: 150px;
            width: 90%;
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            border: 1px solid #999;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #444;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f4f4f4;
        }

        .acciones form {
            display: inline;
        }

        .volver {
            display: block;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>

<nav>
    <h1>Panel de Administración – Usuarios</h1>
</nav>

<div class="admin-container">
    <h2>Usuarios Registrados</h2>

    <table>
        <tr>
            <th>ID</th><th>Nombre</th><th>Correo</th><th>Teléfono</th>
            <th>Vehículo</th><th>Placas</th><th>Discapacidad</th><th>Rol</th><th>Acciones</th>
        </tr>
        <?php while ($row = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><?= htmlspecialchars($row['correo']) ?></td>
                <td><?= htmlspecialchars($row['telefono']) ?></td>
                <td><?= $row['tipo_vehiculo'] ?></td>
                <td><?= $row['placas'] ?></td>
                <td><?= $row['discapacidad'] ? 'Sí' : 'No' ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="usuario_id" value="<?= $row['id'] ?>">
                        <select name="nuevo_rol" onchange="this.form.submit()">
                            <option value="usuario" <?= $row['rol'] === 'usuario' ? 'selected' : '' ?>>Usuario</option>
                            <option value="admin" <?= $row['rol'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select>
                        <input type="hidden" name="cambiar_rol" value="1">
                    </form>
                </td>
                <td class="acciones">
                    <a href="?eliminar=<?= $row['id'] ?>" onclick="return confirm('¿Eliminar este usuario?')">🗑️</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <a class="volver" href="welcome.php">← Volver al inicio</a>
</div>

</body>
</html>
