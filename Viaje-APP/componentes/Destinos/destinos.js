// ============================================
// CONFIG - Cambia esta URL a donde tengas tu api.php
// ============================================
const API_URL = 'api.php';

// Estado global
let currentRegion = '';
let currentPaqueteId = null;
let sliderIndex = 0;
let sliderImages = [];
let adminToken = localStorage.getItem('admin_token') || null;
let itinerarioDiaCount = 0;
let editingPaqueteId = null;

// ============================================
// NAVEGACIÓN
// ============================================
function showPage(page) {
  document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
  document.getElementById('page-' + page).classList.add('active');
  const navEl = document.getElementById('nav-' + page);
  if (navEl) navEl.classList.add('active');
  window.scrollTo(0,0);

  if (page === 'destinos') loadPaquetes();
  if (page === 'home') loadHomePreview();
  if (page === 'admin') initAdmin();
  if (page === 'reservas') loadReservasList();
}

// Scroll navbar
window.addEventListener('scroll', () => {
  document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 10);
});

// ============================================
// API CALLS
// ============================================
async function apiGet(action, params = {}) {
  const query = new URLSearchParams({ action, ...params }).toString();
  const res = await fetch(`${API_URL}?${query}`);
  return res.json();
}

async function apiPost(action, body) {
  const res = await fetch(`${API_URL}?action=${action}`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(body)
  });
  return res.json();
}

async function apiPut(action, body) {
  const res = await fetch(`${API_URL}?action=${action}`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(body)
  });
  return res.json();
}

async function apiDelete(action, params = {}) {
  const query = new URLSearchParams({ action, ...params }).toString();
  const res = await fetch(`${API_URL}?${query}`, { method: 'DELETE' });
  return res.json();
}

// ============================================
// HOME
// ============================================
async function loadHomePreview() {
  try {
    const data = await apiGet('get_paquetes', { region: '', popular: 1 });
    document.getElementById('stat-paquetes').textContent = data.total + '+';
    const populares = (data.data || []).filter(p => p.popular == 1).slice(0, 3);
    const grid = document.getElementById('home-preview-grid');
    if (!populares.length) {
      grid.innerHTML = '<div style="text-align:center;padding:40px;color:var(--gray);grid-column:1/-1">No hay paquetes disponibles</div>';
      return;
    }
    grid.innerHTML = populares.map(p => cardHTML(p)).join('');
  } catch(e) {
    document.getElementById('home-preview-grid').innerHTML = '<div style="text-align:center;padding:40px;color:var(--gray);grid-column:1/-1">⚠️ No se pudo conectar con el servidor</div>';
  }
}

// ============================================
// DESTINOS / PAQUETES
// ============================================
async function loadPaquetes() {
  const params = {
    region: currentRegion,
    buscar: document.getElementById('search-input').value,
    precio_max: document.getElementById('filter-precio').value,
    duracion: document.getElementById('filter-duracion').value,
  };
  const grid = document.getElementById('cards-grid');
  grid.innerHTML = '<div style="text-align:center;padding:80px;color:var(--gray);grid-column:1/-1">Cargando...</div>';
  try {
    const data = await apiGet('get_paquetes', params);
    document.getElementById('results-count').textContent = `${data.total} paquetes encontrados`;
    if (!data.data || !data.data.length) {
      grid.innerHTML = '<div class="empty-state" style="grid-column:1/-1"><div class="empty-state-icon">🌍</div><h3>No se encontraron paquetes</h3><p>Intenta con otros filtros</p></div>';
      return;
    }
    grid.innerHTML = data.data.map(p => cardHTML(p)).join('');
  } catch(e) {
    grid.innerHTML = '<div class="empty-state" style="grid-column:1/-1"><div class="empty-state-icon">⚠️</div><h3>Error de conexión</h3><p>Revisa que el servidor PHP esté activo</p></div>';
  }
}

function cardHTML(p) {
  const img = p.imagen_principal || (p.imagenes && p.imagenes[0]) || 'https://images.unsplash.com/photo-1488085061387-422e29b40080?w=800';
  return `
    <div class="card" onclick="showDetalle(${p.id})">
      <div class="card-img-wrap">
        <img class="card-img" src="${img}" alt="${p.nombre}" onerror="this.src='https://images.unsplash.com/photo-1488085061387-422e29b40080?w=800'">
        ${p.popular == 1 ? '<span class="card-badge-popular">POPULAR</span>' : ''}
        <span class="card-badge-region">${p.region}</span>
      </div>
      <div class="card-body">
        <h3 class="card-title">${p.nombre}</h3>
        <div class="card-location">📍 ${p.ubicacion}</div>
        <p class="card-desc">${p.descripcion}</p>
        <div class="card-meta">
          <span class="card-meta-item">📅 ${p.duracion_dias} días</span>
          <span class="card-meta-item">👤 ${p.personas_minimo} personas</span>
          <span class="card-meta-item">⭐ ${p.calificacion || '—'}</span>
        </div>
        <div class="card-footer">
          <div>
            <div class="card-price-label">Desde</div>
            <div class="card-price">$${Number(p.precio).toLocaleString()}</div>
          </div>
          <button class="btn-card">Ver Detalles</button>
        </div>
      </div>
    </div>`;
}

function setRegion(region, btn) {
  currentRegion = region;
  document.querySelectorAll('.filter-tab').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  loadPaquetes();
}

function applyFilters() {
  clearTimeout(window._filterTimer);
  window._filterTimer = setTimeout(loadPaquetes, 350);
}

// ============================================
// DETALLE
// ============================================
async function showDetalle(id) {
  currentPaqueteId = id;
  showPage('detalle');
  document.getElementById('detalle-content').innerHTML = '<div style="text-align:center;padding:80px;color:var(--gray)">Cargando...</div>';
  try {
    const data = await apiGet('get_paquete', { id });
    if (!data.success) { document.getElementById('detalle-content').innerHTML = '<div style="text-align:center;padding:80px">Paquete no encontrado</div>'; return; }
    renderDetalle(data);
  } catch(e) {
    document.getElementById('detalle-content').innerHTML = '<div style="text-align:center;padding:80px">⚠️ Error de conexión</div>';
  }
}

function renderDetalle(data) {
  const p = data.data;
  sliderImages = p.imagenes && p.imagenes.length ? p.imagenes : [p.imagen_principal || ''];
  sliderIndex = 0;

  document.getElementById('breadcrumb-title').textContent = p.nombre;

  const stars = (n) => '★'.repeat(Math.round(n)) + '☆'.repeat(5 - Math.round(n));
  const starsHTML = (n) => `<span style="color:#EAB308">${'★'.repeat(Math.round(n))}</span><span style="color:#d1d5db">${'☆'.repeat(5-Math.round(n))}</span>`;

  const thumbsHTML = sliderImages.map((img, i) =>
    `<div class="thumb ${i===0?'active':''}" onclick="goSlide(${i})"><img src="${img}" alt=""></div>`
  ).join('');

  const statsCards = `
    <div class="stats-row">
      <div class="stat-card"><div class="stat-icon">📅</div><div class="stat-label">Duración</div><div class="stat-val">${p.duracion_dias} días</div></div>
      <div class="stat-card"><div class="stat-icon">👤</div><div class="stat-label">Personas</div><div class="stat-val">${p.personas_minimo} personas</div></div>
      <div class="stat-card"><div class="stat-icon">⭐</div><div class="stat-label">Calificación</div><div class="stat-val">${p.calificacion || '—'} / 5.0</div></div>
      <div class="stat-card"><div class="stat-icon">💬</div><div class="stat-label">Reseñas</div><div class="stat-val">${data.resenas_stats.total} reseñas</div></div>
    </div>`;

  const incluye = (p.incluye || []).map(i => `<li><span class="icon-check">✅</span>${i}</li>`).join('');
  const noIncluye = (p.no_incluye || []).map(i => `<li><span class="icon-x">❌</span>${i}</li>`).join('');

  const itinerarioHTML = (data.itinerario || []).map(dia => `
    <div class="iti-item">
      <div class="iti-header" onclick="toggleIti(this)">
        <div class="iti-num">${dia.dia_numero}</div>
        <div><div class="iti-day">Día ${dia.dia_numero}</div><div class="iti-title">${dia.titulo}</div></div>
        <span class="iti-chevron">▾</span>
      </div>
      <div class="iti-body ${dia.dia_numero===1?'open':''}">
        <p class="iti-desc">${dia.descripcion || ''}</p>
        <div class="iti-tags">${(dia.actividades||[]).map(a=>`<span class="iti-tag">📍 ${a}</span>`).join('')}</div>
      </div>
    </div>`).join('');

  // Reseñas
  const stats = data.resenas_stats;
  const barsHTML = [5,4,3,2,1].map(n => {
    const pct = stats.total ? Math.round((stats.distribucion[n]/stats.total)*100) : 0;
    return `<div class="bar-row">
      <span class="bar-label">${n}★</span>
      <div class="bar-track"><div class="bar-fill" style="width:${pct}%"></div></div>
      <span class="bar-count">${stats.distribucion[n]}</span>
    </div>`;
  }).join('');

  const resenasHTML = (data.resenas || []).map(r => `
    <div class="resena-card">
      <div class="resena-card-header">
        <div class="resena-avatar">${r.nombre_usuario.charAt(0)}</div>
        <div>
          <div class="resena-user-name">${r.nombre_usuario} ${r.verificado?'<span class="resena-verified">✓ Verificado</span>':''}</div>
          <div class="resena-user-city">📍 ${r.ciudad || 'Anónimo'}</div>
        </div>
        <div class="resena-meta">
          <div class="resena-stars">${'★'.repeat(r.calificacion)}${'☆'.repeat(5-r.calificacion)}</div>
          <div class="resena-date">${r.fecha ? new Date(r.fecha).toLocaleDateString('es-MX',{month:'long',year:'numeric'}) : ''}</div>
        </div>
      </div>
      ${r.titulo ? `<div class="resena-titulo">${r.titulo}</div>` : ''}
      <p class="resena-text">${r.comentario}</p>
    </div>`).join('') || '<div style="text-align:center;padding:30px;color:var(--gray)">Sé el primero en dejar una reseña.</div>';

  const asideIncludes = (p.incluye || []).map(i => `<li>${i}</li>`).join('');

  document.getElementById('detalle-content').innerHTML = `
    <!-- SLIDER -->
    <div class="slider-wrap" id="main-slider">
      ${sliderImages.map((img,i) => `
        <div class="slider-slide ${i===0?'active':''}" data-idx="${i}">
          <img src="${img}" alt="${p.nombre}" onerror="this.src='https://images.unsplash.com/photo-1488085061387-422e29b40080?w=1200'">
        </div>`).join('')}
      <div class="slider-overlay">
        <div class="slider-badges">
          <span class="slider-badge badge-region">${p.region}</span>
          ${p.categoria ? `<span class="slider-badge badge-cat">${p.categoria}</span>` : ''}
          ${p.popular==1 ? `<span class="slider-badge badge-popular">★ POPULAR</span>` : ''}
        </div>
        <h1 class="slider-title">${p.nombre}</h1>
        <div class="slider-loc">📍 ${p.ubicacion}</div>
      </div>
      <div class="slider-price-box">
        <div class="slider-price-label">Desde</div>
        <div class="slider-price">$${Number(p.precio).toLocaleString()}</div>
        <div class="slider-price-sub">por persona</div>
      </div>
      ${sliderImages.length > 1 ? `
        <button class="slider-btn prev" onclick="changeSlide(-1)">‹</button>
        <button class="slider-btn next" onclick="changeSlide(1)">›</button>
        <div class="slider-dots">${sliderImages.map((_,i)=>`<div class="slider-dot ${i===0?'active':''}" onclick="goSlide(${i})"></div>`).join('')}</div>
      ` : ''}
    </div>

    ${sliderImages.length > 1 ? `<div class="thumbs-strip">${thumbsHTML}</div>` : ''}

    <div class="detalle-body">
      <div class="detalle-main">
        ${statsCards}
        <h2 class="section-title">Descripción del Paquete</h2>
        <p style="color:var(--gray);line-height:1.7;margin-bottom:30px;font-size:.97rem">${p.descripcion}</p>

        <div class="include-grid">
          <div class="include-card">
            <h4><span style="color:var(--green)">✅</span> Incluye</h4>
            <ul class="include-list">${incluye}</ul>
          </div>
          <div class="include-card">
            <h4><span style="color:var(--red)">❌</span> No Incluye</h4>
            <ul class="include-list">${noIncluye}</ul>
          </div>
        </div>

        ${data.itinerario && data.itinerario.length ? `
          <h2 class="section-title">Itinerario Día a Día</h2>
          <div class="itinerario-list">${itinerarioHTML}</div>
        ` : ''}

        <div style="margin-bottom:36px">
          <div class="resenas-header">
            <div>
              <h2 class="section-title" style="margin-bottom:2px">Reseñas de Viajeros</h2>
              <p class="resenas-subtitle">${stats.total} reseñas verificadas</p>
            </div>
            <button class="btn-resena" onclick="openModalResena(${p.id})">✍️ Escribir Reseña</button>
          </div>
          <div class="resenas-summary">
            <div class="resena-score">
              <div class="resena-score-num">${stats.promedio || '—'}</div>
              <div class="stars">${stats.promedio ? '★'.repeat(Math.round(stats.promedio)) + '☆'.repeat(5-Math.round(stats.promedio)) : '☆☆☆☆☆'}</div>
              <div style="font-size:.8rem;color:var(--gray);margin-top:4px">Calificación promedio</div>
            </div>
            <div class="resena-bars">${barsHTML}</div>
          </div>
          ${resenasHTML}
        </div>
      </div>

      <div class="detalle-aside">
        <div class="aside-card">
          <div class="aside-price-rating">
            <span class="aside-price-label">Precio por persona</span>
            <span class="aside-rating">⭐ ${p.calificacion || '—'} (${stats.total})</span>
          </div>
          <div class="aside-price-big">$${Number(p.precio).toLocaleString()}</div>
          <div class="aside-meta">
            <div class="aside-meta-row"><span class="aside-meta-icon">📅</span>${p.duracion_dias} días / ${p.duracion_noches} noches</div>
            <div class="aside-meta-row"><span class="aside-meta-icon">👤</span>Mínimo ${p.personas_minimo} personas</div>
            <div class="aside-meta-row"><span class="aside-meta-icon">📍</span>${p.ubicacion}</div>
            <div class="aside-meta-row"><span class="aside-meta-icon">🌎</span>${p.region}</div>
          </div>
          <button class="btn-reservar-paquete" onclick="openModalReserva(${p.id}, '${p.nombre.replace(/'/g,"\\'")}', ${p.precio})">
            📋 Reservar Este Paquete
          </button>
          <p class="aside-note">Sin cargos hasta confirmar</p>
          ${asideIncludes ? `
            <div class="aside-includes-title">Lo que incluye:</div>
            <ul class="aside-includes-list">${asideIncludes}</ul>
          ` : ''}
          <div class="aside-consulta">
            <p>¿Tienes preguntas sobre este paquete?</p>
            <button class="btn-consulta" onclick="showToast('Te contactaremos pronto 😊','success')">💬 Consultar con un experto</button>
          </div>
        </div>
      </div>
    </div>`;
}

// Slider
function changeSlide(dir) {
  sliderIndex = (sliderIndex + dir + sliderImages.length) % sliderImages.length;
  updateSlider();
}
function goSlide(i) {
  sliderIndex = i;
  updateSlider();
}
function updateSlider() {
  document.querySelectorAll('.slider-slide').forEach((s,i) => s.classList.toggle('active', i === sliderIndex));
  document.querySelectorAll('.slider-dot').forEach((d,i) => d.classList.toggle('active', i === sliderIndex));
  document.querySelectorAll('.thumb').forEach((t,i) => t.classList.toggle('active', i === sliderIndex));
}

// Itinerario accordion
function toggleIti(header) {
  const body = header.nextElementSibling;
  const chevron = header.querySelector('.iti-chevron');
  body.classList.toggle('open');
  chevron.style.transform = body.classList.contains('open') ? 'rotate(180deg)' : '';
}

// ============================================
// ADMIN
// ============================================
function initAdmin() {
  if (adminToken) {
    showAdminPanel();
    loadAdminPaquetes();
  }
}

async function adminLogin() {
  const usuario = document.getElementById('login-user').value;
  const password = document.getElementById('login-pass').value;
  const err = document.getElementById('login-error');
  err.style.display = 'none';
  try {
    const data = await apiPost('admin_login', { usuario, password });
    if (data.success) {
      adminToken = data.token;
      localStorage.setItem('admin_token', adminToken);
      showAdminPanel();
      loadAdminPaquetes();
    } else {
      err.textContent = data.error || 'Credenciales incorrectas';
      err.style.display = 'block';
    }
  } catch(e) {
    err.textContent = '⚠️ Error de conexión con el servidor';
    err.style.display = 'block';
  }
}

function showAdminPanel() {
  document.getElementById('admin-login-wrap').style.display = 'none';
  document.getElementById('admin-panel-wrap').style.display = 'block';
}

function adminLogout() {
  adminToken = null;
  localStorage.removeItem('admin_token');
  document.getElementById('admin-login-wrap').style.display = 'flex';
  document.getElementById('admin-panel-wrap').style.display = 'none';
}

function switchAdminTab(tab, btn) {
  document.querySelectorAll('.admin-tab').forEach(t => t.classList.remove('active'));
  btn.classList.add('active');
  document.querySelectorAll('.admin-panel').forEach(p => p.classList.remove('active'));
  document.getElementById('admin-' + tab).classList.add('active');
  if (tab === 'paquetes') loadAdminPaquetes();
  if (tab === 'reservas') loadAdminReservas();
}

async function loadAdminPaquetes() {
  const tbody = document.getElementById('admin-paquetes-tbody');
  tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:30px;color:var(--gray)">Cargando...</td></tr>';
  try {
    const data = await apiGet('admin_paquetes');
    if (!data.data || !data.data.length) {
      tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:30px;color:var(--gray)">No hay paquetes</td></tr>';
      return;
    }
    tbody.innerHTML = data.data.map(p => {
      const img = p.imagen_principal || (p.imagenes && p.imagenes[0]) || '';
      return `<tr>
        <td>${img ? `<img class="tbl-img" src="${img}" onerror="this.style.display='none'">` : '—'}</td>
        <td><div class="tbl-name">${p.nombre}</div><div class="tbl-loc">📍 ${p.ubicacion}</div></td>
        <td>${p.region}</td>
        <td>$${Number(p.precio).toLocaleString()}</td>
        <td>${p.duracion_dias} días</td>
        <td><span class="status-badge ${p.popular==1?'status-popular':'status-normal'}">${p.popular==1?'⭐ Popular':'Normal'}</span> &nbsp; <span class="status-badge ${p.activo==1?'status-confirmada':'status-cancelada'}">${p.activo==1?'Activo':'Oculto'}</span></td>
        <td><div class="tbl-actions">
          <button class="btn-tbl btn-edit" onclick="openModalPaquete(${p.id})">✏️ Editar</button>
          <button class="btn-tbl btn-delete" onclick="eliminarPaquete(${p.id})">🗑️ Eliminar</button>
        </div></td>
      </tr>`;
    }).join('');
  } catch(e) {
    tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:30px">⚠️ Error de conexión</td></tr>';
  }
}

async function loadAdminReservas() {
  const tbody = document.getElementById('admin-reservas-tbody');
  tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:30px;color:var(--gray)">Cargando...</td></tr>';
  try {
    const data = await apiGet('admin_reservas');
    if (!data.data || !data.data.length) {
      tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:30px;color:var(--gray)">No hay reservas</td></tr>';
      return;
    }
    tbody.innerHTML = data.data.map(r => `
      <tr>
        <td>#${r.id}</td>
        <td><strong>${r.nombre}</strong><br><span style="color:var(--gray);font-size:.82rem">${r.email}</span></td>
        <td>${r.paquete_nombre || '—'}</td>
        <td>${r.fecha_viaje || '—'}</td>
        <td>${r.personas}</td>
        <td><span class="status-badge status-${r.estado}">${r.estado}</span></td>
        <td><div class="tbl-actions">
          <button class="btn-tbl btn-confirm" onclick="cambiarEstadoReserva(${r.id},'confirmada')">✓</button>
          <button class="btn-tbl btn-cancel-r" onclick="cambiarEstadoReserva(${r.id},'cancelada')">✕</button>
        </div></td>
      </tr>`).join('');
  } catch(e) {
    tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:30px">⚠️ Error de conexión</td></tr>';
  }
}

async function cambiarEstadoReserva(id, estado) {
  try {
    await apiPut('admin_reserva_estado', { id, estado });
    showToast(`Reserva ${estado}`, 'success');
    loadAdminReservas();
  } catch(e) { showToast('Error al actualizar','error'); }
}

async function eliminarPaquete(id) {
  if (!confirm('¿Seguro que deseas ocultar este paquete?')) return;
  try {
    await apiDelete('admin_eliminar_paquete', { id });
    showToast('Paquete ocultado', 'success');
    loadAdminPaquetes();
  } catch(e) { showToast('Error al eliminar','error'); }
}

// ============================================
// MODAL PAQUETE
// ============================================
async function openModalPaquete(id = null) {
  editingPaqueteId = id;
  document.getElementById('modal-paquete-title').textContent = id ? 'Editar Paquete' : 'Nuevo Paquete';
  // Reset
  ['mp-id','mp-nombre','mp-ubicacion','mp-descripcion','mp-precio','mp-dias','mp-noches','mp-categoria','mp-imagen-principal'].forEach(f => {
    const el = document.getElementById(f);
    if(el) el.value = '';
  });
  document.getElementById('mp-region').value = '';
  document.getElementById('mp-personas').value = 2;
  document.getElementById('mp-popular').checked = false;
  document.getElementById('mp-imagenes-list').innerHTML = '';
  document.getElementById('mp-incluye-list').innerHTML = '';
  document.getElementById('mp-no-incluye-list').innerHTML = '';
  document.getElementById('mp-itinerario-list').innerHTML = '';
  itinerarioDiaCount = 0;

  if (id) {
    try {
      const data = await apiGet('get_paquete', { id });
      const p = data.data;
      document.getElementById('mp-id').value = p.id;
      document.getElementById('mp-nombre').value = p.nombre;
      document.getElementById('mp-ubicacion').value = p.ubicacion;
      document.getElementById('mp-descripcion').value = p.descripcion;
      document.getElementById('mp-precio').value = p.precio;
      document.getElementById('mp-dias').value = p.duracion_dias;
      document.getElementById('mp-noches').value = p.duracion_noches;
      document.getElementById('mp-region').value = p.region;
      document.getElementById('mp-categoria').value = p.categoria || '';
      document.getElementById('mp-personas').value = p.personas_minimo || 2;
      document.getElementById('mp-popular').checked = p.popular == 1;
      document.getElementById('mp-imagen-principal').value = p.imagen_principal || '';
      (p.imagenes || []).forEach(url => addDynamicItem('mp-imagenes-list','url','URL...',url));
      (p.incluye || []).forEach(item => addDynamicItem('mp-incluye-list','text','Ítem...',item));
      (p.no_incluye || []).forEach(item => addDynamicItem('mp-no-incluye-list','text','Ítem...',item));
      (data.itinerario || []).forEach(dia => addItinerarioDia(dia));
    } catch(e) { showToast('Error cargando paquete','error'); }
  }

  openModal('modal-paquete');
}

function addDynamicItem(listId, type, placeholder, value = '') {
  const list = document.getElementById(listId);
  const div = document.createElement('div');
  div.className = 'dynamic-item';
  div.innerHTML = `<input type="${type}" placeholder="${placeholder}" value="${value.replace(/"/g,'&quot;')}">
    <button class="btn-remove-item" onclick="this.parentElement.remove()">✕</button>`;
  list.appendChild(div);
}

function addItinerarioDia(data = null) {
  itinerarioDiaCount++;
  const n = itinerarioDiaCount;
  const list = document.getElementById('mp-itinerario-list');
  const div = document.createElement('div');
  div.className = 'iti-form-item';
  div.innerHTML = `
    <div class="iti-day-num">Día ${data ? data.dia_numero : n}</div>
    <button class="btn-remove-day" onclick="this.parentElement.remove()">✕</button>
    <input type="hidden" class="iti-dia-num" value="${data ? data.dia_numero : n}">
    <div class="form-group">
      <label class="form-label" style="font-size:.8rem">Título del día</label>
      <input type="text" class="form-input" style="font-size:.88rem" placeholder="Ej: Llegada y bienvenida" value="${data ? data.titulo : ''}">
    </div>
    <div class="form-group">
      <label class="form-label" style="font-size:.8rem">Descripción</label>
      <textarea class="form-input form-textarea" style="font-size:.88rem;min-height:60px" placeholder="Descripción del día...">${data ? data.descripcion || '' : ''}</textarea>
    </div>
    <div class="form-group">
      <label class="form-label" style="font-size:.8rem">Actividades (una por línea)</label>
      <textarea class="form-input iti-actividades" style="font-size:.88rem;min-height:50px" placeholder="Traslado hotel&#10;Tour guiado&#10;...">${data && data.actividades ? data.actividades.join('\n') : ''}</textarea>
    </div>`;
  list.appendChild(div);
}

async function savePaquete() {
  const id = document.getElementById('mp-id').value;
  const nombre = document.getElementById('mp-nombre').value.trim();
  const ubicacion = document.getElementById('mp-ubicacion').value.trim();
  const descripcion = document.getElementById('mp-descripcion').value.trim();
  const precio = parseFloat(document.getElementById('mp-precio').value);
  const duracion_dias = parseInt(document.getElementById('mp-dias').value);
  const duracion_noches = parseInt(document.getElementById('mp-noches').value);
  const region = document.getElementById('mp-region').value;

  if (!nombre || !ubicacion || !descripcion || !precio || !duracion_dias || !region) {
    showToast('Completa todos los campos requeridos','error'); return;
  }

  const imagenes = [...document.querySelectorAll('#mp-imagenes-list .dynamic-item input')].map(i=>i.value).filter(Boolean);
  const incluye = [...document.querySelectorAll('#mp-incluye-list .dynamic-item input')].map(i=>i.value).filter(Boolean);
  const no_incluye = [...document.querySelectorAll('#mp-no-incluye-list .dynamic-item input')].map(i=>i.value).filter(Boolean);
  const itinerario = [...document.querySelectorAll('.iti-form-item')].map(item => ({
    dia_numero: parseInt(item.querySelector('.iti-dia-num').value),
    titulo: item.querySelectorAll('input[type=text]')[0]?.value || '',
    descripcion: item.querySelectorAll('textarea')[0]?.value || '',
    actividades: (item.querySelectorAll('textarea')[1]?.value || '').split('\n').filter(Boolean)
  }));

  const body = {
    nombre, ubicacion, descripcion, precio, duracion_dias, duracion_noches,
    personas_minimo: parseInt(document.getElementById('mp-personas').value) || 2,
    region, categoria: document.getElementById('mp-categoria').value,
    popular: document.getElementById('mp-popular').checked ? 1 : 0,
    imagen_principal: document.getElementById('mp-imagen-principal').value,
    imagenes, incluye, no_incluye, itinerario
  };

  try {
    let data;
    if (id) {
      body.id = parseInt(id);
      data = await apiPut('admin_editar_paquete', body);
    } else {
      data = await apiPost('admin_crear_paquete', body);
    }
    if (data.success) {
      showToast(id ? 'Paquete actualizado ✓' : 'Paquete creado ✓', 'success');
      closeModal('modal-paquete');
      loadAdminPaquetes();
    } else {
      showToast(data.error || 'Error al guardar','error');
    }
  } catch(e) { showToast('Error de conexión','error'); }
}

// ============================================
// MODAL RESERVA
// ============================================
function openModalReserva(id, nombre, precio) {
  document.getElementById('res-paquete-id').value = id;
  document.getElementById('res-paquete-nombre').textContent = nombre;
  document.getElementById('res-paquete-precio').textContent = `Desde $${Number(precio).toLocaleString()} por persona`;
  openModal('modal-reserva');
}

async function enviarReserva() {
  const nombre = document.getElementById('res-nombre').value.trim();
  const email = document.getElementById('res-email').value.trim();
  if (!nombre || !email) { showToast('Nombre y email son requeridos','error'); return; }
  try {
    const data = await apiPost('crear_reserva', {
      paquete_id: document.getElementById('res-paquete-id').value,
      nombre, email,
      telefono: document.getElementById('res-telefono').value,
      fecha_viaje: document.getElementById('res-fecha').value,
      personas: document.getElementById('res-personas').value,
      mensaje: document.getElementById('res-mensaje').value
    });
    if (data.success) {
      showToast(data.mensaje || '¡Reserva enviada!', 'success');
      closeModal('modal-reserva');
      ['res-nombre','res-email','res-telefono','res-fecha','res-mensaje'].forEach(id => document.getElementById(id).value = '');
    } else { showToast(data.error || 'Error al reservar','error'); }
  } catch(e) { showToast('Error de conexión','error'); }
}

// ============================================
// MODAL RESEÑA
// ============================================
function openModalResena(id) {
  document.getElementById('rr-paquete-id').value = id;
  document.getElementById('rr-calificacion').value = 0;
  setResenaStars(0);
  openModal('modal-resena');
}

function setResenaStars(val) {
  document.getElementById('rr-calificacion').value = val;
  document.querySelectorAll('.rr-star').forEach(s => {
    s.style.color = parseInt(s.dataset.val) <= val ? '#EAB308' : '#d1d5db';
  });
}

async function enviarResena() {
  const nombre = document.getElementById('rr-nombre').value.trim();
  const cal = parseInt(document.getElementById('rr-calificacion').value);
  const comentario = document.getElementById('rr-comentario').value.trim();
  if (!nombre || !cal || !comentario) { showToast('Completa nombre, calificación y comentario','error'); return; }
  try {
    const data = await apiPost('crear_resena', {
      paquete_id: document.getElementById('rr-paquete-id').value,
      nombre_usuario: nombre,
      ciudad: document.getElementById('rr-ciudad').value,
      calificacion: cal,
      titulo: document.getElementById('rr-titulo').value,
      comentario
    });
    if (data.success) {
      showToast('¡Reseña publicada! Gracias 😊','success');
      closeModal('modal-resena');
      if (currentPaqueteId) showDetalle(currentPaqueteId);
    } else { showToast(data.error || 'Error','error'); }
  } catch(e) { showToast('Error de conexión','error'); }
}

// ============================================
// RESERVAS LIST (página pública)
// ============================================
async function loadReservasList() {
  // Solo muestra una búsqueda por email
  document.getElementById('reservas-list').innerHTML = `
    <div style="background:#fff;border-radius:var(--radius);border:1.5px solid var(--border);padding:32px;max-width:500px">
      <h3 style="margin-bottom:16px;font-weight:600">Buscar mis reservas</h3>
      <div class="form-group">
        <label class="form-label">Tu email de reserva</label>
        <input type="email" class="form-input" id="buscar-email" placeholder="tu@email.com">
      </div>
      <button class="btn-primary" onclick="buscarReservasPorEmail()" style="margin-top:8px">Buscar</button>
      <div id="mis-reservas-resultado" style="margin-top:24px"></div>
    </div>`;
}

// ============================================
// MODAL UTILS
// ============================================
function openModal(id) { document.getElementById(id).classList.add('open'); document.body.style.overflow = 'hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow = ''; }
document.querySelectorAll('.modal-overlay').forEach(m => m.addEventListener('click', e => { if(e.target === m) closeModal(m.id); }));

// ============================================
// TOAST
// ============================================
function showToast(msg, type = '') {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.className = `toast ${type}`;
  setTimeout(() => t.classList.add('show'), 10);
  setTimeout(() => t.classList.remove('show'), 3500);
}

// ============================================
// INIT
// ============================================
loadHomePreview();
