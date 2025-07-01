<?php
session_start();
require 'db.php';

// Solo admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: welcome.php");
    exit();
}

// Agregar espacio
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['agregar'])) {
    $numero = $_POST['numero_cajon'];
    $estado = $_POST['estado'];
    $tipo = $_POST['tipo'];
    $conn->query("INSERT INTO espacios (numero_cajon, estado, tipo) VALUES ('$numero', '$estado', '$tipo')");
    header("Location: admin_espacios.php");
    exit();
}

// Eliminar espacio
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conn->query("DELETE FROM espacios WHERE id = $id");
    header("Location: admin_espacios.php");
    exit();
}

// Editar espacio
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['editar'])) {
    $id = intval($_POST['espacio_id']);
    $numero = $_POST['numero_cajon'];
    $estado = $_POST['estado'];
    $tipo = $_POST['tipo'];
    $conn->query("UPDATE espacios SET numero_cajon='$numero', estado='$estado', tipo='$tipo' WHERE id=$id");
    header("Location: admin_espacios.php");
    exit();
}

// Obtener espacios
$espacios = $conn->query("SELECT * FROM espacios ORDER BY numero_cajon");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Espacios</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav>
    <h1>Administrar Espacios</h1>
</nav>

<div class="admin-container">
    <h2>Agregar Nuevo Cajón</h2>
    <form method="POST" class="form-agregar">
        <input type="text" name="numero_cajon" placeholder="Ej. A6" required>
        <select name="estado">
            <option value="libre">Libre</option>
            <option value="ocupado">Ocupado</option>
        </select>
        <select name="tipo">
            <option value="normal">Normal</option>
            <option value="discapacitado">Discapacitado</option>
        </select>
        <input type="submit" name="agregar" value="Agregar">
    </form>

    <h2>Cajones Existentes</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Cajón</th>
            <th>Estado</th>
            <th>Tipo</th>
            <th>Acciones</th>
        </tr>
        <?php while ($espacio = $espacios->fetch_assoc()): ?>
            <tr>
                <form method="POST">
                    <input type="hidden" name="espacio_id" value="<?= $espacio['id'] ?>">
                    <td><?= $espacio['id'] ?></td>
                    <td><input type="text" name="numero_cajon" value="<?= $espacio['numero_cajon'] ?>" required></td>
                    <td>
                        <select name="estado">
                            <option value="libre" <?= $espacio['estado'] === 'libre' ? 'selected' : '' ?>>Libre</option>
                            <option value="ocupado" <?= $espacio['estado'] === 'ocupado' ? 'selected' : '' ?>>Ocupado</option>
                        </select>
                    </td>
                    <td>
                        <select name="tipo">
                            <option value="normal" <?= $espacio['tipo'] === 'normal' ? 'selected' : '' ?>>Normal</option>
                            <option value="discapacitado" <?= $espacio['tipo'] === 'discapacitado' ? 'selected' : '' ?>>Discapacitado</option>
                        </select>
                    </td>
                    <td class="acciones">
                        <input type="submit" name="editar" value="✏️">
                        <a href="?eliminar=<?= $espacio['id'] ?>" onclick="return confirm('¿Eliminar este cajón?')">🗑️</a>
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>

    <a class="volver" href="welcome.php">← Volver al inicio</a>
</div>

</body>
</html>
