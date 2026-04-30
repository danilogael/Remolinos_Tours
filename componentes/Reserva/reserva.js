/* ── ESTADO GLOBAL ────────────────────────────────────────── */
const state = {
  pasoActual: 1,
  paquete: null,
  adultos: 1,
  ninos: 0,
  fechaSalida: '',
  fechaRegreso: '',
  metodoContacto: 'WhatsApp',
  solicitudes: '',
  folio: '',
  total: 0,
  usuario: {
    id:       window.AppConfig.usuario.id,
    nombre:   window.AppConfig.usuario.nombre,
    email:    window.AppConfig.usuario.email,
    telefono: window.AppConfig.usuario.telefono
  }
};

/* ── SELECCIONAR PAQUETE ──────────────────────────────────── */
function seleccionarPaquete(el) {
  document.querySelectorAll('.paquete-card').forEach(c => c.classList.remove('selected'));
  el.classList.add('selected');
  state.paquete = {
    id:          el.dataset.id,
    nombre:      el.dataset.nombre,
    precio:      parseFloat(el.dataset.precio),
    // ← ruta correcta: assets/imagenes/ (sin subcarpeta destinos/)
    imagen:      el.dataset.imagen,
    descripcion: el.dataset.descripcion,
    permiteNinos: el.dataset.permiteNinos !== '0',
    minAdultos: parseInt(el.dataset.minAdultos || '1', 10),
    maxAdultos: parseInt(el.dataset.maxAdultos || '10', 10),
    maxNinos: parseInt(el.dataset.maxNinos || '6', 10),
    cupoTotal: parseInt(el.dataset.cupoTotal || '20', 10),
    tipoCupo: el.dataset.tipoCupo || 'flexible'
  };
  state.adultos = Math.max(state.paquete.minAdultos, Math.min(state.adultos, state.paquete.maxAdultos));
  state.ninos = state.paquete.permiteNinos ? Math.min(state.ninos, state.paquete.maxNinos) : 0;
  ajustarCupoTotal();
  document.getElementById('val-adultos').textContent = state.adultos;
  document.getElementById('val-ninos').textContent = state.ninos;
  document.getElementById('total-viajeros').textContent = state.adultos + state.ninos;
  actualizarResumen();
}

/* ── CAMBIAR VIAJEROS ─────────────────────────────────────── */
function cambiarCantidad(tipo, delta) {
  if (!state.paquete) {
    mostrarAlerta('Primero selecciona un paquete.');
    return;
  }

  if (tipo === 'adultos') {
    state.adultos = Math.max(state.paquete.minAdultos, Math.min(state.paquete.maxAdultos, state.adultos + delta));
    document.getElementById('val-adultos').textContent = state.adultos;
  } else {
    if (!state.paquete.permiteNinos) {
      mostrarAlerta('Este paquete no permite ninos.');
      return;
    }
    state.ninos = Math.max(0, Math.min(state.paquete.maxNinos, state.ninos + delta));
    document.getElementById('val-ninos').textContent = state.ninos;
  }
  ajustarCupoTotal();
  document.getElementById('val-adultos').textContent = state.adultos;
  document.getElementById('val-ninos').textContent = state.ninos;
  document.getElementById('total-viajeros').textContent = state.adultos + state.ninos;
  actualizarResumen();
}

function ajustarCupoTotal() {
  if (!state.paquete) return;
  const total = state.adultos + state.ninos;
  if (total <= state.paquete.cupoTotal) return;

  const exceso = total - state.paquete.cupoTotal;
  if (state.ninos >= exceso) {
    state.ninos -= exceso;
  } else {
    state.adultos = Math.max(state.paquete.minAdultos, state.adultos - (exceso - state.ninos));
    state.ninos = 0;
  }
}

/* ── ACTUALIZAR RESUMEN LATERAL ───────────────────────────── */
function actualizarResumen() {
  if (!state.paquete) return;

  document.getElementById('resumen-vacio').classList.add('oculto');
  document.getElementById('resumen-contenido').classList.remove('oculto');

  const p            = state.paquete;
  const precioNino   = p.precio * 0.5;
  const totalAdultos = p.precio * state.adultos;
  const totalNinos   = precioNino * state.ninos;
  state.total        = totalAdultos + totalNinos;

  // ← ruta correcta igual a la que usa destinos.php
  document.getElementById('res-img').src =
    `/Agencia_Remolinos/assets/imagenes/${p.imagen || 'default.png'}`;

  document.getElementById('res-nombre').textContent    = p.nombre;
  document.getElementById('res-desc').textContent      = (p.descripcion || '').substring(0, 60) + '...';
  document.getElementById('res-precio-pp').textContent = `$${p.precio.toLocaleString('es-MX')}`;

  // Fila adultos
  const rowAdultos = document.getElementById('res-row-adultos');
  rowAdultos.style.display = 'flex';
  document.getElementById('res-label-adultos').textContent   = `Adultos × ${state.adultos}`;
  document.getElementById('res-precio-adultos').textContent  = `$${totalAdultos.toLocaleString('es-MX')}`;

  // Fila niños
  const rowNinos = document.getElementById('res-row-ninos');
  if (state.ninos > 0) {
    rowNinos.style.display = 'flex';
    document.getElementById('res-label-ninos').textContent  = `Niños × ${state.ninos} (50% dto)`;
    document.getElementById('res-precio-ninos').textContent = `$${totalNinos.toLocaleString('es-MX')}`;
  } else {
    rowNinos.style.display = 'none';
  }

  document.getElementById('res-total').textContent = `$${state.total.toLocaleString('es-MX')}`;
}

/* ── NAVEGAR ENTRE PASOS ──────────────────────────────────── */
function irPaso(num) {
  // Validaciones antes de avanzar
  if (num === 2 && !state.paquete) {
    mostrarAlerta('Por favor selecciona un paquete para continuar.');
    return;
  }

  if (num === 4) {
    if (state.paquete.tipoCupo === 'fijo' && (state.adultos + state.ninos) !== state.paquete.cupoTotal) {
      mostrarAlerta(`Este paquete requiere exactamente ${state.paquete.cupoTotal} viajeros.`);
      return;
    }
    const fechaSalida = document.getElementById('fecha-salida').value;
    if (!fechaSalida) {
      mostrarAlerta('Por favor ingresa la fecha de salida.');
      return;
    }
    // Validar que la fecha no sea pasada
    if (new Date(fechaSalida) < new Date()) {
      mostrarAlerta('La fecha de salida no puede ser en el pasado.');
      return;
    }
    state.fechaSalida    = fechaSalida;
    state.fechaRegreso   = document.getElementById('fecha-regreso').value;
    state.metodoContacto = document.getElementById('metodo-contacto').value;
    state.solicitudes    = document.getElementById('solicitudes').value;
    llenarConfirmacion();
  }

  // Mostrar paso correcto
  document.querySelectorAll('.paso').forEach(p => p.classList.add('oculto'));
  document.getElementById(`paso-${num}`).classList.remove('oculto');

  // Actualizar stepper
  document.querySelectorAll('.step').forEach((s, i) => {
    s.classList.remove('active', 'done');
    if (i + 1 < num)  s.classList.add('done');
    if (i + 1 === num) s.classList.add('active');
  });

  state.pasoActual = num;
  window.scrollTo({ top: 300, behavior: 'smooth' });
}

/* ── LLENAR TABLA DE CONFIRMACIÓN ────────────────────────── */
function llenarConfirmacion() {
  document.getElementById('conf-paquete').textContent     = state.paquete.nombre;
  document.getElementById('conf-descripcion').textContent = (state.paquete.descripcion || '').substring(0, 80) + '...';
  document.getElementById('conf-adultos').textContent     = state.adultos;
  document.getElementById('conf-ninos').textContent       = state.ninos;
  document.getElementById('conf-salida').textContent      = formatearFecha(state.fechaSalida);
  document.getElementById('conf-regreso').textContent     = state.fechaRegreso ? formatearFecha(state.fechaRegreso) : 'No especificada';
  document.getElementById('conf-nombre').textContent      = state.usuario.nombre;
  document.getElementById('conf-email').textContent       = state.usuario.email;
  document.getElementById('conf-telefono').textContent    = state.usuario.telefono || 'No registrado';
  document.getElementById('conf-contacto').textContent    = state.metodoContacto;
}

/* ── CONFIRMAR RESERVA (AJAX) ────────────────────────────── */
function confirmarReserva() {
  const btn = document.querySelector('.btn-confirmar');
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';

  const datos = {
    id_destino:        parseInt(state.paquete.id),
    id_usuario:        parseInt(state.usuario.id),
    nombre_cliente:    state.usuario.nombre,      // ← campo requerido por tu tabla
    telefono_cliente:  state.usuario.telefono || '',
    adultos:           state.adultos,
    ninos:             state.ninos,
    fecha_salida:      state.fechaSalida,
    fecha_regreso:     state.fechaRegreso || '',
    solicitudes:       state.solicitudes,
    precio_por_persona: state.paquete.precio,
    descuento_ninos:   state.paquete.precio * 0.5,
    total:             state.total,               // ← guardar_reserva.php lo mapea a total_pago
    metodo_contacto:   state.metodoContacto
  };

  fetch(window.AppConfig.rutas.guardar, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(datos)
  })
  .then(r => {
    if (!r.ok) throw new Error(`HTTP ${r.status}`);
    return r.json();
  })
  .then(data => {
    if (data.ok) {
      state.folio = data.folio;
      mostrarExito();
    } else {
      mostrarAlerta('Error al guardar: ' + (data.error || 'Error desconocido'));
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-check"></i> Confirmar Reserva';
    }
  })
  .catch(err => {
    mostrarAlerta('Error de conexión. Intenta de nuevo.\n' + err.message);
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-check"></i> Confirmar Reserva';
  });
}

/* ── PANTALLA DE ÉXITO ───────────────────────────────────── */
function mostrarExito() {
  document.querySelectorAll('.paso').forEach(p => p.classList.add('oculto'));
  document.getElementById('paso-5').classList.remove('oculto');
  document.querySelectorAll('.step').forEach(s => s.classList.add('done'));

  document.getElementById('exito-paquete').textContent = state.paquete.nombre;
  document.getElementById('exito-email').textContent   = state.usuario.email;
  document.getElementById('exito-folio').textContent   = state.folio;
  document.getElementById('exito-total').textContent   = `$${state.total.toLocaleString('es-MX')} MXN`;

  window.scrollTo({ top: 300, behavior: 'smooth' });
}

/* ── GENERAR PDF ─────────────────────────────────────────── */
function generarPDF() {
  if (!window.jspdf) {
    mostrarAlerta('La librería de PDF no está disponible. Intenta recargar la página.');
    return;
  }

  const { jsPDF } = window.jspdf;
  const doc    = new jsPDF({ unit: 'mm', format: 'a4' });
  const azul   = [26, 86, 219];
  const oscuro = [17, 24, 39];
  const gris   = [107, 114, 128];
  let   y      = 20;

  const line = (h = 7) => { y += h; };
  const fila = (label, valor) => {
    doc.setFont('helvetica', 'bold');   doc.setFontSize(9);  doc.setTextColor(...gris);
    doc.text(label, 22, y);
    doc.setFont('helvetica', 'normal'); doc.setTextColor(...oscuro);
    doc.text(String(valor || '—'), 85, y);
    doc.setDrawColor(229, 231, 235); doc.setLineWidth(0.3);
    doc.line(20, y + 3, 190, y + 3);
    line(9);
  };
  const seccion = (titulo) => {
    line(4);
    doc.setFillColor(232, 240, 254);
    doc.roundedRect(15, y - 4, 180, 9, 2, 2, 'F');
    doc.setFont('helvetica', 'bold'); doc.setFontSize(10); doc.setTextColor(...azul);
    doc.text(titulo, 20, y + 2);
    line(12);
  };

  // ── Encabezado ──
  doc.setFillColor(...azul);
  doc.rect(0, 0, 210, 38, 'F');
  doc.setFont('helvetica', 'bold'); doc.setFontSize(22); doc.setTextColor(255,255,255);
  doc.text("Remolino's Tours", 20, 18);
  doc.setFontSize(8.5); doc.setFont('helvetica', 'normal');
  doc.text('ROSA PORCELINA 106 EL ROSEDAL, Aguascalientes, Ags.', 20, 26);
  doc.text('Tel: 449 598 9826  |  info@remolinostours.com', 20, 32);
  doc.setFont('helvetica', 'bold'); doc.setFontSize(10);
  doc.text(`Folio: ${state.folio}`, 190, 14, { align: 'right' });
  doc.setFontSize(8); doc.setFont('helvetica', 'normal');
  doc.text(`Fecha: ${formatearFecha(new Date().toISOString().split('T')[0])}`, 190, 21, { align: 'right' });

  // ── Título ──
  y = 48;
  doc.setFontSize(16); doc.setFont('helvetica', 'bold'); doc.setTextColor(...oscuro);
  doc.text('SOLICITUD DE RESERVA', 105, y, { align: 'center' });
  line(5);
  doc.setDrawColor(...azul); doc.setLineWidth(0.8);
  doc.line(20, y, 190, y);
  line(8);

  // ── Paquete ──
  seccion('PAQUETE SELECCIONADO');
  fila('Destino',     state.paquete.nombre);
  fila('Descripción', (state.paquete.descripcion || '').substring(0, 90));

  // ── Viaje ──
  seccion('DETALLES DEL VIAJE');
  fila('Fecha de salida',   formatearFecha(state.fechaSalida));
  fila('Fecha de regreso',  state.fechaRegreso ? formatearFecha(state.fechaRegreso) : 'No especificada');
  fila('Adultos',           state.adultos);
  fila('Niños (50% dto)',   state.ninos);
  fila('Total viajeros',    state.adultos + state.ninos);
  fila('Solicitudes esp.',  state.solicitudes || 'Ninguna');

  // ── Viajero ──
  seccion('DATOS DEL VIAJERO');
  fila('Nombre completo',   state.usuario.nombre);
  fila('Email',             state.usuario.email);
  fila('Teléfono',          state.usuario.telefono || '—');
  fila('Contacto pref.',    state.metodoContacto);

  // ── Total ──
  line(6);
  doc.setFillColor(...azul);
  doc.roundedRect(120, y - 4, 70, 20, 3, 3, 'F');
  doc.setFont('helvetica', 'bold'); doc.setFontSize(10); doc.setTextColor(255,255,255);
  doc.text('TOTAL ESTIMADO', 155, y + 3, { align: 'center' });
  doc.setFontSize(17);
  doc.text(`$${state.total.toLocaleString('es-MX')} MXN`, 155, y + 13, { align: 'center' });
  line(28);

  // ── Pie ──
  doc.setFontSize(7.5); doc.setFont('helvetica', 'italic'); doc.setTextColor(...gris);
  doc.text('*Precio estimado sujeto a disponibilidad. Uno de nuestros asesores confirmará en 24hrs.', 20, y);
  doc.line(20, y + 5, 190, y + 5);
  doc.text(`Folio: ${state.folio}  |  Generado el ${formatearFecha(new Date().toISOString().split('T')[0])}`, 105, y + 10, { align: 'center' });

  doc.save(`Reserva_${state.folio}.pdf`);
}

/* ── NUEVA RESERVA ───────────────────────────────────────── */
function nuevaReserva() {
  state.paquete      = null;
  state.adultos      = 1;
  state.ninos        = 0;
  state.fechaSalida  = '';
  state.fechaRegreso = '';
  state.solicitudes  = '';
  state.folio        = '';
  state.total        = 0;
  document.querySelectorAll('.paquete-card').forEach(c => c.classList.remove('selected'));
  document.getElementById('val-adultos').textContent  = '1';
  document.getElementById('val-ninos').textContent    = '0';
  document.getElementById('total-viajeros').textContent = '1';
  document.getElementById('resumen-vacio').classList.remove('oculto');
  document.getElementById('resumen-contenido').classList.add('oculto');
  irPaso(1);
}

/* ── UTILIDADES ──────────────────────────────────────────── */
function formatearFecha(fechaStr) {
  if (!fechaStr) return '—';
  const [y, m, d] = fechaStr.split('-');
  const meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
  return `${d} ${meses[parseInt(m,10)-1]}, ${y}`;
}

function mostrarAlerta(msg) {
  // Alerta visual en lugar de alert() nativo
  const el = document.createElement('div');
  el.style.cssText = `
    position:fixed; top:20px; left:50%; transform:translateX(-50%);
    background:#ef4444; color:#fff; padding:14px 24px; border-radius:12px;
    font-size:1.4rem; font-weight:600; z-index:9999;
    box-shadow:0 8px 24px rgba(239,68,68,.4); max-width:90%;
    animation: slideDown .3s ease;
  `;
  el.textContent = msg;
  document.body.appendChild(el);
  setTimeout(() => el.remove(), 4000);
}

document.addEventListener('DOMContentLoaded', () => {
  const idPreseleccionado = String(window.AppConfig?.preseleccionado || '');
  if (!idPreseleccionado || idPreseleccionado === '0') return;

  const card = Array.from(document.querySelectorAll('.paquete-card'))
    .find(el => el.dataset.id === idPreseleccionado);
  if (!card) return;

  seleccionarPaquete(card);
  irPaso(2);
});
