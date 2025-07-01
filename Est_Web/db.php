<?php
$servername = "localhost";
$username = "root";
$password = ""; // Cambia esto si tu MySQL tiene contraseña
$dbname = "estacionamiento";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Establecer charset
$conn->set_charset("utf8mb4");
?>
