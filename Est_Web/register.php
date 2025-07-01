<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $correo = mysqli_real_escape_string($conn, $_POST['correo']);
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
    $tipo_vehiculo = mysqli_real_escape_string($conn, $_POST['tipo_vehiculo']);
    $placas = mysqli_real_escape_string($conn, $_POST['placas']);
    $discapacidad = isset($_POST['discapacidad']) ? intval($_POST['discapacidad']) : 0;

    $contrasena = $_POST['contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    if ($contrasena !== $confirmar_contrasena) {
        $error = "Las contraseñas no coinciden.";
    } else {
        $contrasena_hashed = password_hash($contrasena, PASSWORD_DEFAULT);

        $check = "SELECT * FROM usuarios WHERE correo = '$correo'";
        $result = $conn->query($check);

        if ($result->num_rows > 0) {
            $error = "El correo ya está registrado.";
        } else {
            $sql = "INSERT INTO usuarios (nombre, correo, contrasena, telefono, tipo_vehiculo, placas, discapacidad)
                    VALUES ('$nombre', '$correo', '$contrasena_hashed', '$telefono', '$tipo_vehiculo', '$placas', $discapacidad)";
            if ($conn->query($sql) === TRUE) {
                $success = "Registro exitoso. Ahora puedes iniciar sesión.";
            } else {
                $error = "Error al registrar usuario.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav>
    <h1>Smart Parking</h1>
</nav>
<div class="login-container">
    <h2>Registro</h2>
    <form method="POST">
        <input type="text" name="nombre" placeholder="Nombre completo" required>
        <input type="email" name="correo" placeholder="Correo electrónico" required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>
        <input type="password" name="confirmar_contrasena" placeholder="Confirmar Contraseña" required>
        <input type="text" name="telefono" placeholder="Teléfono" required>
        <select name="tipo_vehiculo" required>
            <option value="">Tipo de vehículo</option>
            <option value="Auto">Auto</option>
            <option value="Moto">Moto</option>
            <option value="Bicicleta">Bicicleta</option>
        </select>
        <input type="text" name="placas" placeholder="Placas del vehículo" required>
        <label>¿Tiene discapacidad?</label><br>
        <input type="radio" name="discapacidad" value="1" required> Sí
        <input type="radio" name="discapacidad" value="0" required> No<br><br>
        <input type="submit" value="Registrarse">
    </form>
    <p>¿Ya tienes cuenta? <a href="index.php">Inicia sesión aquí</a></p>
    <?php
    if (isset($error)) echo "<p class='message'>$error</p>";
    if (isset($success)) echo "<p class='success'>$success</p>";
    ?>
</div>
</body>
</html>
