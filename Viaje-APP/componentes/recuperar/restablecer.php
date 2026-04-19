<?php
session_start();

// Validar que venga de enviar_codigo.php
if (!isset($_SESSION['correo_recuperacion'])) {
    echo "<script>
        alert('Primero solicita un código de recuperación.');
        window.location='solicitar.php';
    </script>";
    exit;
}

$correo = $_SESSION['correo_recuperacion']; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer contraseña</title>
</head>
<body>

<div class="container">
    <h2>Restablecer contraseña</h2>

    <small>Correo al que se envió el código: <b><?php echo htmlspecialchars($correo); ?></b></small>
    <br><br>

    <form action="actualizar_password.php" method="POST">

        <label>Código recibido:</label>
        <input type="number" name="codigo" required>

        <label>Nueva contraseña:</label>
        <input type="password" name="nueva" minlength="6" required>

        <label>Confirmar contraseña:</label>
        <input type="password" name="confirmar" minlength="6" required>

        <button type="submit">Restablecer</button>
    </form>
</div>

</body>
</html>
