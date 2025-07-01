<?php
session_start();
require 'db.php';

// Verificar sesión activa
if (!isset($_SESSION['correo']) || !isset($_SESSION['nombre'])) {
    header("Location: index.php");
    exit();
}

// Obtener los espacios desde la base de datos
$query = "SELECT * FROM espacios ORDER BY numero_cajon";
$resultado = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mapa de Estacionamiento</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .grid {
            display: grid;
            grid-template-columns: repeat(5, 100px);
            gap: 10px;
            margin-top: 150px;
            justify-content: center;
        }

        .cajon {
            width: 100px;
            height: 100px;
            text-align: center;
            line-height: 1.2;
            padding-top: 25px;
            font-size: 14px;
            border-radius: 8px;
            font-weight: bold;
            color: white;
            cursor: pointer;
            border: none;
        }

        .libre { background-color: green; }
        .ocupado { background-color: red; }
        .discapacitado { border: 3px dashed yellow; }

        a.volver {
            margin-top: 20px;
            display: block;
            text-align: center;
            font-weight: bold;
            color: #333;
        }

        form {
            margin: 0;
        }
    </style>
</head>
<body>
<nav>
    <h1>Mapa de Estacionamiento</h1>
</nav>

<div class="grid">
    <?php while ($fila = $resultado->fetch_assoc()): ?>
        <?php
            $clase = $fila['estado'];
            if ($fila['tipo'] === 'discapacitado') {
                $clase .= ' discapacitado';
            }
        ?>

        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
            <form method="get" action="cambiar_estado.php">
                <input type="hidden" name="id" value="<?= $fila['id'] ?>">
                <button type="submit" class="cajon <?= $clase ?>">
                    <?= $fila['numero_cajon'] ?><br><?= strtoupper($fila['estado']) ?>
                </button>
            </form>
        <?php else: ?>
            <div class="cajon <?= $clase ?>">
                <?= $fila['numero_cajon'] ?><br><?= strtoupper($fila['estado']) ?>
            </div>
        <?php endif; ?>
    <?php endwhile; ?>
</div>

<a class="volver" href="welcome.php">← Volver</a>
</body>
</html>
