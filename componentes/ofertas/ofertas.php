<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include(__DIR__ . '/../../Database/conexion.php');

$hoy = date('Y-m-d');
$ofertas = [];
$sql = "SELECT d.*, p.nombre_proveedor
        FROM destinos d
        LEFT JOIN proveedores p ON d.id_proveedor = p.id_proveedor
        WHERE d.estado='Activo'
          AND d.es_oferta=1
          AND (d.oferta_inicio IS NULL OR d.oferta_inicio <= '$hoy')
          AND (d.oferta_fin IS NULL OR d.oferta_fin >= '$hoy')
        ORDER BY d.oferta_fin IS NULL, d.oferta_fin ASC, d.nombre ASC";
$res = mysqli_query($conexion, $sql);
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) $ofertas[] = $row;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ofertas | Remolino's Tours</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/Agencia_Remolinos/componentes/header/header.css">
    <link rel="stylesheet" href="/Agencia_Remolinos/componentes/footer/footer.css">
    <link rel="stylesheet" href="/Agencia_Remolinos/componentes/ofertas/ofertas.css">
</head>
<body class="ofertas-page">
<?php include $_SERVER['DOCUMENT_ROOT'] . "/Agencia_Remolinos/componentes/header/header.php"; ?>

<main>
    <section class="ofertas-hero">
        <div>
            <span class="ofertas-kicker"><i class="fas fa-bolt"></i> Ofertas vigentes</span>
            <h1>Paquetes con precio especial</h1>
            <p>Promociones cargadas por la administradora, conectadas directamente con los paquetes reales.</p>
        </div>
    </section>

    <section class="ofertas-shell">
        <?php if (empty($ofertas)): ?>
            <div class="ofertas-empty">
                <i class="fas fa-tags"></i>
                <h2>No hay ofertas activas</h2>
                <p>Cuando la administradora marque paquetes como oferta, apareceran aqui.</p>
                <a href="/Agencia_Remolinos/componentes/Destinos/destinos.php">Ver destinos</a>
            </div>
        <?php else: ?>
            <div class="ofertas-grid">
                <?php foreach ($ofertas as $d):
                    $precioNormal = (float)($d['precio'] ?? 0);
                    $precioOferta = (float)($d['precio_oferta'] ?: $precioNormal);
                    $ahorro = $precioNormal > $precioOferta ? round((1 - ($precioOferta / $precioNormal)) * 100) : 0;
                ?>
                    <article class="oferta-card">
                        <div class="oferta-img">
                            <img src="/Agencia_Remolinos/assets/imagenes/<?= htmlspecialchars($d['foto_portada'] ?: 'default.png') ?>"
                                 alt="<?= htmlspecialchars($d['nombre']) ?>"
                                 onerror="this.src='https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&q=80'">
                            <?php if ($ahorro > 0): ?>
                                <span class="oferta-save">-<?= $ahorro ?>%</span>
                            <?php endif; ?>
                        </div>
                        <div class="oferta-body">
                            <span class="oferta-label"><?= htmlspecialchars($d['oferta_titulo'] ?: 'Oferta especial') ?></span>
                            <h2><?= htmlspecialchars($d['nombre']) ?></h2>
                            <p><?= htmlspecialchars(mb_strimwidth($d['descripcion'] ?? '', 0, 120, '...')) ?></p>
                            <div class="oferta-meta">
                                <span><i class="fas fa-map-marker-alt"></i><?= htmlspecialchars($d['punto_salida'] ?: 'Salida por confirmar') ?></span>
                                <span><i class="fas fa-calendar-days"></i><?= (int)$d['dias'] ?> dias / <?= (int)$d['noches'] ?> noches</span>
                                <span><i class="fas fa-users"></i><?= htmlspecialchars($d['tipo_cupo'] ?? 'flexible') ?></span>
                            </div>
                            <div class="oferta-bottom">
                                <div>
                                    <?php if ($precioNormal > $precioOferta): ?>
                                        <span class="oferta-old">$<?= number_format($precioNormal, 0, '.', ',') ?></span>
                                    <?php endif; ?>
                                    <strong>$<?= number_format($precioOferta, 0, '.', ',') ?></strong>
                                    <small>MXN por adulto</small>
                                </div>
                                <a href="/Agencia_Remolinos/componentes/Destinos/detalle.php?id=<?= (int)$d['id'] ?>">
                                    Ver detalles <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                            <?php if (!empty($d['oferta_fin'])): ?>
                                <div class="oferta-vigencia">Vigente hasta <?= date('d/m/Y', strtotime($d['oferta_fin'])) ?></div>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . "/Agencia_Remolinos/componentes/footer/footer.php"; ?>
</body>
</html>
