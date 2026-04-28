// ── Estado global (Ahora dinámico) ──
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
  // Los datos vienen del objeto global AppConfig definido en el PHP
  usuario: {
    nombre: window.AppConfig.usuario.nombre,
    email: window.AppConfig.usuario.email,
    telefono: window.AppConfig.usuario.telefono,
    id: window.AppConfig.usuario.id
  }
};

// ── Seleccionar paquete ──
function seleccionarPaquete(el) {
  document.querySelectorAll('.paquete-card').forEach(c => c.classList.remove('selected'));
  el.classList.add('selected');
  state.paquete = {
    id: el.dataset.id,
    nombre: el.dataset.nombre,
    precio: parseFloat(el.dataset.precio),
    imagen: el.dataset.imagen,
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
  const totalNinos = precioNino * state.ninos;
  state.total = totalAdultos + totalNinos;

  document.getElementById('res-img').src = `${window.AppConfig.rutas.imagenes}${p.imagen}`;
  document.getElementById('res-nombre').textContent = p.nombre;
  document.getElementById('res-desc').textContent = p.descripcion.substring(0, 60) + '...';
  document.getElementById('res-precio-pp').textContent = `$${p.precio.toLocaleString()}`;

  const rowAdultos = document.getElementById('res-row-adultos');
  rowAdultos.style.display = 'flex';
  document.getElementById('res-label-adultos').textContent = `Adultos × ${state.adultos}`;
  document.getElementById('res-precio-adultos').textContent = `$${totalAdultos.toLocaleString()}`;

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
    state.fechaSalida = fechaSalida;
    state.fechaRegreso = document.getElementById('fecha-regreso').value;
    state.metodoContacto = document.getElementById('metodo-contacto').value;
    state.solicitudes = document.getElementById('solicitudes').value;
    llenarConfirmacion();
  }

  document.querySelectorAll('.paso').forEach(p => p.classList.add('oculto'));
  document.getElementById(`paso-${num}`).classList.remove('oculto');

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
  document.getElementById('conf-paquete').textContent = state.paquete.nombre;
  document.getElementById('conf-descripcion').textContent = state.paquete.descripcion.substring(0, 80) + '...';
  document.getElementById('conf-adultos').textContent = state.adultos;
  document.getElementById('conf-ninos').textContent = state.ninos;
  document.getElementById('conf-salida').textContent = state.fechaSalida;
  document.getElementById('conf-regreso').textContent = state.fechaRegreso || 'No especificada';
  document.getElementById('conf-nombre').textContent = state.usuario.nombre;
  document.getElementById('conf-email').textContent = state.usuario.email;
  document.getElementById('conf-telefono').textContent = state.usuario.telefono || 'No registrado';
  document.getElementById('conf-contacto').textContent = state.metodoContacto;
}

// ── Confirmar reserva (AJAX → PHP) ──
function confirmarReserva() {
  const btn = document.querySelector('.btn-confirmar');
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';

  const datos = {
    id_destino: state.paquete.id,
    id_usuario: state.usuario.id,
    adultos: state.adultos,
    ninos: state.ninos,
    fecha_salida: state.fechaSalida,
    fecha_regreso: state.fechaRegreso,
    solicitudes: state.solicitudes,
    precio_por_persona: state.paquete.precio,
    descuento_ninos: state.paquete.precio * 0.5,
    total: state.total,
    metodo_contacto: state.metodoContacto
  };

  fetch(window.AppConfig.rutas.guardar, {
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
  document.getElementById('exito-email').textContent = state.usuario.email;
  document.getElementById('exito-folio').textContent = state.folio;
  document.getElementById('exito-total').textContent = `$${state.total.toLocaleString()} MXN`;

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

  doc.setFont('helvetica', 'bold');
  doc.setFontSize(10);
  doc.text(`Folio: ${state.folio}`, 190, 15, { align: 'right' });
  
  const fecha = new Date().toLocaleDateString('es-MX', { year:'numeric', month:'long', day:'numeric' });
  doc.setFontSize(8);
  doc.text(`Fecha: ${fecha}`, 190, 21, { align: 'right' });

  lineaY.v = 45;
  doc.setFontSize(16);
  doc.setFont('helvetica', 'bold');
  doc.setTextColor(...oscuro);
  doc.text('SOLICITUD DE RESERVA', 105, lineaY.v, { align: 'center' });
  addLine(4);
  doc.setDrawColor(...azul);
  doc.setLineWidth(0.8);
  doc.line(20, lineaY.v, 190, lineaY.v);
  addLine(8);

  // Detalle de Paquete
  doc.setFillColor(232, 240, 254);
  doc.roundedRect(15, lineaY.v - 4, 180, 8, 2, 2, 'F');
  doc.setFontSize(11);
  doc.setTextColor(...azul);
  doc.text('PAQUETE SELECCIONADO', 20, lineaY.v + 1);
  addLine(10);

  const filas = [
    ['Destino', state.paquete.nombre],
    ['Descripción', state.paquete.descripcion.substring(0, 80)],
  ];

  filas.forEach(([label, val]) => {
    doc.setFont('helvetica', 'bold'); doc.setFontSize(9); doc.setTextColor(...gris);
    doc.text(label, 20, lineaY.v);
    doc.setFont('helvetica', 'normal'); doc.setTextColor(...oscuro);
    doc.text(String(val), 80, lineaY.v);
    doc.setDrawColor(229, 231, 235); doc.line(20, lineaY.v + 2, 190, lineaY.v + 2);
    addLine(8);
  });

  addLine(4);

  // Detalle de Viaje
  doc.setFillColor(232, 240, 254);
  doc.roundedRect(15, lineaY.v - 4, 180, 8, 2, 2, 'F');
  doc.text('DETALLES DEL VIAJE', 20, lineaY.v + 1);
  addLine(10);

  const filasViaje = [
    ['Fecha de salida', state.fechaSalida || '—'],
    ['Fecha de regreso', state.fechaRegreso || 'No especificada'],
    ['Adultos', String(state.adultos)],
    ['Niños (50% dto)', String(state.ninos)],
    ['Total viajeros', String(state.adultos + state.ninos)],
    ['Solicitudes', state.solicitudes || 'Ninguna'],
  ];

  filasViaje.forEach(([label, val]) => {
    doc.setFont('helvetica', 'bold'); doc.text(label, 20, lineaY.v);
    doc.setFont('helvetica', 'normal'); doc.text(String(val), 80, lineaY.v);
    doc.line(20, lineaY.v + 2, 190, lineaY.v + 2);
    addLine(8);
  });

  addLine(4);

  // Datos del Viajero
  doc.setFillColor(232, 240, 254);
  doc.roundedRect(15, lineaY.v - 4, 180, 8, 2, 2, 'F');
  doc.text('DATOS DEL VIAJERO', 20, lineaY.v + 1);
  addLine(10);

  const filasViajero = [
    ['Nombre completo', state.usuario.nombre],
    ['Email', state.usuario.email],
    ['Teléfono', state.usuario.telefono || '—'],
    ['Contacto pref.', state.metodoContacto],
  ];

  filasViajero.forEach(([label, val]) => {
    doc.setFont('helvetica', 'bold'); doc.text(label, 20, lineaY.v);
    doc.setFont('helvetica', 'normal'); doc.text(String(val), 80, lineaY.v);
    doc.line(20, lineaY.v + 2, 190, lineaY.v + 2);
    addLine(8);
  });

  addLine(6);

  doc.setFillColor(...azul);
  doc.roundedRect(120, lineaY.v - 4, 70, 18, 3, 3, 'F');
  doc.setFont('helvetica', 'bold'); doc.setFontSize(10); doc.setTextColor(255,255,255);
  doc.text('TOTAL ESTIMADO', 155, lineaY.v + 2, { align: 'center' });
  doc.setFontSize(16);
  doc.text(`$${state.total.toLocaleString()} MXN`, 155, lineaY.v + 11, { align: 'center' });
  
  addLine(24);
  doc.setFontSize(7.5); doc.setFont('helvetica', 'italic'); doc.setTextColor(...gris);
  doc.text('**Precio estimado sujeto a disponibilidad y cambios sin previo aviso.', 20, lineaY.v);
  doc.line(20, lineaY.v + 5, 190, lineaY.v + 5);
  doc.text(`Cotización generada el ${fecha}  |  Folio: ${state.folio}`, 105, lineaY.v + 10, { align: 'center' });

  doc.save(`Reserva_${state.folio}.pdf`);
}

// ── Nueva reserva ──
function nuevaReserva() {
  state.paquete = null;
  state.adultos = 1;
  state.ninos = 0;
  state.fechaSalida = '';
  state.fechaRegreso = '';
  state.solicitudes = '';
  state.folio = '';
  state.total = 0;
  document.querySelectorAll('.paquete-card').forEach(c => c.classList.remove('selected'));
  document.getElementById('val-adultos').textContent = '1';
  document.getElementById('val-ninos').textContent = '0';
  document.getElementById('total-viajeros').textContent = '1';
  document.getElementById('resumen-vacio').classList.remove('oculto');
  document.getElementById('resumen-contenido').classList.add('oculto');
  irPaso(1);
}