<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include(__DIR__ . '/../../Database/conexion.php');

$hoy = date('Y-m-d');
$noticias = mysqli_query($conexion,
    "SELECT * FROM noticias
     WHERE estado='publicado'
       AND (fecha_publicacion IS NULL OR fecha_publicacion <= '$hoy')
     ORDER BY fecha_publicacion DESC, created_at DESC"
);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias | Remolino's Tours</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/Agencia_Remolinos/componentes/header/header.css">
    <link rel="stylesheet" href="/Agencia_Remolinos/componentes/footer/footer.css">
    <link rel="stylesheet" href="/Agencia_Remolinos/componentes/noticias/noticias.css">
</head>
<body class="noticias-page">
<?php include $_SERVER['DOCUMENT_ROOT'] . "/Agencia_Remolinos/componentes/header/header.php"; ?>
<main>
    <section class="noticias-hero">
        <span><i class="fas fa-newspaper"></i> Noticias y avisos</span>
        <h1>Lo nuevo en Remolino's Tours</h1>
        <p>Promociones, comunicados, recomendaciones y novedades para viajar mejor.</p>
    </section>
    <section class="noticias-shell">
        <?php if (!$noticias || mysqli_num_rows($noticias) === 0): ?>
            <div class="noticias-empty">Aun no hay noticias publicadas.</div>
        <?php else: ?>
            <div class="noticias-grid">
                <?php while ($n = mysqli_fetch_assoc($noticias)): ?>
                <article class="noticia-card">
                    <img src="/Agencia_Remolinos/assets/imagenes/<?= htmlspecialchars($n['imagen'] ?: 'default.png') ?>"
                         alt="<?= htmlspecialchars($n['titulo']) ?>"
                         onerror="this.src='https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=800&q=80'">
                    <div class="noticia-body">
                        <span><?= htmlspecialchars($n['categoria']) ?></span>
                        <h2><?= htmlspecialchars($n['titulo']) ?></h2>
                        <p><?= htmlspecialchars($n['resumen'] ?: mb_strimwidth($n['contenido'] ?? '', 0, 160, '...')) ?></p>
                        <div class="noticia-content"><?= nl2br(htmlspecialchars($n['contenido'] ?? '')) ?></div>
                        <small><?= $n['fecha_publicacion'] ? date('d/m/Y', strtotime($n['fecha_publicacion'])) : '' ?></small>
                    </div>
                </article>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </section>
</main>
<?php include $_SERVER['DOCUMENT_ROOT'] . "/Agencia_Remolinos/componentes/footer/footer.php"; ?>
</body>
</html>
