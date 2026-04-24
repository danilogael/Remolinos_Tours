<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Redirigir si no está logueado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: /Agencia_Remolinos/Login_APP/login.php');
    exit;
}

require_once __DIR__ . '/../../Database/conexion.php';

// Cargar destinos activos
$destinos = [];
$res = $conn->query("SELECT * FROM destinos WHERE activo = 1 ORDER BY nombre ASC");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $destinos[] = $row;
    }
}

// Datos del usuario logueado
$id_usuario = $_SESSION['id_usuario'];
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();
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
  <link rel="stylesheet" href="reserva.css">
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
               data-precio="<?= $d['precio'] ?>"
               data-imagen="<?= htmlspecialchars($d['imagen']) ?>"
               data-descripcion="<?= htmlspecialchars($d['descripcion']) ?>"
               onclick="seleccionarPaquete(this)">
            <div class="paquete-img-wrap">
              <img src="/Agencia_Remolinos/assets/imagenes/destinos/<?= htmlspecialchars($d['imagen']) ?>" 
                   alt="<?= htmlspecialchars($d['nombre']) ?>"
                   onerror="this.src='https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=400'">
              <span class="paquete-tipo <?= $d['tipo'] ?>"><?= ucfirst($d['tipo']) ?></span>
            </div>
            <div class="paquete-info">
              <h3><?= htmlspecialchars($d['nombre']) ?></h3>
              <p><?= htmlspecialchars(substr($d['descripcion'], 0, 60)) ?>...</p>
              <div class="paquete-precio">
                <strong>$<?= number_format($d['precio'], 0, '.', ',') ?></strong>
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
// ── Estado global ──
const state = {
  pasoActual: 1,
  paquete: null,
  adultos: 1,
  ninos: 0,
  fechaSalida: '',
  fechaRegreso: '',
  metodoContacto: 'whatsapp',
  solicitudes: '',
  folio: '',
  total: 0,
  usuario: {
    nombre: '<?= addslashes($usuario['nombre_completo']) ?>',
    email:  '<?= addslashes($usuario['email']) ?>',
    telefono: '<?= addslashes($usuario['telefono'] ?? '') ?>',
    id: <?= $id_usuario ?>
  }
};

// ── Seleccionar paquete ──
function seleccionarPaquete(el) {
  document.querySelectorAll('.paquete-card').forEach(c => c.classList.remove('selected'));
  el.classList.add('selected');
  state.paquete = {
    id:          el.dataset.id,
    nombre:      el.dataset.nombre,
    precio:      parseFloat(el.dataset.precio),
    imagen:      el.dataset.imagen,
    descripcion: el.dataset.descripcion
  };
  actualizarResumen();
}

// ── Cambiar cantidad de viajeros ──
function cambiarCantidad(tipo, delta) {
  if (tipo === 'adultos') {
    state.adultos = Math.max(1, state.adultos + delta);
    document.getElementById('val-adultos').textContent = state.adultos;
  } else {
    state.ninos = Math.max(0, state.ninos + delta);
    document.getElementById('val-ninos').textContent = state.ninos;
  }
  document.getElementById('total-viajeros').textContent = state.adultos + state.ninos;
  actualizarResumen();
}

// ── Actualizar resumen lateral ──
function actualizarResumen() {
  if (!state.paquete) return;
  document.getElementById('resumen-vacio').classList.add('oculto');
  document.getElementById('resumen-contenido').classList.remove('oculto');

  const p = state.paquete;
  const precioNino = p.precio * 0.5;
  const totalAdultos = p.precio * state.adultos;
  const totalNinos   = precioNino * state.ninos;
  state.total = totalAdultos + totalNinos;

  document.getElementById('res-img').src = `/Agencia_Remolinos/assets/imagenes/destinos/${p.imagen}`;
  document.getElementById('res-nombre').textContent = p.nombre;
  document.getElementById('res-desc').textContent   = p.descripcion.substring(0, 60) + '...';
  document.getElementById('res-precio-pp').textContent = `$${p.precio.toLocaleString()}`;

  // Adultos
  const rowAdultos = document.getElementById('res-row-adultos');
  rowAdultos.style.display = 'flex';
  document.getElementById('res-label-adultos').textContent = `Adultos × ${state.adultos}`;
  document.getElementById('res-precio-adultos').textContent = `$${totalAdultos.toLocaleString()}`;

  // Niños
  const rowNinos = document.getElementById('res-row-ninos');
  if (state.ninos > 0) {
    rowNinos.style.display = 'flex';
    document.getElementById('res-label-ninos').textContent = `Niños × ${state.ninos} (50% dto)`;
    document.getElementById('res-precio-ninos').textContent = `$${totalNinos.toLocaleString()}`;
  } else {
    rowNinos.style.display = 'none';
  }

  document.getElementById('res-total').textContent = `$${state.total.toLocaleString()}`;
}

// ── Navegar entre pasos ──
function irPaso(num) {
  // Validaciones
  if (num === 2 && !state.paquete) {
    alert('Por favor selecciona un paquete para continuar.');
    return;
  }
  if (num === 4) {
    const fechaSalida = document.getElementById('fecha-salida').value;
    if (!fechaSalida) {
      alert('Por favor ingresa la fecha de salida.');
      return;
    }
    state.fechaSalida   = fechaSalida;
    state.fechaRegreso  = document.getElementById('fecha-regreso').value;
    state.metodoContacto = document.getElementById('metodo-contacto').value;
    state.solicitudes   = document.getElementById('solicitudes').value;
    llenarConfirmacion();
  }

  document.querySelectorAll('.paso').forEach(p => p.classList.add('oculto'));
  document.getElementById(`paso-${num}`).classList.remove('oculto');

  // Stepper
  document.querySelectorAll('.step').forEach((s, i) => {
    s.classList.remove('active', 'done');
    if (i + 1 < num) s.classList.add('done');
    if (i + 1 === num) s.classList.add('active');
  });

  state.pasoActual = num;
  window.scrollTo({ top: 300, behavior: 'smooth' });
}

// ── Llenar tabla de confirmación ──
function llenarConfirmacion() {
  document.getElementById('conf-paquete').textContent     = state.paquete.nombre;
  document.getElementById('conf-descripcion').textContent = state.paquete.descripcion.substring(0, 80) + '...';
  document.getElementById('conf-adultos').textContent     = state.adultos;
  document.getElementById('conf-ninos').textContent       = state.ninos;
  document.getElementById('conf-salida').textContent      = state.fechaSalida;
  document.getElementById('conf-regreso').textContent     = state.fechaRegreso || 'No especificada';
  document.getElementById('conf-nombre').textContent      = state.usuario.nombre;
  document.getElementById('conf-email').textContent       = state.usuario.email;
  document.getElementById('conf-telefono').textContent    = state.usuario.telefono || 'No registrado';
  document.getElementById('conf-contacto').textContent    = state.metodoContacto;
}

// ── Confirmar reserva (AJAX → PHP) ──
function confirmarReserva() {
  const btn = document.querySelector('.btn-confirmar');
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';

  const datos = {
    id_destino:         state.paquete.id,
    id_usuario:         state.usuario.id,
    adultos:            state.adultos,
    ninos:              state.ninos,
    fecha_salida:       state.fechaSalida,
    fecha_regreso:      state.fechaRegreso,
    solicitudes:        state.solicitudes,
    precio_por_persona: state.paquete.precio,
    descuento_ninos:    state.paquete.precio * 0.5,
    total:              state.total,
    metodo_contacto:    state.metodoContacto
  };

  fetch('/Agencia_Remolinos/componentes/Reserva/guardar_reserva.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(datos)
  })
  .then(r => r.json())
  .then(data => {
    if (data.ok) {
      state.folio = data.folio;
      mostrarExito();
    } else {
      alert('Error: ' + data.error);
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-check"></i> Confirmar Reserva';
    }
  })
  .catch(() => {
    alert('Error de conexión. Intenta de nuevo.');
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-check"></i> Confirmar Reserva';
  });
}

// ── Mostrar pantalla de éxito ──
function mostrarExito() {
  document.querySelectorAll('.paso').forEach(p => p.classList.add('oculto'));
  document.getElementById('paso-5').classList.remove('oculto');
  document.querySelectorAll('.step').forEach(s => s.classList.add('done'));

  document.getElementById('exito-paquete').textContent = state.paquete.nombre;
  document.getElementById('exito-email').textContent   = state.usuario.email;
  document.getElementById('exito-folio').textContent   = state.folio;
  document.getElementById('exito-total').textContent   = `$${state.total.toLocaleString()} MXN`;

  window.scrollTo({ top: 300, behavior: 'smooth' });
}

// ── Generar PDF ──
function generarPDF() {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF({ unit: 'mm', format: 'a4' });
  const azul = [26, 86, 219];
  const oscuro = [17, 24, 39];
  const gris = [107, 114, 128];
  const lineaY = { v: 20 };

  const addLine = (h = 6) => { lineaY.v += h; };

  // ── Header ──
  doc.setFillColor(...azul);
  doc.rect(0, 0, 210, 35, 'F');

  doc.setFont('helvetica', 'bold');
  doc.setFontSize(22);
  doc.setTextColor(255, 255, 255);
  doc.text("Remolino's Tours", 20, 18);

  doc.setFontSize(9);
  doc.setFont('helvetica', 'normal');
  doc.text('ROSA PORCELINA 106 EL ROSEDAL, Aguascalientes', 20, 25);
  doc.text('Tel: 449 598 9826  |  info@remolinostours.com', 20, 30);

  // Folio (esquina superior derecha)
  doc.setFont('helvetica', 'bold');
  doc.setFontSize(10);
  doc.text(`Folio: ${state.folio}`, 190, 15, { align: 'right' });
  doc.setFont('helvetica', 'normal');
  doc.setFontSize(8);
  const fecha = new Date().toLocaleDateString('es-MX', { year:'numeric', month:'long', day:'numeric' });
  doc.text(`Fecha: ${fecha}`, 190, 21, { align: 'right' });

  lineaY.v = 45;

  // ── Título ──
  doc.setFontSize(16);
  doc.setFont('helvetica', 'bold');
  doc.setTextColor(...oscuro);
  doc.text('SOLICITUD DE RESERVA', 105, lineaY.v, { align: 'center' });
  addLine(4);
  doc.setDrawColor(...azul);
  doc.setLineWidth(0.8);
  doc.line(20, lineaY.v, 190, lineaY.v);
  addLine(8);

  // ── Datos del paquete ──
  doc.setFillColor(232, 240, 254);
  doc.roundedRect(15, lineaY.v - 4, 180, 8, 2, 2, 'F');
  doc.setFontSize(11);
  doc.setFont('helvetica', 'bold');
  doc.setTextColor(...azul);
  doc.text('PAQUETE SELECCIONADO', 20, lineaY.v + 1);
  addLine(10);

  const filas = [
    ['Destino', state.paquete.nombre],
    ['Descripción', state.paquete.descripcion.substring(0, 80)],
  ];

  filas.forEach(([label, val]) => {
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(9);
    doc.setTextColor(...gris);
    doc.text(label, 20, lineaY.v);
    doc.setFont('helvetica', 'normal');
    doc.setTextColor(...oscuro);
    doc.text(String(val), 80, lineaY.v);
    doc.setDrawColor(229, 231, 235);
    doc.setLineWidth(0.3);
    doc.line(20, lineaY.v + 2, 190, lineaY.v + 2);
    addLine(8);
  });

  addLine(4);

  // ── Datos del viaje ──
  doc.setFillColor(232, 240, 254);
  doc.roundedRect(15, lineaY.v - 4, 180, 8, 2, 2, 'F');
  doc.setFontSize(11);
  doc.setFont('helvetica', 'bold');
  doc.setTextColor(...azul);
  doc.text('DETALLES DEL VIAJE', 20, lineaY.v + 1);
  addLine(10);

  const filasViaje = [
    ['Fecha de salida',  state.fechaSalida || '—'],
    ['Fecha de regreso', state.fechaRegreso || 'No especificada'],
    ['Adultos',          String(state.adultos)],
    ['Niños (50% dto)',  String(state.ninos)],
    ['Total viajeros',   String(state.adultos + state.ninos)],
    ['Solicitudes',      state.solicitudes || 'Ninguna'],
  ];

  filasViaje.forEach(([label, val]) => {
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(9);
    doc.setTextColor(...gris);
    doc.text(label, 20, lineaY.v);
    doc.setFont('helvetica', 'normal');
    doc.setTextColor(...oscuro);
    doc.text(String(val), 80, lineaY.v);
    doc.setDrawColor(229, 231, 235);
    doc.setLineWidth(0.3);
    doc.line(20, lineaY.v + 2, 190, lineaY.v + 2);
    addLine(8);
  });

  addLine(4);

  // ── Datos del viajero ──
  doc.setFillColor(232, 240, 254);
  doc.roundedRect(15, lineaY.v - 4, 180, 8, 2, 2, 'F');
  doc.setFontSize(11);
  doc.setFont('helvetica', 'bold');
  doc.setTextColor(...azul);
  doc.text('DATOS DEL VIAJERO', 20, lineaY.v + 1);
  addLine(10);

  const filasViajero = [
    ['Nombre completo', state.usuario.nombre],
    ['Email',           state.usuario.email],
    ['Teléfono',        state.usuario.telefono || '—'],
    ['Contacto pref.',  state.metodoContacto],
  ];

  filasViajero.forEach(([label, val]) => {
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(9);
    doc.setTextColor(...gris);
    doc.text(label, 20, lineaY.v);
    doc.setFont('helvetica', 'normal');
    doc.setTextColor(...oscuro);
    doc.text(String(val), 80, lineaY.v);
    doc.setDrawColor(229, 231, 235);
    doc.setLineWidth(0.3);
    doc.line(20, lineaY.v + 2, 190, lineaY.v + 2);
    addLine(8);
  });

  addLine(6);

  // ── Total ──
  doc.setFillColor(...azul);
  doc.roundedRect(120, lineaY.v - 4, 70, 18, 3, 3, 'F');
  doc.setFont('helvetica', 'bold');
  doc.setFontSize(10);
  doc.setTextColor(255,255,255);
  doc.text('TOTAL ESTIMADO', 155, lineaY.v + 2, { align: 'center' });
  doc.setFontSize(16);
  doc.text(`$${state.total.toLocaleString()} MXN`, 155, lineaY.v + 11, { align: 'center' });
  addLine(24);

  // ── Nota legal ──
  doc.setFontSize(7.5);
  doc.setFont('helvetica', 'italic');
  doc.setTextColor(...gris);
  doc.text('**Precio estimado sujeto a disponibilidad y cambios sin previo aviso.', 20, lineaY.v);
  doc.text('TARIFAS SUJETAS A DISPONIBILIDAD Y CAMBIO SIN PREVIO AVISO', 20, lineaY.v + 5);
  addLine(10);

  // ── Footer del PDF ──
  doc.setDrawColor(...azul);
  doc.setLineWidth(0.5);
  doc.line(20, lineaY.v, 190, lineaY.v);
  addLine(5);
  doc.setFont('helvetica', 'normal');
  doc.setFontSize(8);
  doc.setTextColor(...gris);
  doc.text(`Cotización generada el ${fecha}  |  Folio: ${state.folio}`, 105, lineaY.v, { align: 'center' });

  doc.save(`Reserva_${state.folio}.pdf`);
}

// ── Nueva reserva ──
function nuevaReserva() {
  state.paquete = null;
  state.adultos = 1;
  state.ninos   = 0;
  state.fechaSalida = '';
  state.fechaRegreso = '';
  state.solicitudes = '';
  state.folio = '';
  state.total = 0;
  document.querySelectorAll('.paquete-card').forEach(c => c.classList.remove('selected'));
  document.getElementById('val-adultos').textContent = '1';
  document.getElementById('val-ninos').textContent   = '0';
  document.getElementById('total-viajeros').textContent = '1';
  document.getElementById('resumen-vacio').classList.remove('oculto');
  document.getElementById('resumen-contenido').classList.add('oculto');
  irPaso(1);
}
</script>

</body>
</html>
