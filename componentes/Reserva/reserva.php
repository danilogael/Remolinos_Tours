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
$idPreseleccionado = isset($_GET['id_destino']) ? (int)$_GET['id_destino'] : 0;
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
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reserva tu Viaje - Remolinos Tours</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <link rel="stylesheet" href="/Agencia_Remolinos/componentes/header/header.css">
  <link rel="stylesheet" href="/Agencia_Remolinos/componentes/footer/footer.css">
  <link rel="stylesheet" href="/Agencia_Remolinos/componentes/Reserva/reserva.css">
</head>
<body>

<?php include __DIR__ . '/../../componentes/header/header.php'; ?>

<main class="reserva-main">

  <!-- HERO -->
  <section class="reserva-hero">
    <div class="reserva-hero-overlay"></div>
    <div class="reserva-hero-content">
      <span class="reserva-hero-label">RESERVA TU VIAJE</span>
      <h1>Comienza Tu<br><span>Aventura</span></h1>
      <p>Sigue los pasos y reserva tu experiencia perfecta</p>
    </div>
  </section>

  <!-- STEPPER -->
  <div class="stepper-wrap">
    <div class="stepper">
      <div class="step active" id="step-dot-1">
        <div class="step-circle"><i class="fas fa-map-marked-alt"></i></div>
        <span>Paquete</span>
      </div>
      <div class="step-line"></div>
      <div class="step" id="step-dot-2">
        <div class="step-circle"><i class="fas fa-users"></i></div>
        <span>Viajeros</span>
      </div>
      <div class="step-line"></div>
      <div class="step" id="step-dot-3">
        <div class="step-circle"><i class="fas fa-calendar-alt"></i></div>
        <span>Fechas</span>
      </div>
      <div class="step-line"></div>
      <div class="step" id="step-dot-4">
        <div class="step-circle"><i class="fas fa-check"></i></div>
        <span>Confirmar</span>
      </div>
    </div>
  </div>

  <!-- CONTENIDO PRINCIPAL -->
  <div class="reserva-layout">

    <!-- FORMULARIO -->
    <div class="reserva-form-wrap">

      <!-- PASO 1: Seleccionar paquete -->
      <div class="paso" id="paso-1">
        <h2 class="paso-titulo">Selecciona tu Paquete</h2>
        <div class="paquetes-grid">
          <?php foreach ($destinos as $d): ?>
          <div class="paquete-card" 
               data-id="<?= $d['id'] ?>"
               data-nombre="<?= htmlspecialchars($d['nombre']) ?>"
               data-precio="<?= !empty($d['es_oferta']) && !empty($d['precio_oferta']) ? $d['precio_oferta'] : $d['precio'] ?>"
               data-precio-normal="<?= $d['precio'] ?>"
               data-imagen="<?= htmlspecialchars($d['foto_portada'] ?? '') ?>"
               data-descripcion="<?= htmlspecialchars($d['descripcion'] ?? '') ?>"
               data-permite-ninos="<?= (int)($d['permite_ninos'] ?? 1) ?>"
               data-min-adultos="<?= (int)($d['min_adultos'] ?? 1) ?>"
               data-max-adultos="<?= (int)($d['max_adultos'] ?? 10) ?>"
               data-max-ninos="<?= (int)($d['max_ninos'] ?? 6) ?>"
               data-cupo-total="<?= (int)($d['cupo_total'] ?? 20) ?>"
               data-tipo-cupo="<?= htmlspecialchars($d['tipo_cupo'] ?? 'flexible') ?>"
               onclick="seleccionarPaquete(this)">
            <div class="paquete-img-wrap">
              <img src="/Agencia_Remolinos/assets/imagenes/<?= htmlspecialchars($d['foto_portada'] ?? 'default.png') ?>" 
                   alt="<?= htmlspecialchars($d['nombre']) ?>"
                   onerror="this.src='https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=400'">
              <span class="paquete-tipo"><?= htmlspecialchars($d['tipo_trayecto'] ?? 'Redondo') ?></span>
            </div>
            <div class="paquete-info">
              <h3><?= htmlspecialchars($d['nombre']) ?></h3>
              <p><?= htmlspecialchars(substr($d['descripcion'], 0, 60)) ?>...</p>
              <div class="paquete-precio">
                <strong>$<?= number_format(!empty($d['es_oferta']) && !empty($d['precio_oferta']) ? $d['precio_oferta'] : $d['precio'], 0, '.', ',') ?></strong>
                <span>por persona</span>
              </div>
            </div>
            <div class="paquete-selected-bar"><i class="fas fa-check"></i> Seleccionado</div>
          </div>
          <?php endforeach; ?>
        </div>
        <div class="paso-nav">
          <span></span>
          <button class="btn-siguiente" onclick="irPaso(2)" id="btn-paso1">Siguiente <i class="fas fa-arrow-right"></i></button>
        </div>
      </div>

      <!-- PASO 2: Viajeros -->
      <div class="paso oculto" id="paso-2">
        <h2 class="paso-titulo">Número de Viajeros</h2>
        <div class="viajeros-wrap">
          <div class="viajero-row">
            <div class="viajero-label">
              <strong>Adultos</strong>
              <span>12+ años</span>
            </div>
            <div class="counter">
              <button class="counter-btn" onclick="cambiarCantidad('adultos', -1)"><i class="fas fa-minus"></i></button>
              <span class="counter-val" id="val-adultos">1</span>
              <button class="counter-btn" onclick="cambiarCantidad('adultos', 1)"><i class="fas fa-plus"></i></button>
            </div>
          </div>
          <div class="viajero-row">
            <div class="viajero-label">
              <strong>Niños</strong>
              <span>2–11 años — 50% descuento</span>
            </div>
            <div class="counter">
              <button class="counter-btn" onclick="cambiarCantidad('ninos', -1)"><i class="fas fa-minus"></i></button>
              <span class="counter-val" id="val-ninos">0</span>
              <button class="counter-btn" onclick="cambiarCantidad('ninos', 1)"><i class="fas fa-plus"></i></button>
            </div>
          </div>
          <div class="viajero-total-box">
            <i class="fas fa-users"></i>
            <span>Total de viajeros: <strong id="total-viajeros">1</strong></span>
          </div>
        </div>
        <div class="paso-nav">
          <button class="btn-anterior" onclick="irPaso(1)"><i class="fas fa-arrow-left"></i> Anterior</button>
          <button class="btn-siguiente" onclick="irPaso(3)">Siguiente <i class="fas fa-arrow-right"></i></button>
        </div>
      </div>

      <!-- PASO 3: Fechas y contacto -->
      <div class="paso oculto" id="paso-3">
        <h2 class="paso-titulo">Fechas y Datos de Contacto</h2>
        <div class="form-grid">
          <div class="form-group">
            <label><i class="fas fa-plane-departure"></i> Fecha de salida *</label>
            <input type="date" id="fecha-salida" class="finput" required>
          </div>
          <div class="form-group">
            <label><i class="fas fa-plane-arrival"></i> Fecha de regreso</label>
            <input type="date" id="fecha-regreso" class="finput">
          </div>
          <div class="form-group full">
            <label><i class="fas fa-user"></i> Nombre completo *</label>
            <input type="text" id="nombre-contacto" class="finput" 
                   value="<?= htmlspecialchars($usuario['nombre_completo']) ?>" readonly>
          </div>
          <div class="form-group">
            <label><i class="fas fa-envelope"></i> Correo electrónico *</label>
            <input type="email" id="email-contacto" class="finput" 
                   value="<?= htmlspecialchars($usuario['email']) ?>" readonly>
          </div>
          <div class="form-group">
            <label><i class="fas fa-phone"></i> Teléfono</label>
            <input type="tel" id="telefono-contacto" class="finput" 
                   value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>" readonly>
          </div>
          <div class="form-group">
            <label><i class="fas fa-comments"></i> ¿Cómo prefieres que te contactemos?</label>
            <select id="metodo-contacto" class="finput">
              <option value="whatsapp">WhatsApp</option>
              <option value="email">Email</option>
              <option value="telefono">Teléfono</option>
            </select>
          </div>
          <div class="form-group full">
            <label><i class="fas fa-star"></i> Solicitudes especiales</label>
            <textarea id="solicitudes" class="finput" rows="3" 
                      placeholder="Alergias, preferencias, celebraciones especiales..."></textarea>
          </div>
        </div>
        <div class="paso-nav">
          <button class="btn-anterior" onclick="irPaso(2)"><i class="fas fa-arrow-left"></i> Anterior</button>
          <button class="btn-siguiente" onclick="irPaso(4)">Siguiente <i class="fas fa-arrow-right"></i></button>
        </div>
      </div>

      <!-- PASO 4: Confirmar -->
      <div class="paso oculto" id="paso-4">
        <h2 class="paso-titulo">Confirma tu Reserva</h2>
        <div class="confirmacion-tabla">
          <div class="conf-row"><span>Paquete</span><strong id="conf-paquete">—</strong></div>
          <div class="conf-row"><span>Destino</span><strong id="conf-descripcion">—</strong></div>
          <div class="conf-row"><span>Adultos</span><strong id="conf-adultos">—</strong></div>
          <div class="conf-row"><span>Niños</span><strong id="conf-ninos">—</strong></div>
          <div class="conf-row"><span>Fecha de salida</span><strong id="conf-salida">—</strong></div>
          <div class="conf-row"><span>Fecha de regreso</span><strong id="conf-regreso">—</strong></div>
          <div class="conf-row"><span>Nombre</span><strong id="conf-nombre">—</strong></div>
          <div class="conf-row"><span>Email</span><strong id="conf-email">—</strong></div>
          <div class="conf-row"><span>Teléfono</span><strong id="conf-telefono">—</strong></div>
          <div class="conf-row"><span>Contacto preferido</span><strong id="conf-contacto">—</strong></div>
        </div>
        <div class="paso-nav">
          <button class="btn-anterior" onclick="irPaso(3)"><i class="fas fa-arrow-left"></i> Anterior</button>
          <button class="btn-confirmar" onclick="confirmarReserva()">
            <i class="fas fa-check"></i> Confirmar Reserva
          </button>
        </div>
      </div>

      <!-- PASO 5: Éxito -->
      <div class="paso oculto" id="paso-5">
        <div class="exito-wrap">
          <div class="exito-icon"><i class="fas fa-check"></i></div>
          <span class="exito-label">Reservas</span>
          <h2>¡Reserva Enviada!</h2>
          <p>Tu solicitud de reserva para <strong id="exito-paquete">—</strong> ha sido recibida.</p>
          <p class="exito-sub">Recibirás una confirmación en <strong id="exito-email">—</strong> en las próximas 24 horas.<br>Un asesor de Remolinos Tours se pondrá en contacto contigo a la brevedad.</p>
          <div class="exito-folio-box">
            <span>Folio de reserva</span>
            <strong id="exito-folio">—</strong>
          </div>
          <div class="exito-total-box">
            <span>Total estimado</span>
            <strong id="exito-total">—</strong>
          </div>
          <div class="exito-btns">
            <button class="btn-pdf" onclick="generarPDF()">
              <i class="fas fa-file-pdf"></i> Descargar PDF
            </button>
            <button class="btn-nueva" onclick="nuevaReserva()">
              <i class="fas fa-plus"></i> Nueva Reserva
            </button>
          </div>
        </div>
      </div>

    </div>

    <!-- RESUMEN LATERAL -->
    <div class="resumen-lateral" id="resumen-lateral">
      <div class="resumen-card">
        <h3>Resumen</h3>
        <div id="resumen-vacio" class="resumen-vacio">
          <i class="fas fa-map-marked-alt"></i>
          <p>Selecciona un paquete para ver el resumen</p>
        </div>
        <div id="resumen-contenido" class="oculto">
          <img id="res-img" src="" alt="" class="resumen-img">
          <div class="resumen-info">
            <h4 id="res-nombre">—</h4>
            <p id="res-desc">—</p>
          </div>
          <div class="resumen-desglose">
            <div class="res-row"><span>Precio por persona</span><strong id="res-precio-pp">—</strong></div>
            <div class="res-row" id="res-row-adultos" style="display:none"><span id="res-label-adultos">Adultos × 1</span><strong id="res-precio-adultos">—</strong></div>
            <div class="res-row" id="res-row-ninos" style="display:none"><span id="res-label-ninos">Niños × 0</span><strong id="res-precio-ninos">—</strong></div>
          </div>
          <div class="resumen-total">
            <span>Total</span>
            <strong id="res-total">—</strong>
          </div>
        </div>
      </div>
    </div>

  </div><!-- /reserva-layout -->
</main>

<?php include __DIR__ . '/../../componentes/footer/footer.php'; ?>
<script>
    // Este es el puente: PHP escribe aquí y JS lo lee después
    window.AppConfig = {
        usuario: {
            id: <?= json_encode($id_usuario) ?>,
            nombre: <?= json_encode($usuario['nombre_completo']) ?>,
            email: <?= json_encode($usuario['email']) ?>,
            telefono: <?= json_encode($usuario['telefono'] ?? '') ?>
        },
        preseleccionado: <?= json_encode($idPreseleccionado) ?>,
        rutas: {
            imagenes: '/Agencia_Remolinos/assets/imagenes/destinos/',
            guardar: '/Agencia_Remolinos/componentes/Reserva/guardar_reserva.php'
        }
    };
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="reserva.js" defer></script>

</body>
</html>
