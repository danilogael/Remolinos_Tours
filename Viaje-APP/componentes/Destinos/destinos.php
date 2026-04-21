<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Remolinos Tours</title>
<link rel="stylesheet" href="destinos.css">
<link rel="icon" href="https://cdn-icons-png.flaticon.com/512/854/854878.png">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
<!-- ══ HEADER ══ -->
<?php include __DIR__ . "/Viaje-APP/componentes/header/header.php"; ?>
  
<!-- ====== DESTINOS ====== -->
<div class="page" id="page-destinos">
  <div class="destinos-hero">
    <div class="destinos-hero-content">
      <div class="destinos-hero-tag">EXPLORA EL MUNDO</div>
      <h1 class="destinos-hero-title">Nuestros Destinos</h1>
      <p class="destinos-hero-sub">Descubre más de 50 destinos increíbles alrededor del mundo</p>
    </div>
  </div>

  <div class="filters-bar">
    <div class="search-wrap">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" class="search-input" id="search-input" placeholder="Buscar destino..." oninput="applyFilters()">
    </div>
    <div class="filter-tabs">
      <button class="filter-tab active" onclick="setRegion('',this)">Todos</button>
      <button class="filter-tab" onclick="setRegion('America',this)">América</button>
      <button class="filter-tab" onclick="setRegion('Europa',this)">Europa</button>
      <button class="filter-tab" onclick="setRegion('Asia',this)">Asia</button>
      <button class="filter-tab" onclick="setRegion('Africa',this)">África</button>
    </div>
    <select class="filter-select" id="filter-precio" onchange="applyFilters()">
      <option value="">Todos los precios</option>
      <option value="1500">Hasta $1,500</option>
      <option value="2500">Hasta $2,500</option>
      <option value="3500">Hasta $3,500</option>
    </select>
    <select class="filter-select" id="filter-duracion" onchange="applyFilters()">
      <option value="">Todas las duraciones</option>
      <option value="7">Hasta 7 días</option>
      <option value="10">Hasta 10 días</option>
      <option value="14">Hasta 14 días</option>
    </select>
    <span class="results-count" id="results-count">Cargando...</span>
  </div>

  <div class="cards-section">
    <div class="cards-grid" id="cards-grid">
      <div style="text-align:center;padding:80px;color:var(--gray);grid-column:1/-1">Cargando paquetes...</div>
    </div>
  </div>
</div>

<!-- ====== DETALLE ====== -->
<div class="page" id="page-detalle">
  <div class="detalle-nav">
    <span onclick="showPage('home')">Inicio</span>
    <span class="sep">›</span>
    <span onclick="showPage('destinos')">Destinos</span>
    <span class="sep">›</span>
    <span id="breadcrumb-title" style="color:var(--orange)">—</span>
  </div>
  <div id="detalle-content"></div>
</div>

<!-- ====== RESERVAS (lista) ====== -->
<div class="page" id="page-reservas">
  <div style="max-width:1100px;margin:0 auto;padding:40px;">
    <h2 style="font-family:'Playfair Display',serif;font-size:2rem;margin-bottom:6px;">Mis Reservas</h2>
    <p style="color:var(--gray);margin-bottom:30px;">Historial de reservas realizadas</p>
    <div id="reservas-list">Cargando...</div>
  </div>
</div>

<!-- ====== ADMIN ====== -->
<div class="page" id="page-admin">
  <!-- Login -->
  <div id="admin-login-wrap" class="admin-login-wrap">
    <div class="admin-login-card">
      <div class="admin-login-icon">🔒</div>
      <h2 class="admin-login-title">Panel Admin</h2>
      <p class="admin-login-sub">Ingresa tus credenciales para continuar</p>
      <div class="error-msg" id="login-error"></div>
      <div class="form-group">
        <label class="form-label">Usuario</label>
        <input type="text" class="form-input" id="login-user" placeholder="admin" value="admin">
      </div>
      <div class="form-group">
        <label class="form-label">Contraseña</label>
        <input type="password" class="form-input" id="login-pass" placeholder="••••••••" value="admin123">
      </div>
      <button class="btn-full" onclick="adminLogin()">Ingresar</button>
    </div>
  </div>

  <!-- Panel -->
  <div id="admin-panel-wrap" style="display:none;">
    <div class="admin-header">
      <h2>🌀 Remolinos Tours — Admin</h2>
      <div class="admin-tabs">
        <button class="admin-tab active" onclick="switchAdminTab('paquetes',this)">Paquetes</button>
        <button class="admin-tab" onclick="switchAdminTab('reservas',this)">Reservas</button>
      </div>
      <div class="admin-actions">
        <button class="btn-logout" onclick="adminLogout()">Cerrar sesión</button>
      </div>
    </div>
    <div class="admin-content">
      <!-- PAQUETES -->
      <div class="admin-panel active" id="admin-paquetes">
        <div class="admin-card">
          <div class="admin-card-header">
            <span class="admin-card-title">Paquetes de Viaje</span>
            <button class="btn-agregar" onclick="openModalPaquete()">＋ Agregar Paquete</button>
          </div>
          <div style="overflow-x:auto">
            <table class="admin-table">
              <thead>
                <tr>
                  <th>Imagen</th><th>Nombre</th><th>Región</th><th>Precio</th><th>Días</th><th>Estado</th><th>Acciones</th>
                </tr>
              </thead>
              <tbody id="admin-paquetes-tbody">
                <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--gray)">Cargando...</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- RESERVAS -->
      <div class="admin-panel" id="admin-reservas">
        <div class="admin-card">
          <div class="admin-card-header">
            <span class="admin-card-title">Reservas Recibidas</span>
          </div>
          <div style="overflow-x:auto">
            <table class="admin-table">
              <thead>
                <tr><th>ID</th><th>Cliente</th><th>Paquete</th><th>Fecha Viaje</th><th>Personas</th><th>Estado</th><th>Acciones</th></tr>
              </thead>
              <tbody id="admin-reservas-tbody">
                <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--gray)">Cargando...</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ====== MODAL PAQUETE ====== -->
<div class="modal-overlay" id="modal-paquete">
  <div class="modal">
    <div class="modal-header">
      <h3 class="modal-title" id="modal-paquete-title">Nuevo Paquete</h3>
      <button class="modal-close" onclick="closeModal('modal-paquete')">✕</button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="mp-id">
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Nombre del paquete *</label>
          <input type="text" class="form-input" id="mp-nombre" placeholder="Ej: Caribe Mexicano Mágico">
        </div>
        <div class="form-group">
          <label class="form-label">Ubicación *</label>
          <input type="text" class="form-input" id="mp-ubicacion" placeholder="Ej: Cancún, México">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Descripción *</label>
        <textarea class="form-input form-textarea" id="mp-descripcion" placeholder="Descripción detallada del paquete..."></textarea>
      </div>
      <div class="form-row-3">
        <div class="form-group">
          <label class="form-label">Precio (USD) *</label>
          <input type="number" class="form-input" id="mp-precio" placeholder="1299">
        </div>
        <div class="form-group">
          <label class="form-label">Días *</label>
          <input type="number" class="form-input" id="mp-dias" placeholder="7">
        </div>
        <div class="form-group">
          <label class="form-label">Noches *</label>
          <input type="number" class="form-input" id="mp-noches" placeholder="6">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Región *</label>
          <select class="form-input" id="mp-region">
            <option value="">Seleccionar...</option>
            <option value="America">América</option>
            <option value="Europa">Europa</option>
            <option value="Asia">Asia</option>
            <option value="Africa">África</option>
            <option value="Oceania">Oceanía</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Categoría</label>
          <input type="text" class="form-input" id="mp-categoria" placeholder="Ej: Playa & Cultura">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Personas mínimo</label>
          <input type="number" class="form-input" id="mp-personas" placeholder="2" value="2">
        </div>
        <div class="form-group" style="display:flex;align-items:flex-end;gap:10px;padding-bottom:2px">
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-weight:600;font-size:.88rem">
            <input type="checkbox" id="mp-popular" style="width:18px;height:18px;accent-color:var(--orange)">
            Marcar como POPULAR
          </label>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Imagen principal (URL)</label>
        <input type="url" class="form-input" id="mp-imagen-principal" placeholder="https://...">
        <p class="form-hint">URL de la imagen principal del paquete</p>
      </div>
      <div class="form-group">
        <label class="form-label">Galería de imágenes (URLs)</label>
        <div class="dynamic-list" id="mp-imagenes-list"></div>
        <button class="btn-add-item" onclick="addDynamicItem('mp-imagenes-list','url','URL de imagen...')">+ Agregar imagen</button>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">✅ Incluye</label>
          <div class="dynamic-list" id="mp-incluye-list"></div>
          <button class="btn-add-item" onclick="addDynamicItem('mp-incluye-list','text','Ej: Vuelo redondo')">+ Agregar ítem</button>
        </div>
        <div class="form-group">
          <label class="form-label">❌ No incluye</label>
          <div class="dynamic-list" id="mp-no-incluye-list"></div>
          <button class="btn-add-item" onclick="addDynamicItem('mp-no-incluye-list','text','Ej: Comidas libres')">+ Agregar ítem</button>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">📅 Itinerario día a día</label>
        <div id="mp-itinerario-list"></div>
        <button class="btn-add-item" onclick="addItinerarioDia()">+ Agregar día</button>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-cancel" onclick="closeModal('modal-paquete')">Cancelar</button>
      <button class="btn-save" onclick="savePaquete()">💾 Guardar Paquete</button>
    </div>
  </div>
</div>

<!-- ====== MODAL RESERVA ====== -->
<div class="modal-overlay" id="modal-reserva">
  <div class="modal" style="max-width:520px">
    <div class="modal-header">
      <h3 class="modal-title">Reservar Paquete</h3>
      <button class="modal-close" onclick="closeModal('modal-reserva')">✕</button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="res-paquete-id">
      <div style="background:var(--orange-light);border-radius:10px;padding:14px;margin-bottom:20px;font-size:.9rem;">
        <strong id="res-paquete-nombre"></strong><br>
        <span style="color:var(--gray)" id="res-paquete-precio"></span>
      </div>
      <div class="form-group">
        <label class="form-label">Nombre completo *</label>
        <input type="text" class="form-input" id="res-nombre" placeholder="Tu nombre">
      </div>
      <div class="form-group">
        <label class="form-label">Email *</label>
        <input type="email" class="form-input" id="res-email" placeholder="tu@email.com">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Teléfono</label>
          <input type="tel" class="form-input" id="res-telefono" placeholder="+52 ...">
        </div>
        <div class="form-group">
          <label class="form-label">Personas</label>
          <input type="number" class="form-input" id="res-personas" value="2" min="1">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Fecha de viaje deseada</label>
        <input type="date" class="form-input" id="res-fecha">
      </div>
      <div class="form-group">
        <label class="form-label">Mensaje adicional</label>
        <textarea class="form-input form-textarea" id="res-mensaje" placeholder="Solicitudes especiales, preguntas..."></textarea>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-cancel" onclick="closeModal('modal-reserva')">Cancelar</button>
      <button class="btn-save" onclick="enviarReserva()">📋 Enviar Reserva</button>
    </div>
  </div>
</div>

<!-- ====== MODAL RESEÑA ====== -->
<div class="modal-overlay" id="modal-resena">
  <div class="modal" style="max-width:500px">
    <div class="modal-header">
      <h3 class="modal-title">✍️ Escribir Reseña</h3>
      <button class="modal-close" onclick="closeModal('modal-resena')">✕</button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="rr-paquete-id">
      <div class="form-group">
        <label class="form-label">Tu nombre *</label>
        <input type="text" class="form-input" id="rr-nombre" placeholder="Nombre completo">
      </div>
      <div class="form-group">
        <label class="form-label">Ciudad</label>
        <input type="text" class="form-input" id="rr-ciudad" placeholder="Tu ciudad">
      </div>
      <div class="form-group">
        <label class="form-label">Calificación *</label>
        <div style="display:flex;gap:10px;margin-top:8px" id="rr-stars-row">
          <span class="rr-star" data-val="1" onclick="setResenaStars(1)" style="font-size:1.8rem;cursor:pointer;color:#d1d5db">★</span>
          <span class="rr-star" data-val="2" onclick="setResenaStars(2)" style="font-size:1.8rem;cursor:pointer;color:#d1d5db">★</span>
          <span class="rr-star" data-val="3" onclick="setResenaStars(3)" style="font-size:1.8rem;cursor:pointer;color:#d1d5db">★</span>
          <span class="rr-star" data-val="4" onclick="setResenaStars(4)" style="font-size:1.8rem;cursor:pointer;color:#d1d5db">★</span>
          <span class="rr-star" data-val="5" onclick="setResenaStars(5)" style="font-size:1.8rem;cursor:pointer;color:#d1d5db">★</span>
        </div>
        <input type="hidden" id="rr-calificacion" value="0">
      </div>
      <div class="form-group">
        <label class="form-label">Título de la reseña</label>
        <input type="text" class="form-input" id="rr-titulo" placeholder="Resumen en una frase">
      </div>
      <div class="form-group">
        <label class="form-label">Tu experiencia *</label>
        <textarea class="form-input form-textarea" id="rr-comentario" placeholder="Comparte los detalles de tu viaje..." style="min-height:110px"></textarea>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-cancel" onclick="closeModal('modal-resena')">Cancelar</button>
      <button class="btn-save" onclick="enviarResena()">Publicar Reseña</button>
    </div>
  </div>
</div>

<!-- TOAST -->
<div class="toast" id="toast"></div>
<?php include __DIR__ . "/Viaje-APP/componentes/footer/footer.php"; ?>
<script src="javascript.js"></script>
</body>
</html>
