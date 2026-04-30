/* ════════════════════════════════════════════════════════
   MI VIAJE A LA MEDIDA — JS
   Estado global, pasos, resumen en tiempo real, PDF
════════════════════════════════════════════════════════ */

const MV = {
    pasoActual:  1,
    destino:     null,
    fechaSalida: '',
    fechaRegreso:'',
    flexFechas:  false,
    soloIda:     false,
    adultos:     1,
    ninos:       0,
    bebes:       0,
    habitacion:  'Doble',
    necesidades: [],
    extrasDestino:   [],   // actividades del destino seleccionado
    servicios:       [],   // servicios adicionales elegidos
    presupuesto:     '',
    metodoContacto:  'WhatsApp',
    horario:         'Mañana (8–12h)',
    notas:           '',
    folio:           '',
    totalBase:       0,
    totalExtras:     0,
    totalServicios:  0,
    get total() {
        return this.totalBase + this.totalExtras + this.totalServicios;
    }
};

/* ── INICIALIZACIÓN ─────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {

    // Fechas mínimas = hoy
    const hoy = new Date().toISOString().split('T')[0];
    document.getElementById('mv-fecha-salida').min  = hoy;
    document.getElementById('mv-fecha-regreso').min = hoy;

    // Calcular duración al cambiar fechas
    ['mv-fecha-salida','mv-fecha-regreso'].forEach(id => {
        document.getElementById(id).addEventListener('change', calcularDuracion);
    });

    // Fecha regreso mínima = fecha salida
    document.getElementById('mv-fecha-salida').addEventListener('change', function() {
        document.getElementById('mv-fecha-regreso').min = this.value;
    });

    // Buscador de destinos con debounce
    let timer;
    document.getElementById('buscadorDestino').addEventListener('input', function() {
        clearTimeout(timer);
        timer = setTimeout(() => filtrarDestinos(this.value), 300);
    });
});

/* ── NAVEGACIÓN ENTRE PASOS ─────────────────────────── */
function irPasoMV(num) {

    // Validaciones antes de avanzar
    if (num > MV.pasoActual) {
        if (!validarPasoMV(MV.pasoActual)) return;
    }

    // Recolectar datos del paso actual antes de salir
    recolectarDatos(MV.pasoActual);

    if (num === 5) llenarConfirmacionMV();

    // Mostrar paso
    document.querySelectorAll('.paso').forEach(p => p.classList.add('oculto'));
    document.getElementById(`paso-${num}`).classList.remove('oculto');

    // Stepper
    document.querySelectorAll('.step').forEach((s, i) => {
        s.classList.remove('active','done');
        if (i + 1 < num)  s.classList.add('done');
        if (i + 1 === num) s.classList.add('active');
    });

    MV.pasoActual = num;
    window.scrollTo({ top: 320, behavior: 'smooth' });
}

/* ── VALIDAR CADA PASO ──────────────────────────────── */
function validarPasoMV(paso) {
    if (paso === 1 && !MV.destino) {
        mvAlerta('Por favor selecciona un destino para continuar.');
        return false;
    }
    if (paso === 2) {
        const fs = document.getElementById('mv-fecha-salida').value;
        if (!fs) {
            mvAlerta('Por favor ingresa la fecha de salida.');
            return false;
        }
        if (new Date(fs) < new Date()) {
            mvAlerta('La fecha de salida no puede ser en el pasado.');
            return false;
        }
    }
    return true;
}

/* ── RECOLECTAR DATOS DEL PASO ──────────────────────── */
function recolectarDatos(paso) {
    if (paso === 2) {
        MV.fechaSalida  = document.getElementById('mv-fecha-salida').value;
        MV.fechaRegreso = document.getElementById('mv-fecha-regreso').value;
        MV.flexFechas   = document.getElementById('mv-flex-fechas').checked;
        MV.soloIda      = document.getElementById('mv-solo-ida').checked;
        calcularDuracion();
    }
    if (paso === 3) {
        MV.necesidades = [];
        document.querySelectorAll('.mv-check-item input:checked').forEach(chk => {
            MV.necesidades.push(chk.value);
        });
        actualizarResumenMV();
    }
    if (paso === 4) {
        MV.metodoContacto = document.getElementById('mv-metodo-contacto').value;
        MV.horario        = document.getElementById('mv-horario').value;
        MV.notas          = document.getElementById('mv-notas').value;
        MV.presupuesto    = document.querySelector('input[name="presupuesto"]:checked')?.value || '';

        // Servicios adicionales
        MV.servicios = [];
        MV.totalServicios = 0;
        document.querySelectorAll('.mv-servicio-card input:checked').forEach(chk => {
            const precio = parseInt(chk.value, 10);
            MV.servicios.push({ label: chk.dataset.label, precio });
            MV.totalServicios += precio * (MV.adultos + MV.ninos);
        });

        // Actividades extra del destino
        MV.extrasDestino = [];
        MV.totalExtras   = 0;
        document.querySelectorAll('.mv-extra-item input:checked').forEach(chk => {
            const precio = parseFloat(chk.dataset.precio);
            MV.extrasDestino.push({ label: chk.dataset.nombre, precio });
            MV.totalExtras += precio * (MV.adultos + MV.ninos);
        });

        actualizarResumenMV();
    }
}

/* ── SELECCIONAR DESTINO ────────────────────────────── */
function seleccionarDestino(el) {
    document.querySelectorAll('.mv-destino-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');

    MV.destino = {
        id:         el.dataset.id,
        nombre:     el.dataset.nombre,
        precio:     parseFloat(el.dataset.precio),
        precioNino: parseFloat(el.dataset.precioNino) || 0,
        imagen:     el.dataset.imagen,
        descripcion:el.dataset.descripcion,
        salida:     el.dataset.salida,
        trayecto:   el.dataset.trayecto,
        dias:       parseInt(el.dataset.dias, 10) || 1,
        noches:     parseInt(el.dataset.noches, 10) || 0,
        proveedor:  el.dataset.proveedor,
    };

    // Cargar actividades extra del destino seleccionado
    cargarExtrasDestino(MV.destino.id);
    actualizarResumenMV();
}

/* ── ACTIVIDADES EXTRA DEL DESTINO ─────────────────── */
function cargarExtrasDestino(idDestino) {
    const grid  = document.getElementById('gridExtrasDestino');
    const extras = (window.MiViajeConfig.extras[idDestino] || []);

    if (extras.length === 0) {
        grid.innerHTML = '<p class="mv-hint" style="grid-column:span 2;">Este destino no tiene actividades extra registradas.</p>';
        return;
    }

    grid.innerHTML = extras.map(e => `
        <label class="mv-extra-item">
            <input type="checkbox" data-nombre="${e.nombre_actividad}"
                   data-precio="${e.precio_extra}" onchange="recolectarDatos(4);actualizarResumenMV()">
            <i class="fas fa-star"></i>
            <div style="flex:1;">
                <strong style="display:block;font-size:1.3rem;">${e.nombre_actividad}</strong>
            </div>
            <span class="mv-extra-precio">+$${parseFloat(e.precio_extra).toLocaleString('es-MX')}</span>
        </label>
    `).join('');
}

/* ── CAMBIAR VIAJEROS ───────────────────────────────── */
function cambiarMV(tipo, delta) {
    if (tipo === 'adultos') MV.adultos = Math.max(1, MV.adultos + delta);
    if (tipo === 'ninos')   MV.ninos   = Math.max(0, MV.ninos   + delta);
    if (tipo === 'bebes')   MV.bebes   = Math.max(0, MV.bebes   + delta);

    document.getElementById('mv-val-adultos').textContent = MV.adultos;
    document.getElementById('mv-val-ninos').textContent   = MV.ninos;
    document.getElementById('mv-val-bebes').textContent   = MV.bebes;
    document.getElementById('mv-total-viajeros').textContent = MV.adultos + MV.ninos + MV.bebes;
    actualizarResumenMV();
}

/* ── HABITACIÓN ─────────────────────────────────────── */
function seleccionarHab(el, val) {
    document.querySelectorAll('.mv-hab-card').forEach(c => c.querySelector('.mv-hab-body').style.borderColor = '');
    MV.habitacion = val;
}

/* ── PRESUPUESTO ────────────────────────────────────── */
function seleccionarPresup(el, val) {
    document.querySelectorAll('.mv-presup-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    MV.presupuesto = val;
}

/* ── CALCULAR DURACIÓN ──────────────────────────────── */
function calcularDuracion() {
    const salida  = document.getElementById('mv-fecha-salida').value;
    const regreso = document.getElementById('mv-fecha-regreso').value;
    const box     = document.getElementById('mvDuracionBox');
    const txt     = document.getElementById('mvDuracionTexto');

    if (salida && regreso && regreso >= salida) {
        const dias = Math.round((new Date(regreso) - new Date(salida)) / 86400000);
        txt.textContent = `${dias} día${dias !== 1 ? 's' : ''} / ${dias - 1 > 0 ? dias - 1 : 0} noche${dias - 1 !== 1 ? 's' : ''}`;
        box.style.display = 'flex';
    } else if (salida) {
        box.style.display = 'none';
    }
}

/* ── FILTRAR DESTINOS ───────────────────────────────── */
function filtrarDestinos(q) {
    const termino = q.toLowerCase().trim();
    document.querySelectorAll('.mv-destino-card').forEach(card => {
        const texto = (card.dataset.nombre + ' ' + card.dataset.salida + ' ' + card.dataset.descripcion).toLowerCase();
        card.classList.toggle('hidden', termino !== '' && !texto.includes(termino));
    });
}

/* ── ACTUALIZAR RESUMEN LATERAL ─────────────────────── */
function actualizarResumenMV() {
    if (!MV.destino) return;

    const cfg = window.MiViajeConfig;

    document.getElementById('mv-resumen-vacio').classList.add('oculto');
    document.getElementById('mv-resumen-contenido').classList.remove('oculto');

    // Imagen y nombre
    document.getElementById('mv-res-img').src =
        `${cfg.rutas.imagenes}${MV.destino.imagen || 'default.png'}`;
    document.getElementById('mv-res-nombre').textContent = MV.destino.nombre;
    document.getElementById('mv-res-salida').textContent =
        MV.destino.salida ? `Sale desde: ${MV.destino.salida}` : '';

    // Precio base
    MV.totalBase = (MV.destino.precio * MV.adultos) +
                   ((MV.destino.precioNino || MV.destino.precio * 0.5) * MV.ninos);

    document.getElementById('mv-res-precio-adulto').textContent =
        `$${(MV.destino.precio * MV.adultos).toLocaleString('es-MX')} (×${MV.adultos})`;

    const rowNinos = document.getElementById('mv-row-ninos');
    if (MV.ninos > 0) {
        rowNinos.style.display = 'flex';
        document.getElementById('mv-lbl-ninos').textContent = `Niños ×${MV.ninos}`;
        document.getElementById('mv-res-precio-ninos').textContent =
            `$${((MV.destino.precioNino || MV.destino.precio * 0.5) * MV.ninos).toLocaleString('es-MX')}`;
    } else {
        rowNinos.style.display = 'none';
    }

    // Extras destino
    const rowExtras = document.getElementById('mv-row-extras');
    if (MV.totalExtras > 0) {
        rowExtras.style.display = 'flex';
        document.getElementById('mv-res-extras').textContent =
            `$${MV.totalExtras.toLocaleString('es-MX')}`;
    } else {
        rowExtras.style.display = 'none';
    }

    // Servicios
    const rowSvc = document.getElementById('mv-row-servicios');
    if (MV.totalServicios > 0) {
        rowSvc.style.display = 'flex';
        document.getElementById('mv-res-servicios').textContent =
            `$${MV.totalServicios.toLocaleString('es-MX')}`;
    } else {
        rowSvc.style.display = 'none';
    }

    // Tags de servicios activos
    const tagsWrap = document.getElementById('mv-res-tags');
    const allTags = [...MV.extrasDestino, ...MV.servicios];
    tagsWrap.innerHTML = allTags.map(t =>
        `<span class="mv-res-tag">${t.label}</span>`
    ).join('');

    // Total
    document.getElementById('mv-res-total').textContent =
        `$${MV.total.toLocaleString('es-MX')} MXN`;
}

/* ── LLENAR PANTALLA DE CONFIRMACIÓN ───────────────── */
function llenarConfirmacionMV() {
    const set = (id, val) => {
        const el = document.getElementById(id);
        if (el) el.textContent = val || '—';
    };

    set('conf-mv-destino',  MV.destino?.nombre);
    set('conf-mv-salida',   formatFecha(MV.fechaSalida));
    set('conf-mv-regreso',  MV.fechaRegreso ? formatFecha(MV.fechaRegreso) : 'No especificado');
    set('conf-mv-adultos',  MV.adultos);
    set('conf-mv-ninos',    MV.ninos);
    set('conf-mv-bebes',    MV.bebes);
    set('conf-mv-hab',      MV.habitacion);
    set('conf-mv-contacto', MV.metodoContacto);
    set('conf-mv-horario',  MV.horario);
    set('conf-mv-presup',   MV.presupuesto || 'No especificado');

    // Tags servicios
    const allTags = [...MV.extrasDestino.map(e => e.label), ...MV.servicios.map(s => s.label)];
    document.getElementById('conf-mv-servicios').innerHTML = allTags.length
        ? allTags.map(t => `<span class="mv-tag">${t}</span>`).join('')
        : '<span style="color:#9ca3af;font-size:1.3rem;">Ninguno seleccionado</span>';

    // Necesidades
    const secNec = document.getElementById('confNecesidadesSection');
    if (MV.necesidades.length > 0) {
        secNec.style.display = 'block';
        document.getElementById('conf-mv-necesidades').innerHTML =
            MV.necesidades.map(n => `<span class="mv-tag naranja">${n}</span>`).join('');
    } else {
        secNec.style.display = 'none';
    }

    // Notas
    const secNotas = document.getElementById('confNotasSection');
    if (MV.notas.trim()) {
        secNotas.style.display = 'block';
        document.getElementById('conf-mv-notas').textContent = MV.notas;
    } else {
        secNotas.style.display = 'none';
    }
}

/* ── CONFIRMAR Y ENVIAR ─────────────────────────────── */
async function confirmarMV() {
    if (!document.getElementById('mv-acepto-terminos').checked) {
        mvAlerta('Por favor acepta los términos para continuar.');
        return;
    }

    const btn = document.getElementById('btnConfirmarMV');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';

    const datos = {
        id_destino:         parseInt(MV.destino.id, 10),
        id_usuario:         window.MiViajeConfig.usuario.id,
        nombre_cliente:     window.MiViajeConfig.usuario.nombre,
        telefono_cliente:   window.MiViajeConfig.usuario.telefono || '',
        adultos:            MV.adultos,
        ninos:              MV.ninos,
        bebes:              MV.bebes,
        fecha_salida:       MV.fechaSalida,
        fecha_regreso:      MV.fechaRegreso || '',
        precio_por_persona: MV.destino.precio,
        descuento_ninos:    MV.destino.precioNino || (MV.destino.precio * 0.5),
        total:              MV.total,
        metodo_contacto:    MV.metodoContacto,
        solicitudes:        armarSolicitudes(),
        tipo_viaje:         'personalizado',
    };

    try {
        const r    = await fetch(window.MiViajeConfig.rutas.guardar, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datos)
        });
        const data = await r.json();

        if (data.ok) {
            MV.folio = data.folio;
            mostrarExitoMV();
        } else {
            mvAlerta('Error al guardar: ' + (data.error || 'Error desconocido'));
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane"></i> Enviar Solicitud';
        }
    } catch (err) {
        mvAlerta('Error de conexión. Verifica tu internet e intenta de nuevo.');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane"></i> Enviar Solicitud';
    }
}

/* Construye el texto de solicitudes combinando todos los extras */
function armarSolicitudes() {
    const partes = [];
    if (MV.habitacion)          partes.push(`Habitación: ${MV.habitacion}`);
    if (MV.extrasDestino.length) partes.push(`Actividades: ${MV.extrasDestino.map(e => e.label).join(', ')}`);
    if (MV.servicios.length)     partes.push(`Servicios: ${MV.servicios.map(s => s.label).join(', ')}`);
    if (MV.necesidades.length)   partes.push(`Necesidades: ${MV.necesidades.join(', ')}`);
    if (MV.presupuesto)          partes.push(`Presupuesto: ${MV.presupuesto}`);
    if (MV.horario)              partes.push(`Horario contacto: ${MV.horario}`);
    if (MV.flexFechas)           partes.push('Fechas flexibles: Sí');
    if (MV.soloIda)              partes.push('Solo ida: Sí');
    if (MV.notas)                partes.push(`Notas: ${MV.notas}`);
    return partes.join(' | ');
}

/* ── PANTALLA DE ÉXITO ──────────────────────────────── */
function mostrarExitoMV() {
    document.querySelectorAll('.paso').forEach(p => p.classList.add('oculto'));
    document.getElementById('paso-6').classList.remove('oculto');
    document.querySelectorAll('.step').forEach(s => s.classList.add('done'));

    document.getElementById('mv-exito-destino').textContent  = MV.destino.nombre;
    document.getElementById('mv-exito-contacto').textContent = MV.metodoContacto;
    document.getElementById('mv-exito-folio').textContent    = MV.folio;
    document.getElementById('mv-exito-total').textContent    = `$${MV.total.toLocaleString('es-MX')} MXN`;

    window.scrollTo({ top: 300, behavior: 'smooth' });
}

/* ── GENERAR PDF ────────────────────────────────────── */
function generarPDFMV() {
    if (!window.jspdf) { mvAlerta('Librería PDF no disponible.'); return; }

    const { jsPDF } = window.jspdf;
    const doc    = new jsPDF({ unit: 'mm', format: 'a4' });
    const azul   = [26, 86, 219];
    const naranja= [249, 115, 22];
    const oscuro = [17, 24, 39];
    const gris   = [107, 114, 128];
    let   y      = 20;

    const line = (h = 7) => { y += h; };
    const fila = (label, valor, color) => {
        doc.setFont('helvetica', 'bold'); doc.setFontSize(9); doc.setTextColor(...gris);
        doc.text(label, 22, y);
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(...(color || oscuro));
        const lines = doc.splitTextToSize(String(valor || '—'), 100);
        doc.text(lines, 85, y);
        doc.setDrawColor(229, 231, 235); doc.setLineWidth(0.3);
        doc.line(20, y + (lines.length * 5), 190, y + (lines.length * 5));
        line(lines.length > 1 ? lines.length * 6 : 9);
    };
    const seccion = (titulo, color) => {
        line(4);
        doc.setFillColor(...(color || [232, 240, 254]));
        doc.roundedRect(15, y - 4, 180, 9, 2, 2, 'F');
        doc.setFont('helvetica', 'bold'); doc.setFontSize(10);
        doc.setTextColor(...(color === naranja ? [255,255,255] : azul));
        doc.text(titulo, 20, y + 2);
        line(12);
    };

    // ── Header ──
    doc.setFillColor(...azul);
    doc.rect(0, 0, 210, 38, 'F');
    doc.setFillColor(...naranja);
    doc.rect(0, 33, 210, 5, 'F');
    doc.setFont('helvetica', 'bold'); doc.setFontSize(22); doc.setTextColor(255,255,255);
    doc.text("Remolino's Tours", 20, 18);
    doc.setFontSize(8.5); doc.setFont('helvetica', 'normal');
    doc.text('ROSA PORCELINA 106 EL ROSEDAL, Aguascalientes, Ags.', 20, 26);
    doc.text('Tel: 449 598 9826  |  info@remolinostours.com', 20, 32);
    doc.setFont('helvetica', 'bold'); doc.setFontSize(10);
    doc.text(`Folio: ${MV.folio}`, 190, 14, { align: 'right' });
    doc.setFontSize(8); doc.setFont('helvetica', 'normal');
    doc.text(`Fecha: ${formatFecha(new Date().toISOString().split('T')[0])}`, 190, 21, { align: 'right' });

    // ── Título ──
    y = 52;
    doc.setFontSize(16); doc.setFont('helvetica', 'bold'); doc.setTextColor(...oscuro);
    doc.text('SOLICITUD DE VIAJE A LA MEDIDA', 105, y, { align: 'center' });
    line(5);
    doc.setDrawColor(...naranja); doc.setLineWidth(0.8);
    doc.line(20, y, 190, y);
    line(8);

    // ── Viajero ──
    seccion('DATOS DEL VIAJERO');
    fila('Nombre completo',  window.MiViajeConfig.usuario.nombre);
    fila('Email',            window.MiViajeConfig.usuario.email);
    fila('Teléfono',         window.MiViajeConfig.usuario.telefono || '—');
    fila('Contacto pref.',   MV.metodoContacto);
    fila('Horario contacto', MV.horario);

    // ── Viaje ──
    seccion('DETALLES DEL VIAJE');
    fila('Destino',          MV.destino.nombre, azul);
    fila('Punto de salida',  MV.destino.salida || '—');
    fila('Tipo trayecto',    MV.destino.trayecto);
    fila('Fecha de salida',  formatFecha(MV.fechaSalida));
    fila('Fecha de regreso', MV.fechaRegreso ? formatFecha(MV.fechaRegreso) : 'No especificado');
    fila('Fechas flexibles', MV.flexFechas ? 'Sí, ±2 días' : 'No');
    fila('Adultos',          MV.adultos);
    fila('Niños',            MV.ninos);
    fila('Bebés',            MV.bebes);
    fila('Habitación',       MV.habitacion);
    if (MV.presupuesto) fila('Presupuesto aprox.', MV.presupuesto);

    // ── Servicios ──
    const todos = [...MV.extrasDestino, ...MV.servicios];
    if (todos.length > 0) {
        seccion('SERVICIOS Y ACTIVIDADES SELECCIONADOS');
        todos.forEach(sv => {
            fila(sv.label, `$${sv.precio.toLocaleString('es-MX')} MXN por persona`);
        });
    }

    // ── Necesidades ──
    if (MV.necesidades.length > 0) {
        seccion('NECESIDADES ESPECIALES');
        fila('Requerimientos', MV.necesidades.join(', '));
    }

    // ── Notas ──
    if (MV.notas.trim()) {
        seccion('NOTAS ADICIONALES');
        fila('Solicitudes', MV.notas);
    }

    // ── Total ──
    line(4);
    doc.setFillColor(...naranja);
    doc.roundedRect(115, y - 4, 75, 22, 3, 3, 'F');
    doc.setFont('helvetica', 'bold'); doc.setFontSize(10); doc.setTextColor(255,255,255);
    doc.text('TOTAL ESTIMADO BASE', 152, y + 3, { align: 'center' });
    doc.setFontSize(18);
    doc.text(`$${MV.total.toLocaleString('es-MX')} MXN`, 152, y + 14, { align: 'center' });
    line(30);

    // ── Pie ──
    doc.setFontSize(7); doc.setFont('helvetica', 'italic'); doc.setTextColor(...gris);
    doc.text('*Precio estimado sujeto a disponibilidad. Un asesor confirmará el precio final en menos de 24 horas.', 20, y);
    doc.line(20, y + 5, 190, y + 5);
    doc.text(`Folio: ${MV.folio}  |  ${formatFecha(new Date().toISOString().split('T')[0])}  |  remolinostours.com`, 105, y + 10, { align: 'center' });

    doc.save(`MiViaje_${MV.folio}.pdf`);
}

/* ── UTILIDADES ─────────────────────────────────────── */
function formatFecha(str) {
    if (!str) return '—';
    const [y, m, d] = str.split('-');
    const meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
    return `${d} ${meses[parseInt(m,10)-1]}, ${y}`;
}

function mvAlerta(msg) {
    const el = document.createElement('div');
    el.style.cssText = `
        position:fixed; top:90px; left:50%; transform:translateX(-50%);
        background:#ef4444; color:#fff; padding:14px 28px; border-radius:12px;
        font-size:1.4rem; font-weight:600; z-index:9999;
        box-shadow:0 8px 24px rgba(239,68,68,.4); max-width:90%;
    `;
    el.textContent = msg;
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 4000);
}