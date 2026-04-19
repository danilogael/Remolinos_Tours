<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar contraseña</title>
   
</head>
<body>

<div class="container">
    <h2>Recuperar contraseña</h2>

    <form action="enviar_codigo.php" method="POST">
        <label>Ingresa tu correo:</label>
        <input type="email" name="correo" required>

        <button type="submit">Enviar código</button>
    </form>
</div>

</body>
</html>
