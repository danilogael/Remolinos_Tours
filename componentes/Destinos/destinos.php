<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Agencia_Remolinos/Database/conexion.php';

$tipo_filtro = isset($_GET['tipo']) && in_array($_GET['tipo'], ['nacional','internacional']) 
               ? $_GET['tipo'] 
               : 'todos';

$busqueda = isset($_GET['busqueda']) ? mysqli_real_escape_string($conexion, trim($_GET['busqueda'])) : '';

// Construir query
$where = "WHERE activo = 1";
if ($tipo_filtro !== 'todos')  $where .= " AND tipo = '$tipo_filtro'";
if ($busqueda !== '')           $where .= " AND (nombre LIKE '%$busqueda%' OR descripcion LIKE '%$busqueda%')";

$result = mysqli_query($conexion, "SELECT * FROM destinos $where ORDER BY nombre ASC");
$destinos = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Destinos | Remolinos Tours</title>
    <link rel="stylesheet" href="/Agencia_Remolinos/componentes/header/header.css">
    <link rel="stylesheet" href="/Agencia_Remolinos/componentes/footer/footer.css">
    <link rel="stylesheet" href="/Agencia_Remolinos/componentes/Destinos/destinos.css">
</head>
<body class="destinos-page">

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Agencia_Remolinos/componentes/header/header.php'; ?>

    <!-- ── HERO ────────────────────────────────────────────── -->
    <section class="dest-hero">
        <div class="dest-hero-overlay"></div>
        <div class="dest-hero-content">
            <span class="dest-hero-badge"><i class="fas fa-compass"></i> Explora el mundo</span>
            <h1>Descubre tu próximo<br><em>destino</em></h1>
            <p>Nacionales e internacionales. Tú eliges la aventura, nosotros te llevamos.</p>
        </div>
    </section>

    <!-- ── FILTROS ──────────────────────────────────────────── -->
    <section class="dest-filtros-section">
        <div class="dest-filtros-container">

            <!-- Búsqueda -->
            <form class="dest-search-form" method="GET" action="">
                <i class="fas fa-search"></i>
                <input type="text"
                       name="busqueda"
                       placeholder="Buscar destino..."
                       value="<?php echo htmlspecialchars($busqueda); ?>"
                       autocomplete="off">
                <?php if ($tipo_filtro !== 'todos'): ?>
                    <input type="hidden" name="tipo" value="<?php echo $tipo_filtro; ?>">
                <?php endif; ?>
            </form>

            <!-- Tabs de tipo -->
            <div class="dest-tabs">
                <a href="?<?php echo $busqueda ? 'busqueda='.urlencode($busqueda) : ''; ?>"
                   class="dest-tab <?php echo $tipo_filtro === 'todos' ? 'active' : ''; ?>">
                    <i class="fas fa-globe"></i> Todos
                </a>
                <a href="?tipo=nacional<?php echo $busqueda ? '&busqueda='.urlencode($busqueda) : ''; ?>"
                   class="dest-tab <?php echo $tipo_filtro === 'nacional' ? 'active' : ''; ?>">
                    <i class="fas fa-flag"></i> Nacional
                </a>
                <a href="?tipo=internacional<?php echo $busqueda ? '&busqueda='.urlencode($busqueda) : ''; ?>"
                   class="dest-tab <?php echo $tipo_filtro === 'internacional' ? 'active' : ''; ?>">
                    <i class="fas fa-plane"></i> Internacional
                </a>
            </div>

        </div>
    </section>

    <!-- ── CATÁLOGO ─────────────────────────────────────────── -->
    <section class="dest-catalogo-section">
        <div class="dest-catalogo-container">

            <?php if (empty($destinos)): ?>
                <div class="dest-empty">
                    <i class="fas fa-map-marked-alt"></i>
                    <h3>Sin resultados</h3>
                    <p>No encontramos destinos con ese filtro. <a href="?">Ver todos</a></p>
                </div>

            <?php else: ?>
                <p class="dest-count">
                    <?php echo count($destinos); ?> destino<?php echo count($destinos) !== 1 ? 's' : ''; ?> encontrado<?php echo count($destinos) !== 1 ? 's' : ''; ?>
                </p>

                <div class="dest-grid">
                    <?php foreach ($destinos as $d): ?>
                    <article class="dest-card">
                        <div class="dest-card-img">
                            <img src="/Agencia_Remolinos/assets/<?php echo htmlspecialchars($d['imagen']); ?>"
                                 alt="<?php echo htmlspecialchars($d['nombre']); ?>"
                                 onerror="this.src='/Agencia_Remolinos/assets/destinos/placeholder.jpg'">
                            <span class="dest-tipo-badge dest-tipo-<?php echo $d['tipo']; ?>">
                                <?php echo $d['tipo'] === 'nacional' ? '<i class="fas fa-flag"></i> Nacional' : '<i class="fas fa-plane"></i> Internacional'; ?>
                            </span>
                        </div>
                        <div class="dest-card-body">
                            <h3 class="dest-card-nombre"><?php echo htmlspecialchars($d['nombre']); ?></h3>
                            <p class="dest-card-desc"><?php echo htmlspecialchars($d['descripcion']); ?></p>
                            <div class="dest-card-footer">
                                <div class="dest-precio">
                                    <span class="dest-precio-desde">desde</span>
                                    <span class="dest-precio-val">$<?php echo number_format($d['precio'], 0, '.', ','); ?></span>
                                    <span class="dest-precio-mn">MXN</span>
                                </div>
                                <a href="reservar.php?id=<?php echo $d['id']; ?>" class="dest-btn-reservar">
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
    <br>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Agencia_Remolinos/componentes/footer/footer.php'; ?>

    <script src="/Agencia_Remolinos/componentes/Destinos/destinos.js"></script>
</body>
</html>
