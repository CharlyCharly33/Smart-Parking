<?php
session_start();
require 'db.php';

// Solo admin puede cambiar estado
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: mapa.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "SELECT estado FROM espacios WHERE id = $id";
    $result = $conn->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        $nuevo_estado = ($row['estado'] === 'libre') ? 'ocupado' : 'libre';

        $update = "UPDATE espacios SET estado = '$nuevo_estado' WHERE id = $id";
        if ($conn->query($update)) {
            header("Location: mapa.php");
            exit();
        } else {
            echo "❌ Error al actualizar.";
        }
    } else {
        echo "❌ Cajón no encontrado.";
    }
} else {
    echo "❌ Parámetro inválido.";
}
?>
