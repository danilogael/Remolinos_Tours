<?php
// componentes/Destinos/detalle.php
include $_SERVER['DOCUMENT_ROOT'] . '/Agencia_Remolinos/Database/conexion.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: /Agencia_Remolinos/componentes/Destinos/destinos.php');
    exit();
}

$destino = null;
$stmt = mysqli_prepare($conexion,
    "SELECT d.*, p.nombre_proveedor, p.tipo_proveedor
     FROM destinos d
     LEFT JOIN proveedores p ON d.id_proveedor = p.id_proveedor
     WHERE d.id = ? AND d.estado = 'Activo'
     LIMIT 1"
);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$resDestino = mysqli_stmt_get_result($stmt);
$destino = mysqli_fetch_assoc($resDestino);

if (!$destino) {
    header('Location: /Agencia_Remolinos/componentes/Destinos/destinos.php');
    exit();
}

$itinerario = [];
$resIt = mysqli_query($conexion,
    "SELECT dia_numero, titulo_actividad, descripcion_actividad
     FROM itinerarios
     WHERE id_destino = $id
     ORDER BY dia_numero ASC"
);
if ($resIt) {
    while ($row = mysqli_fetch_assoc($resIt)) {
        $itinerario[] = $row;
    }
}

$extras = [];
$resExtras = mysqli_query($conexion,
    "SELECT nombre_actividad, precio_extra
     FROM actividades_extra
     WHERE id_destino = $id
     ORDER BY nombre_actividad ASC"
);
if ($resExtras) {
    while ($row = mysqli_fetch_assoc($resExtras)) {
        $extras[] = $row;
    }
}

$nombre = $destino['nombre'] ?? 'Paquete';
$imagen = $destino['foto_portada'] ?: 'default.png';
$dias = (int)($destino['dias'] ?? 0);
$noches = (int)($destino['noches'] ?? 0);
$precioBase = (float)($destino['precio'] ?? 0);
$precioFinal = (!empty($destino['es_oferta']) && !empty($destino['precio_oferta'])) ? (float)$destino['precio_oferta'] : $precioBase;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($nombre) ?> | Remolino's Tours</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/Agencia_Remolinos/componentes/header/header.css">
    <link rel="stylesheet" href="/Agencia_Remolinos/componentes/footer/footer.css">
    <link rel="stylesheet" href="/Agencia_Remolinos/componentes/Destinos/destinos.css">
</head>
<body class="destinos-page detalle-page">

<?php include $_SERVER['DOCUMENT_ROOT'] . '/Agencia_Remolinos/componentes/header/header.php'; ?>

<main>
    <section class="detalle-hero">
        <img src="/Agencia_Remolinos/assets/imagenes/<?= htmlspecialchars($imagen) ?>"
             alt="<?= htmlspecialchars($nombre) ?>"
             onerror="this.src='https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1600&q=80'">
        <div class="detalle-hero-overlay"></div>
        <div class="detalle-hero-content">
            <a href="/Agencia_Remolinos/componentes/Destinos/destinos.php" class="detalle-back">
                <i class="fas fa-arrow-left"></i> Volver a destinos
            </a>
            <span class="dest-tipo-badge detalle-badge">
                <i class="fas fa-<?= ($destino['tipo_trayecto'] ?? '') === 'Redondo' ? 'sync-alt' : 'plane' ?>"></i>
                <?= htmlspecialchars($destino['tipo_trayecto'] ?? 'Redondo') ?>
            </span>
            <h1><?= htmlspecialchars($nombre) ?></h1>
            <p><?= htmlspecialchars($destino['descripcion'] ?? '') ?></p>
        </div>
    </section>

    <section class="detalle-shell">
        <div class="detalle-main">
            <div class="detalle-section">
                <h2>Lo que incluye</h2>
                <div class="detalle-incluye-grid">
                    <div class="detalle-incluye-item">
                        <i class="fas fa-plane-departure"></i>
                        <div>
                            <strong>Trayecto</strong>
                            <span><?= htmlspecialchars($destino['tipo_trayecto'] ?? 'Redondo') ?></span>
                        </div>
                    </div>
                    <div class="detalle-incluye-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <strong>Punto de salida</strong>
                            <span><?= htmlspecialchars($destino['punto_salida'] ?: 'Por confirmar') ?></span>
                        </div>
                    </div>
                    <div class="detalle-incluye-item">
                        <i class="fas fa-briefcase"></i>
                        <div>
                            <strong>Equipaje de mano</strong>
                            <span><?= (int)($destino['maleta_mano_kg'] ?? 10) ?> kg</span>
                        </div>
                    </div>
                    <div class="detalle-incluye-item">
                        <i class="fas fa-suitcase"></i>
                        <div>
                            <strong>Equipaje documentado</strong>
                            <span><?= (int)($destino['maleta_documentada_kg'] ?? 25) ?> kg</span>
                        </div>
                    </div>
                    <div class="detalle-incluye-item">
                        <i class="fas fa-shield-alt"></i>
                        <div>
                            <strong>Seguro basico</strong>
                            <span><?= !empty($destino['seguro_basico_incluido']) ? 'Incluido' : 'No incluido' ?></span>
                        </div>
                    </div>
                <div class="detalle-incluye-item">
                    <i class="fas fa-users"></i>
                    <div>
                        <strong>Cupo total</strong>
                            <span><?= (int)($destino['cupo_total'] ?? 0) ?: 'Por confirmar' ?> viajeros, <?= htmlspecialchars($destino['tipo_cupo'] ?? 'flexible') ?></span>
                    </div>
                </div>
                    <div class="detalle-incluye-item">
                        <i class="fas fa-child"></i>
                        <div>
                            <strong>Reglas de viajeros</strong>
                            <span><?= (int)($destino['min_adultos'] ?? 1) ?>-<?= (int)($destino['max_adultos'] ?? 10) ?> adultos<?= !empty($destino['permite_ninos']) ? ', hasta '.(int)($destino['max_ninos'] ?? 0).' ninos' : ', sin ninos' ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="detalle-section">
                <h2>Itinerario</h2>
                <?php if (empty($itinerario)): ?>
                    <div class="detalle-empty-line">El itinerario detallado se confirmara con tu asesor.</div>
                <?php else: ?>
                    <div class="detalle-timeline">
                        <?php foreach ($itinerario as $item): ?>
                            <article class="detalle-day">
                                <div class="detalle-day-num">Dia <?= (int)$item['dia_numero'] ?></div>
                                <div>
                                    <h3><?= htmlspecialchars($item['titulo_actividad']) ?></h3>
                                    <p><?= htmlspecialchars($item['descripcion_actividad'] ?: 'Actividad por confirmar.') ?></p>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="detalle-section">
                <h2>Actividades extra</h2>
                <?php if (empty($extras)): ?>
                    <div class="detalle-empty-line">Este paquete no tiene actividades extra registradas todavia.</div>
                <?php else: ?>
                    <div class="detalle-extra-list">
                        <?php foreach ($extras as $extra): ?>
                            <div class="detalle-extra-item">
                                <span><?= htmlspecialchars($extra['nombre_actividad']) ?></span>
                                <strong>$<?= number_format((float)$extra['precio_extra'], 0, '.', ',') ?> MXN</strong>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <aside class="detalle-summary">
            <div class="detalle-summary-card">
                <span class="detalle-summary-label">Desde</span>
                <?php if ($precioFinal < $precioBase): ?>
                    <span class="detalle-summary-currency" style="text-decoration:line-through;">$<?= number_format($precioBase, 0, '.', ',') ?> MXN</span>
                <?php endif; ?>
                <strong class="detalle-summary-price">$<?= number_format($precioFinal, 0, '.', ',') ?></strong>
                <span class="detalle-summary-currency">MXN por adulto</span>

                <?php if (!empty($destino['precio_nino'])): ?>
                    <div class="detalle-summary-row">
                        <span>Precio nino</span>
                        <strong>$<?= number_format((float)$destino['precio_nino'], 0, '.', ',') ?></strong>
                    </div>
                <?php endif; ?>
                <?php if ($dias > 0): ?>
                    <div class="detalle-summary-row">
                        <span>Duracion</span>
                        <strong><?= $dias ?> dias<?= $noches > 0 ? " / $noches noches" : '' ?></strong>
                    </div>
                <?php endif; ?>
                <?php if (!empty($destino['fecha_salida'])): ?>
                    <div class="detalle-summary-row">
                        <span>Salida</span>
                        <strong><?= date('d/m/Y', strtotime($destino['fecha_salida'])) ?></strong>
                    </div>
                <?php endif; ?>
                <?php if (!empty($destino['nombre_proveedor'])): ?>
                    <div class="detalle-summary-row">
                        <span>Proveedor</span>
                        <strong><?= htmlspecialchars($destino['nombre_proveedor']) ?></strong>
                    </div>
                <?php endif; ?>

                <a href="/Agencia_Remolinos/componentes/Reserva/reserva.php?id_destino=<?= (int)$destino['id'] ?>"
                   class="detalle-reserva-btn">
                    <i class="fas fa-calendar-check"></i> Reservar este paquete
                </a>
            </div>
        </aside>
    </section>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/Agencia_Remolinos/componentes/footer/footer.php'; ?>

</body>
</html>
