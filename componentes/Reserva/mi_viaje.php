<?php
// componentes/Reserva/mi_viaje.php — Arma tu Viaje a la Medida
if (session_status() === PHP_SESSION_NONE) session_start();

include(__DIR__ . '/../../Database/conexion.php');

// Seguridad: sesión requerida
if (!isset($_SESSION['id_usuario'])) {
    header("Location: /Agencia_Remolinos/Login_APP/login.php?error=sesion_requerida");
    exit();
}

$id_usuario = (int)$_SESSION['id_usuario'];
$resUser    = mysqli_query($conexion, "SELECT * FROM usuarios WHERE id=$id_usuario LIMIT 1");
$usuario    = mysqli_fetch_assoc($resUser);

// Cargar destinos activos con proveedor
$destinos = [];
$resD = mysqli_query($conexion,
    "SELECT d.*, p.nombre_proveedor, p.tipo_proveedor
     FROM destinos d
     LEFT JOIN proveedores p ON d.id_proveedor = p.id_proveedor
     WHERE d.estado = 'Activo'
     ORDER BY d.nombre ASC"
);
while ($row = mysqli_fetch_assoc($resD)) $destinos[] = $row;

// Cargar actividades extra agrupadas por destino
$extras = [];
$resE = mysqli_query($conexion, "SELECT * FROM actividades_extra ORDER BY id_destino, nombre_actividad");
while ($row = mysqli_fetch_assoc($resE)) {
    $extras[$row['id_destino']][] = $row;
}

// Cargar proveedores para vuelos/hoteles
$proveedores = [];
$resP = mysqli_query($conexion, "SELECT * FROM proveedores ORDER BY tipo_proveedor, nombre_proveedor");
while ($row = mysqli_fetch_assoc($resP)) {
    $proveedores[$row['tipo_proveedor']][] = $row;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arma tu Viaje | Remolino's Tours</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/Agencia_Remolinos/componentes/header/header.css">
    <link rel="stylesheet" href="/Agencia_Remolinos/componentes/footer/footer.css">
    <link rel="stylesheet" href="/Agencia_Remolinos/componentes/Reserva/reserva.css">
    <link rel="stylesheet" href="/Agencia_Remolinos/componentes/Reserva/mi_viaje.css">
</head>
<body class="reserva-page">

<?php include $_SERVER['DOCUMENT_ROOT'] . '/Agencia_Remolinos/componentes/header/header.php'; ?>

<!-- ── HERO ──────────────────────────────────────────────────── -->
<section class="reserva-hero mv-hero">
    <div class="reserva-hero-overlay"></div>
    <div class="reserva-hero-content">
        <span class="reserva-hero-label">
            <i class="fas fa-magic"></i>&nbsp; Personaliza cada detalle
        </span>
        <h1>Arma tu Viaje<br><span>a la Medida</span></h1>
        <p>Elige destino, fechas, vuelo, hotel y actividades. Tú decides, nosotros lo hacemos realidad.</p>
    </div>
</section>

<!-- ── SELECTOR DE MODO ───────────────────────────────────────── -->
<div class="mv-modo-selector">
    <a href="reserva.php" class="mv-modo-btn">
        <i class="fas fa-box-open"></i>
        <div>
            <strong>Reservar Paquete</strong>
            <span>Todo incluido, listo para viajar</span>
        </div>
    </a>
    <div class="mv-modo-btn mv-modo-active">
        <i class="fas fa-magic"></i>
        <div>
            <strong>Arma tu Viaje</strong>
            <span>Personaliza cada detalle a tu gusto</span>
        </div>
    </div>
</div>

<!-- ── STEPPER ───────────────────────────────────────────────── -->
<div class="stepper-wrap">
    <div class="stepper">
        <div class="step active" id="step-dot-1">
            <div class="step-circle"><i class="fas fa-map-marked-alt"></i></div>
            <span>Destino</span>
        </div>
        <div class="step-line"></div>
        <div class="step" id="step-dot-2">
            <div class="step-circle"><i class="fas fa-calendar-alt"></i></div>
            <span>Fechas</span>
        </div>
        <div class="step-line"></div>
        <div class="step" id="step-dot-3">
            <div class="step-circle"><i class="fas fa-users"></i></div>
            <span>Viajeros</span>
        </div>
        <div class="step-line"></div>
        <div class="step" id="step-dot-4">
            <div class="step-circle"><i class="fas fa-concierge-bell"></i></div>
            <span>Extras</span>
        </div>
        <div class="step-line"></div>
        <div class="step" id="step-dot-5">
            <div class="step-circle"><i class="fas fa-check"></i></div>
            <span>Confirmar</span>
        </div>
    </div>
</div>

<!-- ── LAYOUT PRINCIPAL ──────────────────────────────────────── -->
<div class="reserva-layout">

    <!-- FORMULARIO MULTIPASO -->
    <div class="reserva-form-wrap">

        <!-- ══ PASO 1: DESTINO ══════════════════════════════════ -->
        <div class="paso" id="paso-1">
            <h2 class="paso-titulo">¿A dónde quieres ir?</h2>

            <!-- Búsqueda rápida -->
            <div class="mv-search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="buscadorDestino" placeholder="Buscar destino..." autocomplete="off">
            </div>

            <!-- Grid de destinos -->
            <div class="mv-destinos-grid" id="gridDestinos">
                <?php if (empty($destinos)): ?>
                <div class="mv-empty">
                    <i class="fas fa-map-marked-alt"></i>
                    <p>No hay destinos disponibles por el momento.</p>
                </div>
                <?php else: ?>
                <?php foreach ($destinos as $d): ?>
                <div class="mv-destino-card"
                     data-id="<?= $d['id'] ?>"
                     data-nombre="<?= htmlspecialchars($d['nombre']) ?>"
                     data-precio="<?= $d['precio'] ?>"
                     data-precio-nino="<?= $d['precio_nino'] ?? 0 ?>"
                     data-imagen="<?= htmlspecialchars($d['foto_portada'] ?? 'default.png') ?>"
                     data-descripcion="<?= htmlspecialchars($d['descripcion'] ?? '') ?>"
                     data-salida="<?= htmlspecialchars($d['punto_salida'] ?? '') ?>"
                     data-trayecto="<?= htmlspecialchars($d['tipo_trayecto'] ?? 'Redondo') ?>"
                     data-dias="<?= (int)($d['dias'] ?? 1) ?>"
                     data-noches="<?= (int)($d['noches'] ?? 0) ?>"
                     data-proveedor="<?= htmlspecialchars($d['nombre_proveedor'] ?? '') ?>"
                     onclick="seleccionarDestino(this)">

                    <div class="mv-destino-img">
                        <img src="/Agencia_Remolinos/assets/imagenes/<?= htmlspecialchars($d['foto_portada'] ?? 'default.png') ?>"
                             alt="<?= htmlspecialchars($d['nombre']) ?>"
                             onerror="this.src='https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=400'">

                        <?php if (!empty($d['dias'])): ?>
                        <span class="mv-dias-pill">
                            <?= (int)$d['dias'] ?>d / <?= (int)($d['noches'] ?? 0) ?>n
                        </span>
                        <?php endif; ?>
                    </div>

                    <div class="mv-destino-body">
                        <?php if (!empty($d['nombre_proveedor'])): ?>
                        <span class="mv-proveedor-tag">
                            <i class="fas fa-plane-up"></i>
                            <?= htmlspecialchars($d['nombre_proveedor']) ?>
                        </span>
                        <?php endif; ?>

                        <h3><?= htmlspecialchars($d['nombre']) ?></h3>

                        <?php if (!empty($d['punto_salida'])): ?>
                        <p class="mv-salida">
                            <i class="fas fa-map-marker-alt"></i>
                            Sale desde <?= htmlspecialchars($d['punto_salida']) ?>
                        </p>
                        <?php endif; ?>

                        <p class="mv-desc">
                            <?= htmlspecialchars(mb_strimwidth($d['descripcion'] ?? '', 0, 80, '…')) ?>
                        </p>

                        <div class="mv-precio-row">
                            <div>
                                <span class="mv-precio-label">Adulto desde</span>
                                <span class="mv-precio-val">
                                    $<?= number_format((float)$d['precio'], 0, '.', ',') ?>
                                </span>
                            </div>
                            <?php if (!empty($d['precio_nino'])): ?>
                            <div>
                                <span class="mv-precio-label">Niño desde</span>
                                <span class="mv-precio-nino">
                                    $<?= number_format((float)$d['precio_nino'], 0, '.', ',') ?>
                                </span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="mv-destino-check">
                            <i class="fas fa-check"></i> Seleccionado
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="paso-nav">
                <span></span>
                <button class="btn-siguiente" onclick="irPasoMV(2)">
                    Siguiente <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- ══ PASO 2: FECHAS ════════════════════════════════════ -->
        <div class="paso oculto" id="paso-2">
            <h2 class="paso-titulo">¿Cuándo viajas?</h2>

            <div class="form-grid">
                <div class="form-group">
                    <label><i class="fas fa-plane-departure"></i> Fecha de salida *</label>
                    <input type="date" id="mv-fecha-salida" class="finput" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-plane-arrival"></i> Fecha de regreso</label>
                    <input type="date" id="mv-fecha-regreso" class="finput">
                </div>
            </div>

            <!-- Info calculada de días -->
            <div class="mv-duracion-box" id="mvDuracionBox" style="display:none;">
                <i class="fas fa-clock"></i>
                <span id="mvDuracionTexto">—</span>
            </div>

            <!-- Flexibilidad de fechas -->
            <div class="mv-opciones-grid">
                <label class="mv-opcion-check">
                    <input type="checkbox" id="mv-flex-fechas">
                    <div class="mv-opcion-body">
                        <i class="fas fa-calendar-week"></i>
                        <div>
                            <strong>Fechas flexibles</strong>
                            <span>Acepto salir ±2 días de mi fecha indicada</span>
                        </div>
                    </div>
                </label>
                <label class="mv-opcion-check">
                    <input type="checkbox" id="mv-solo-ida">
                    <div class="mv-opcion-body">
                        <i class="fas fa-plane"></i>
                        <div>
                            <strong>Solo ida</strong>
                            <span>No necesito vuelo de regreso</span>
                        </div>
                    </div>
                </label>
            </div>

            <div class="paso-nav">
                <button class="btn-anterior" onclick="irPasoMV(1)">
                    <i class="fas fa-arrow-left"></i> Anterior
                </button>
                <button class="btn-siguiente" onclick="irPasoMV(3)">
                    Siguiente <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- ══ PASO 3: VIAJEROS ══════════════════════════════════ -->
        <div class="paso oculto" id="paso-3">
            <h2 class="paso-titulo">¿Quiénes viajan?</h2>

            <div class="viajeros-wrap">
                <div class="viajero-row">
                    <div class="viajero-label">
                        <strong>Adultos</strong>
                        <span>12 años en adelante</span>
                    </div>
                    <div class="counter">
                        <button class="counter-btn" onclick="cambiarMV('adultos',-1)">
                            <i class="fas fa-minus"></i>
                        </button>
                        <span class="counter-val" id="mv-val-adultos">1</span>
                        <button class="counter-btn" onclick="cambiarMV('adultos',1)">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <div class="viajero-row">
                    <div class="viajero-label">
                        <strong>Niños</strong>
                        <span>2 a 11 años — precio especial</span>
                    </div>
                    <div class="counter">
                        <button class="counter-btn" onclick="cambiarMV('ninos',-1)">
                            <i class="fas fa-minus"></i>
                        </button>
                        <span class="counter-val" id="mv-val-ninos">0</span>
                        <button class="counter-btn" onclick="cambiarMV('ninos',1)">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <div class="viajero-row">
                    <div class="viajero-label">
                        <strong>Bebés</strong>
                        <span>Menores de 2 años — sin cargo</span>
                    </div>
                    <div class="counter">
                        <button class="counter-btn" onclick="cambiarMV('bebes',-1)">
                            <i class="fas fa-minus"></i>
                        </button>
                        <span class="counter-val" id="mv-val-bebes">0</span>
                        <button class="counter-btn" onclick="cambiarMV('bebes',1)">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <div class="viajero-total-box">
                    <i class="fas fa-users"></i>
                    <span>Total de viajeros: <strong id="mv-total-viajeros">1</strong></span>
                </div>
            </div>

            <!-- Tipo de habitación -->
            <h3 class="mv-subseccion">Tipo de habitación</h3>
            <div class="mv-habitacion-grid">
                <?php
                $habitaciones = [
                    ['val'=>'Sencilla',  'icono'=>'fa-bed',          'desc'=>'1 cama individual'],
                    ['val'=>'Doble',     'icono'=>'fa-bed',          'desc'=>'2 camas o cama doble'],
                    ['val'=>'Suite',     'icono'=>'fa-star',         'desc'=>'Suite de lujo'],
                    ['val'=>'Familiar',  'icono'=>'fa-people-roof',  'desc'=>'Ideal para familias'],
                ];
                foreach ($habitaciones as $h):
                ?>
                <label class="mv-hab-card" onclick="seleccionarHab(this,'<?= $h['val'] ?>')">
                    <input type="radio" name="habitacion" value="<?= $h['val'] ?>"
                           <?= $h['val'] === 'Doble' ? 'checked' : '' ?>>
                    <div class="mv-hab-body">
                        <i class="fas <?= $h['icono'] ?>"></i>
                        <strong><?= $h['val'] ?></strong>
                        <span><?= $h['desc'] ?></span>
                    </div>
                </label>
                <?php endforeach; ?>
            </div>

            <!-- Necesidades especiales -->
            <h3 class="mv-subseccion">Necesidades especiales</h3>
            <div class="mv-checks-grid">
                <?php
                $necesidades = [
                    ['id'=>'mv-acc',     'icono'=>'fa-wheelchair',         'label'=>'Accesibilidad para movilidad reducida'],
                    ['id'=>'mv-vegano',  'icono'=>'fa-leaf',               'label'=>'Alimentación vegana/vegetariana'],
                    ['id'=>'mv-alerg',   'icono'=>'fa-triangle-exclamation','label'=>'Alergias alimentarias'],
                    ['id'=>'mv-mascota', 'icono'=>'fa-paw',                'label'=>'Viajo con mascota'],
                    ['id'=>'mv-celeb',   'icono'=>'fa-champagne-glasses',  'label'=>'Celebración especial'],
                    ['id'=>'mv-luna',    'icono'=>'fa-heart',              'label'=>'Luna de miel / Aniversario'],
                ];
                foreach ($necesidades as $n):
                ?>
                <label class="mv-check-item">
                    <input type="checkbox" id="<?= $n['id'] ?>" value="<?= $n['label'] ?>">
                    <i class="fas <?= $n['icono'] ?>"></i>
                    <span><?= $n['label'] ?></span>
                </label>
                <?php endforeach; ?>
            </div>

            <div class="paso-nav">
                <button class="btn-anterior" onclick="irPasoMV(2)">
                    <i class="fas fa-arrow-left"></i> Anterior
                </button>
                <button class="btn-siguiente" onclick="irPasoMV(4)">
                    Siguiente <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- ══ PASO 4: EXTRAS ════════════════════════════════════ -->
        <div class="paso oculto" id="paso-4">
            <h2 class="paso-titulo">Personaliza tu experiencia</h2>

            <!-- Actividades extra del destino seleccionado -->
            <div id="mvExtrasDestino">
                <h3 class="mv-subseccion">
                    <i class="fas fa-star" style="color:#f59e0b;"></i>
                    Actividades incluidas en tu destino
                </h3>
                <div class="mv-extras-grid" id="gridExtrasDestino">
                    <p class="mv-hint">Selecciona un destino para ver las actividades disponibles.</p>
                </div>
            </div>

            <!-- Servicios adicionales -->
            <h3 class="mv-subseccion" style="margin-top:3rem;">
                <i class="fas fa-plus-circle" style="color:#1a56db;"></i>
                Servicios adicionales
            </h3>
            <div class="mv-servicios-grid">
                <?php
                $servicios = [
                    ['id'=>'sv-traslado',  'icono'=>'fa-van-shuttle',      'label'=>'Traslado aeropuerto',     'precio'=>500],
                    ['id'=>'sv-seguro',    'icono'=>'fa-shield-halved',     'label'=>'Seguro de viaje',         'precio'=>800],
                    ['id'=>'sv-guia',      'icono'=>'fa-person-hiking',     'label'=>'Guía turístico privado',  'precio'=>1200],
                    ['id'=>'sv-foto',      'icono'=>'fa-camera',            'label'=>'Sesión fotográfica',      'precio'=>1500],
                    ['id'=>'sv-spa',       'icono'=>'fa-spa',               'label'=>'Día de spa',              'precio'=>900],
                    ['id'=>'sv-cena',      'icono'=>'fa-utensils',          'label'=>'Cena romántica privada',  'precio'=>1100],
                    ['id'=>'sv-snorkel',   'icono'=>'fa-water',             'label'=>'Tour de snorkel',         'precio'=>700],
                    ['id'=>'sv-bike',      'icono'=>'fa-bicycle',           'label'=>'Tour en bicicleta',       'precio'=>400],
                ];
                foreach ($servicios as $sv):
                ?>
                <label class="mv-servicio-card" id="lbl-<?= $sv['id'] ?>">
                    <input type="checkbox" id="<?= $sv['id'] ?>" value="<?= $sv['precio'] ?>"
                           data-label="<?= $sv['label'] ?>" onchange="actualizarResumenMV()">
                    <div class="mv-servicio-body">
                        <i class="fas <?= $sv['icono'] ?>"></i>
                        <div>
                            <strong><?= $sv['label'] ?></strong>
                            <span>+ $<?= number_format($sv['precio'], 0, '.', ',') ?> MXN por persona</span>
                        </div>
                        <div class="mv-servicio-check"><i class="fas fa-check"></i></div>
                    </div>
                </label>
                <?php endforeach; ?>
            </div>

            <!-- Preferencias de contacto + notas -->
            <h3 class="mv-subseccion" style="margin-top:3rem;">
                <i class="fas fa-comments" style="color:#1a56db;"></i>
                Preferencias de contacto
            </h3>
            <div class="form-grid">
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> ¿Cómo prefieres que te contactemos?</label>
                    <select id="mv-metodo-contacto" class="finput">
                        <option value="WhatsApp">WhatsApp</option>
                        <option value="Email">Email</option>
                        <option value="Llamada">Llamada telefónica</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-clock"></i> Horario preferido de contacto</label>
                    <select id="mv-horario" class="finput">
                        <option value="Mañana (8–12h)">Mañana (8–12h)</option>
                        <option value="Tarde (12–18h)">Tarde (12–18h)</option>
                        <option value="Noche (18–21h)">Noche (18–21h)</option>
                        <option value="Cualquier horario">Cualquier horario</option>
                    </select>
                </div>
                <div class="form-group full">
                    <label><i class="fas fa-pencil"></i> Notas adicionales / Solicitudes especiales</label>
                    <textarea id="mv-notas" class="finput" rows="3"
                              placeholder="Cuéntanos qué hace especial este viaje para ti..."></textarea>
                </div>
            </div>

            <!-- Presupuesto -->
            <h3 class="mv-subseccion">
                <i class="fas fa-wallet" style="color:#1a56db;"></i>
                Tu presupuesto aproximado
            </h3>
            <div class="mv-presupuesto-grid">
                <?php
                $presupuestos = [
                    ['val'=>'Menos de $10,000',    'icono'=>'fa-seedling'],
                    ['val'=>'$10,000 – $25,000',   'icono'=>'fa-tree'],
                    ['val'=>'$25,000 – $50,000',   'icono'=>'fa-mountain'],
                    ['val'=>'Más de $50,000',       'icono'=>'fa-gem'],
                    ['val'=>'Sin límite',           'icono'=>'fa-infinity'],
                ];
                foreach ($presupuestos as $p):
                ?>
                <label class="mv-presup-card" onclick="seleccionarPresup(this,'<?= $p['val'] ?>')">
                    <input type="radio" name="presupuesto" value="<?= $p['val'] ?>">
                    <i class="fas <?= $p['icono'] ?>"></i>
                    <span><?= $p['val'] ?></span>
                </label>
                <?php endforeach; ?>
            </div>

            <div class="paso-nav">
                <button class="btn-anterior" onclick="irPasoMV(3)">
                    <i class="fas fa-arrow-left"></i> Anterior
                </button>
                <button class="btn-siguiente" onclick="irPasoMV(5)">
                    Revisar y confirmar <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- ══ PASO 5: CONFIRMAR ═════════════════════════════════ -->
        <div class="paso oculto" id="paso-5">
            <h2 class="paso-titulo">Revisa tu solicitud</h2>

            <!-- Bloque datos del viajero -->
            <div class="mv-confirm-section">
                <h3><i class="fas fa-user-circle"></i> Datos del viajero</h3>
                <div class="mv-confirm-grid">
                    <div class="mv-confirm-item">
                        <span>Nombre</span>
                        <strong><?= htmlspecialchars($usuario['nombre_completo']) ?></strong>
                    </div>
                    <div class="mv-confirm-item">
                        <span>Email</span>
                        <strong><?= htmlspecialchars($usuario['email']) ?></strong>
                    </div>
                    <div class="mv-confirm-item">
                        <span>Teléfono</span>
                        <strong><?= htmlspecialchars($usuario['telefono'] ?? '—') ?></strong>
                    </div>
                </div>
            </div>

            <!-- Resumen del viaje -->
            <div class="mv-confirm-section">
                <h3><i class="fas fa-map-marked-alt"></i> Tu viaje</h3>
                <div class="mv-confirm-grid">
                    <div class="mv-confirm-item"><span>Destino</span><strong id="conf-mv-destino">—</strong></div>
                    <div class="mv-confirm-item"><span>Salida</span><strong id="conf-mv-salida">—</strong></div>
                    <div class="mv-confirm-item"><span>Regreso</span><strong id="conf-mv-regreso">—</strong></div>
                    <div class="mv-confirm-item"><span>Adultos</span><strong id="conf-mv-adultos">—</strong></div>
                    <div class="mv-confirm-item"><span>Niños</span><strong id="conf-mv-ninos">—</strong></div>
                    <div class="mv-confirm-item"><span>Bebés</span><strong id="conf-mv-bebes">—</strong></div>
                    <div class="mv-confirm-item"><span>Habitación</span><strong id="conf-mv-hab">—</strong></div>
                    <div class="mv-confirm-item"><span>Contacto</span><strong id="conf-mv-contacto">—</strong></div>
                    <div class="mv-confirm-item"><span>Horario</span><strong id="conf-mv-horario">—</strong></div>
                    <div class="mv-confirm-item"><span>Presupuesto</span><strong id="conf-mv-presup">—</strong></div>
                </div>
            </div>

            <!-- Servicios seleccionados -->
            <div class="mv-confirm-section" id="confServiciosSection">
                <h3><i class="fas fa-concierge-bell"></i> Servicios adicionales</h3>
                <div id="conf-mv-servicios" class="mv-confirm-tags"></div>
            </div>

            <!-- Necesidades -->
            <div class="mv-confirm-section" id="confNecesidadesSection" style="display:none;">
                <h3><i class="fas fa-heart"></i> Necesidades especiales</h3>
                <div id="conf-mv-necesidades" class="mv-confirm-tags"></div>
            </div>

            <!-- Notas -->
            <div class="mv-confirm-section" id="confNotasSection" style="display:none;">
                <h3><i class="fas fa-pencil"></i> Notas adicionales</h3>
                <p id="conf-mv-notas" style="font-size:1.4rem;color:#374151;line-height:1.6;"></p>
            </div>

            <!-- Términos -->
            <label class="mv-terminos">
                <input type="checkbox" id="mv-acepto-terminos">
                <span>
                    Acepto que esta es una <strong>solicitud de cotización</strong>.
                    Un asesor de Remolino's Tours me contactará en menos de 24 horas
                    para confirmar disponibilidad y precio final.
                </span>
            </label>

            <div class="paso-nav">
                <button class="btn-anterior" onclick="irPasoMV(4)">
                    <i class="fas fa-arrow-left"></i> Anterior
                </button>
                <button class="btn-confirmar" id="btnConfirmarMV" onclick="confirmarMV()">
                    <i class="fas fa-paper-plane"></i> Enviar Solicitud
                </button>
            </div>
        </div>

        <!-- ══ PASO 6: ÉXITO ═════════════════════════════════════ -->
        <div class="paso oculto" id="paso-6">
            <div class="exito-wrap">
                <div class="exito-icon"><i class="fas fa-check"></i></div>
                <span class="exito-label">¡Solicitud enviada!</span>
                <h2>Tu viaje está en camino</h2>
                <p>Hemos recibido tu solicitud personalizada para
                   <strong id="mv-exito-destino">—</strong>.</p>
                <p class="exito-sub">
                    Un asesor se comunicará contigo en menos de <strong>24 horas</strong>
                    vía <strong id="mv-exito-contacto">—</strong> al
                    <strong><?= htmlspecialchars($usuario['telefono'] ?? $usuario['email']) ?></strong>.
                </p>
                <div class="exito-folio-box">
                    <span>Folio de solicitud</span>
                    <strong id="mv-exito-folio">—</strong>
                </div>
                <div class="exito-total-box">
                    <span>Total estimado base</span>
                    <strong id="mv-exito-total">—</strong>
                </div>
                <p style="font-size:1.2rem;color:#9ca3af;margin-top:-.5rem;">
                    *Precio final sujeto a disponibilidad y confirmación del asesor.
                </p>
                <div class="exito-btns">
                    <button class="btn-pdf" onclick="generarPDFMV()">
                        <i class="fas fa-file-pdf"></i> Descargar PDF
                    </button>
                    <a href="reserva.php" class="btn-nueva">
                        <i class="fas fa-box-open"></i> Ver Paquetes
                    </a>
                </div>
            </div>
        </div>

    </div><!-- /reserva-form-wrap -->

    <!-- ── RESUMEN LATERAL ──────────────────────────────────── -->
    <div class="resumen-lateral">
        <div class="resumen-card">
            <h3>Tu Itinerario</h3>

            <div id="mv-resumen-vacio" class="resumen-vacio">
                <i class="fas fa-suitcase-rolling"></i>
                <p>Selecciona un destino para ver el resumen</p>
            </div>

            <div id="mv-resumen-contenido" class="oculto">
                <img id="mv-res-img" src="" alt="" class="resumen-img">

                <div class="resumen-info">
                    <h4 id="mv-res-nombre">—</h4>
                    <p id="mv-res-salida" style="font-size:1.2rem;color:#6b7280;"></p>
                </div>

                <!-- Desglose precios -->
                <div class="resumen-desglose">
                    <div class="res-row">
                        <span>Precio adulto</span>
                        <strong id="mv-res-precio-adulto">—</strong>
                    </div>
                    <div class="res-row" id="mv-row-ninos" style="display:none;">
                        <span id="mv-lbl-ninos">Niños</span>
                        <strong id="mv-res-precio-ninos">—</strong>
                    </div>
                    <div class="res-row mv-res-row-extras" id="mv-row-extras" style="display:none;">
                        <span>Extras</span>
                        <strong id="mv-res-extras">—</strong>
                    </div>
                    <div class="res-row mv-res-row-servicios" id="mv-row-servicios" style="display:none;">
                        <span>Servicios</span>
                        <strong id="mv-res-servicios">—</strong>
                    </div>
                </div>

                <!-- Tags de servicios activos -->
                <div id="mv-res-tags" class="mv-res-tags-wrap"></div>

                <!-- Total -->
                <div class="resumen-total">
                    <span>Total estimado</span>
                    <strong id="mv-res-total">—</strong>
                </div>

                <p class="mv-res-nota">
                    *Precio estimado. Un asesor confirmará el valor exacto.
                </p>
            </div>
        </div>

        <!-- Card contacto rápido -->
        <div class="resumen-card mv-contacto-card">
            <h3 style="font-size:1.6rem;">¿Tienes dudas?</h3>
            <p style="font-size:1.3rem;color:#6b7280;margin-bottom:1.5rem;line-height:1.6;">
                Nuestros asesores están disponibles para ayudarte a armar el viaje perfecto.
            </p>
            <a href="https://wa.me/524495989826" target="_blank" class="mv-wa-btn">
                <i class="fab fa-whatsapp"></i> Hablar por WhatsApp
            </a>
        </div>
    </div>

</div><!-- /reserva-layout -->

<?php include $_SERVER['DOCUMENT_ROOT'] . '/Agencia_Remolinos/componentes/footer/footer.php'; ?>

<!-- Datos PHP → JS -->
<script>
window.MiViajeConfig = {
    usuario: {
        id:       <?= json_encode($id_usuario) ?>,
        nombre:   <?= json_encode($usuario['nombre_completo']) ?>,
        email:    <?= json_encode($usuario['email']) ?>,
        telefono: <?= json_encode($usuario['telefono'] ?? '') ?>
    },
    extras: <?= json_encode($extras) ?>,
    rutas: {
        imagenes: '/Agencia_Remolinos/assets/imagenes/',
        guardar:  '/Agencia_Remolinos/componentes/Reserva/guardar_mi_viaje.php'
    }
};
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="/Agencia_Remolinos/componentes/Reserva/mi_viaje.js" defer></script>

</body>
</html>