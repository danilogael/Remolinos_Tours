<?php
// Sesión y seguridad
if (session_status() === PHP_SESSION_NONE) session_start();

// Conexión — ruta relativa correcta desde componentes/Reserva/
include(__DIR__ . '/../../Database/conexion.php');

// Si no hay sesión activa, redirigir al login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: /Agencia_Remolinos/Login_APP/login.php?error=sesion_requerida");
    exit();
}

// Cargar destinos activos
// La columna "activo" no existe en tu tabla; se usa el campo "estado"
$destinos = [];
$res = mysqli_query($conexion, "SELECT * FROM destinos WHERE estado = 'Activo' ORDER BY nombre ASC");
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $destinos[] = $row;
    }
}

// Datos del usuario logueado
$id_usuario = (int)$_SESSION['id_usuario'];
$resUser    = mysqli_query($conexion, "SELECT * FROM usuarios WHERE id = $id_usuario LIMIT 1");
$usuario    = mysqli_fetch_assoc($resUser);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="ofertas.css">
    <link rel="stylesheet" href="/Agencia_Remolinos/componentes/header/header.css">
   <link rel="stylesheet" href="/Agencia_Remolinos/componentes/footer/footer.css">
</head>
<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/Agencia_Remolinos/componentes/header/header.php"; ?>
    <section class="hero-creative hero-ofertas">
    <div class="hero-glow"></div>
    <div class="hero-body">
        <div class="badge-promo">Hasta 40% OFF</div>
        <h1>El lujo de viajar, <br> <span>al precio que esperabas</span></h1>
        <p>Vuelos, hoteles y tours con tarifas exclusivas de Remolinos.</p>
        <button class="btn-hero-cta">Ver Ofertas Flash</button>
    </div>
</section>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/Agencia_Remolinos/componentes/footer/footer.php"; ?>
    <script src="ofertas.js"></script>
</body>
</html>