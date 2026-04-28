<?php 
include('auth.php'); // Esto verifica que ya pasaste por el Login_API
include('../Database/conexion.php'); 
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