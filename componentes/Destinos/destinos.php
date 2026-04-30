<?php
// componentes/Destinos/destinos.php
include $_SERVER['DOCUMENT_ROOT'] . '/Agencia_Remolinos/Database/conexion.php';

// Filtro por tipo_trayecto
$tipos_validos = ['Redondo', 'Solo ida', 'Charter'];
$tipo_filtro   = (isset($_GET['tipo']) && in_array($_GET['tipo'], $tipos_validos))
                  ? $_GET['tipo'] : 'todos';

// Búsqueda
$busqueda = isset($_GET['busqueda'])
            ? mysqli_real_escape_string($conexion, trim($_GET['busqueda']))
            : '';

// Rango de precio
$precio_min = isset($_GET['precio_min']) ? (float)$_GET['precio_min'] : 0;
$precio_max = isset($_GET['precio_max']) ? (float)$_GET['precio_max'] : 999999;

// Ordenamiento
$orden_validos = ['precio_asc' => 'precio ASC', 'precio_desc' => 'precio DESC', 'nombre' => 'nombre ASC'];
$orden_key     = $_GET['orden'] ?? 'nombre';
$orden_sql     = $orden_validos[$orden_key] ?? 'nombre ASC';

// ── Construir WHERE usando columnas reales de tu tabla ────────────────────
$where = "WHERE estado = 'Activo'";

if ($tipo_filtro !== 'todos') {
    $where .= " AND tipo_trayecto = '$tipo_filtro'";
}
if ($busqueda !== '') {
    $where .= " AND (nombre LIKE '%$busqueda%' OR descripcion LIKE '%$busqueda%' OR punto_salida LIKE '%$busqueda%')";
}
if ($precio_min > 0) {
    $where .= " AND precio >= $precio_min";
}
if ($precio_max < 999999) {
    $where .= " AND precio <= $precio_max";
}

$result   = mysqli_query($conexion, "SELECT d.*, p.nombre_proveedor
                                     FROM destinos d
                                     LEFT JOIN proveedores p ON d.id_proveedor = p.id_proveedor
                                     $where ORDER BY $orden_sql");
$destinos = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Precio máximo para el slider
$maxPrecio = mysqli_fetch_assoc(
    mysqli_query($conexion, "SELECT COALESCE(MAX(precio), 50000) AS max FROM destinos WHERE estado='Activo'")
)['max'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Destinos | Remolino's Tours</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/Agencia_Remolinos/componentes/header/header.css">
    <link rel="stylesheet" href="/Agencia_Remolinos/componentes/footer/footer.css">
    <link rel="stylesheet" href="/Agencia_Remolinos/componentes/Destinos/destinos.css">
</head>
<body class="destinos-page">

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Agencia_Remolinos/componentes/header/header.php'; ?>

    <!-- ── HERO ─────────────────────────────────────────────────── -->
    <section class="dest-hero">
        <div class="dest-hero-overlay"></div>
        <div class="dest-hero-content">
            <span class="dest-hero-badge">
                <i class="fas fa-compass"></i> Explora el mundo
            </span>
            <h1>Descubre tu próximo<br><em>destino</em></h1>
            <p>Vuelos, hoteles y experiencias únicas. Tú eliges la aventura, nosotros te llevamos.</p>
        </div>
    </section>

    <!-- ── FILTROS ───────────────────────────────────────────────── -->
    <section class="dest-filtros-section">
        <div class="dest-filtros-container">

            <!-- Buscador -->
            <form class="dest-search-form" method="GET" action="" id="formFiltros">
                <i class="fas fa-search"></i>
                <input type="text"
                       name="busqueda"
                       placeholder="Buscar destino, ciudad o salida..."
                       value="<?= htmlspecialchars($busqueda) ?>"
                       autocomplete="off"
                       id="inputBusqueda">

                <!-- Campos ocultos para preservar filtros -->
                <?php if ($tipo_filtro !== 'todos'): ?>
                    <input type="hidden" name="tipo" value="<?= htmlspecialchars($tipo_filtro) ?>">
                <?php endif; ?>
                <input type="hidden" name="orden"      value="<?= htmlspecialchars($orden_key) ?>">
                <input type="hidden" name="precio_min" value="<?= $precio_min ?>">
                <input type="hidden" name="precio_max" value="<?= $precio_max ?>">
            </form>

            <!-- Tabs tipo trayecto -->
            <div class="dest-tabs">
                <a href="?<?= $busqueda ? 'busqueda='.urlencode($busqueda).'&' : '' ?>orden=<?= urlencode($orden_key) ?>"
                   class="dest-tab <?= $tipo_filtro === 'todos' ? 'active' : '' ?>">
                    <i class="fas fa-globe"></i> Todos
                </a>
                <a href="?tipo=Redondo<?= $busqueda ? '&busqueda='.urlencode($busqueda) : '' ?>&orden=<?= urlencode($orden_key) ?>"
                   class="dest-tab <?= $tipo_filtro === 'Redondo' ? 'active' : '' ?>">
                    <i class="fas fa-sync-alt"></i> Redondo
                </a>
                <a href="?tipo=Solo+ida<?= $busqueda ? '&busqueda='.urlencode($busqueda) : '' ?>&orden=<?= urlencode($orden_key) ?>"
                   class="dest-tab <?= $tipo_filtro === 'Solo ida' ? 'active' : '' ?>">
                    <i class="fas fa-plane"></i> Solo ida
                </a>
                <a href="?tipo=Charter<?= $busqueda ? '&busqueda='.urlencode($busqueda) : '' ?>&orden=<?= urlencode($orden_key) ?>"
                   class="dest-tab <?= $tipo_filtro === 'Charter' ? 'active' : '' ?>">
                    <i class="fas fa-plane-departure"></i> Charter
                </a>
            </div>

            <!-- Ordenar y contador -->
            <div class="dest-toolbar">
                <span class="dest-count">
                    <strong><?= count($destinos) ?></strong>
                    destino<?= count($destinos) !== 1 ? 's' : '' ?> encontrado<?= count($destinos) !== 1 ? 's' : '' ?>
                </span>
                <form method="GET" id="formOrden">
                    <input type="hidden" name="busqueda"   value="<?= htmlspecialchars($busqueda) ?>">
                    <input type="hidden" name="tipo"       value="<?= htmlspecialchars($tipo_filtro) ?>">
                    <input type="hidden" name="precio_min" value="<?= $precio_min ?>">
                    <input type="hidden" name="precio_max" value="<?= $precio_max ?>">
                    <select name="orden" class="dest-select-orden" onchange="this.form.submit()">
                        <option value="nombre"      <?= $orden_key==='nombre'?'selected':'' ?>>A–Z</option>
                        <option value="precio_asc"  <?= $orden_key==='precio_asc'?'selected':'' ?>>Precio: menor a mayor</option>
                        <option value="precio_desc" <?= $orden_key==='precio_desc'?'selected':'' ?>>Precio: mayor a menor</option>
                    </select>
                </form>
            </div>

        </div>
    </section>

    <!-- ── CATÁLOGO ──────────────────────────────────────────────── -->
    <section class="dest-catalogo-section">
        <div class="dest-catalogo-container">

            <?php if (empty($destinos)): ?>
            <div class="dest-empty">
                <i class="fas fa-map-marked-alt"></i>
                <h3>Sin resultados</h3>
                <p>No encontramos destinos con ese filtro. <a href="?">Ver todos</a></p>
            </div>

            <?php else: ?>
            <div class="dest-grid">
                <?php foreach ($destinos as $d): ?>
                <article class="dest-card">

                    <!-- Imagen -->
                    <div class="dest-card-img">
                        <img src="/Agencia_Remolinos/assets/imagenes/<?= htmlspecialchars($d['foto_portada'] ?? 'default.png') ?>"
                             alt="<?= htmlspecialchars($d['nombre']) ?>"
                             onerror="this.src='https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=600&q=80'">

                        <!-- Badge trayecto -->
                        <span class="dest-tipo-badge">
                            <i class="fas fa-<?= $d['tipo_trayecto'] === 'Redondo' ? 'sync-alt' : 'plane' ?>"></i>
                            <?= htmlspecialchars($d['tipo_trayecto'] ?? 'Redondo') ?>
                        </span>

                        <!-- Días/Noches si existen -->
                        <?php if (!empty($d['dias'])): ?>
                        <span class="dest-dias-badge">
                            <?= (int)$d['dias'] ?> días / <?= (int)$d['noches'] ?> noches
                        </span>
                        <?php endif; ?>
                    </div>

                    <!-- Cuerpo -->
                    <div class="dest-card-body">

                        <!-- Proveedor (aerolínea) -->
                        <?php if (!empty($d['nombre_proveedor'])): ?>
                        <span class="dest-proveedor">
                            <i class="fas fa-plane-up"></i>
                            <?= htmlspecialchars($d['nombre_proveedor']) ?>
                        </span>
                        <?php endif; ?>

                        <h3 class="dest-card-nombre"><?= htmlspecialchars($d['nombre']) ?></h3>

                        <!-- Punto de salida -->
                        <?php if (!empty($d['punto_salida'])): ?>
                        <p class="dest-salida">
                            <i class="fas fa-map-marker-alt"></i>
                            Sale desde: <strong><?= htmlspecialchars($d['punto_salida']) ?></strong>
                        </p>
                        <?php endif; ?>

                        <p class="dest-card-desc">
                            <?= htmlspecialchars(mb_strimwidth($d['descripcion'] ?? '', 0, 100, '…')) ?>
                        </p>

                        <!-- Maletas -->
                        <div class="dest-maletas">
                            <span><i class="fas fa-briefcase"></i> <?= (int)($d['maleta_mano_kg'] ?? 10) ?>kg mano</span>
                            <span><i class="fas fa-suitcase"></i> <?= (int)($d['maleta_documentada_kg'] ?? 25) ?>kg doc.</span>
                            <?php if (!empty($d['seguro_basico_incluido'])): ?>
                            <span class="dest-seguro"><i class="fas fa-shield-alt"></i> Seguro incluido</span>
                            <?php endif; ?>
                        </div>

                        <!-- Footer: precio + botón -->
                        <div class="dest-card-footer">
                            <div class="dest-precio">
                                <span class="dest-precio-desde">desde</span>
                                <span class="dest-precio-val">
                                    $<?= number_format((float)$d['precio'], 0, '.', ',') ?>
                                </span>
                                <span class="dest-precio-mn">MXN</span>
                            </div>
                            <?php if (!empty($d['precio_nino'])): ?>
                            <small class="dest-precio-nino">
                                Niños: $<?= number_format((float)$d['precio_nino'], 0, '.', ',') ?>
                            </small>
                            <?php endif; ?>
                            <a href="/Agencia_Remolinos/componentes/Reserva/reserva.php"
                               class="dest-btn-reservar">
                                Reservar <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                </article>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

        </div>
    </section>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Agencia_Remolinos/componentes/footer/footer.php'; ?>

    <script src="/Agencia_Remolinos/componentes/Destinos/destinos.js"></script>
</body>
</html>
