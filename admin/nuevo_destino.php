<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../Login_APP/login.php"); exit();
}
include('../Database/conexion.php');

$proveedores = mysqli_query($conexion,
    "SELECT id_proveedor, nombre_proveedor FROM proveedores ORDER BY nombre_proveedor"
);

$tituloPagina = 'Nuevo Destino';
$paginaActual = 'destinos';
require_once 'includes/header.php';
?>

<div class="mb-4">
    <a href="destinos.php" class="text-muted small text-decoration-none">
        <i class="fa-solid fa-arrow-left me-1"></i> Volver a Destinos
    </a>
    <h4 class="fw-bold mt-1 mb-0">Nuevo Paquete de Viaje</h4>
</div>

<?php if (isset($_GET['error'])): ?>
<div class="alert alert-danger"><?= htmlspecialchars(urldecode($_GET['error'])) ?></div>
<?php endif; ?>

<form method="POST" action="guardar_destino.php" enctype="multipart/form-data" novalidate>
<div class="row g-4">

    <!-- COL IZQUIERDA: datos principales -->
    <div class="col-12 col-lg-7">

        <div class="card panel-card p-4 mb-4">
            <h6 class="fw-semibold mb-3">Información principal</h6>
            <div class="mb-3">
                <label class="form-label fw-medium">Nombre del destino</label>
                <input type="text" name="titulo" class="form-control" placeholder="Ej: Cancún All Inclusive" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-medium">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3" placeholder="Describe el paquete..."></textarea>
            </div>
            <div class="row g-3">
                <div class="col-6">
                    <label class="form-label fw-medium">Precio Adulto (MXN)</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="precio_adulto" class="form-control" min="0" step="0.01" placeholder="0.00" required>
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label fw-medium">Precio Niño (MXN)</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="precio_nino" class="form-control" min="0" step="0.01" placeholder="0.00">
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label fw-medium">Fecha de salida</label>
                    <input type="date" name="fecha_salida" class="form-control">
                </div>
                <div class="col-3">
                    <label class="form-label fw-medium">Días</label>
                    <input type="number" name="dias" class="form-control" min="1" value="1">
                </div>
                <div class="col-3">
                    <label class="form-label fw-medium">Noches</label>
                    <input type="number" name="noches" class="form-control" min="0" value="0">
                </div>
            </div>
        </div>

        <div class="card panel-card p-4 mb-4">
            <h6 class="fw-semibold mb-3">Detalles del vuelo</h6>
            <div class="row g-3">
                <div class="col-6">
                    <label class="form-label fw-medium">Punto de salida</label>
                    <input type="text" name="punto_salida" class="form-control" placeholder="Ej: Aguascalientes">
                </div>
                <div class="col-6">
                    <label class="form-label fw-medium">Tipo de trayecto</label>
                    <select name="tipo_trayecto" class="form-select">
                        <option value="Redondo">Redondo</option>
                        <option value="Solo ida">Solo ida</option>
                        <option value="Charter">Charter</option>
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label fw-medium">Maleta de mano (kg)</label>
                    <input type="number" name="kg_mano" class="form-control" min="0" value="10">
                </div>
                <div class="col-6">
                    <label class="form-label fw-medium">Maleta documentada (kg)</label>
                    <input type="number" name="kg_doc" class="form-control" min="0" value="25">
                </div>
                <div class="col-6">
                    <label class="form-label fw-medium">Cupo total</label>
                    <input type="number" name="cupo" class="form-control" min="1" value="20">
                </div>
                <div class="col-6">
                    <label class="form-label fw-medium">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                        <option value="Agotado">Agotado</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Itinerario dinámico -->
        <div class="card panel-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="fw-semibold mb-0">Itinerario por días</h6>
                <button type="button" class="btn btn-sm btn-outline-primary" id="btnAgregarDia">
                    <i class="fa-solid fa-plus me-1"></i> Agregar día
                </button>
            </div>
            <div id="contenedorItinerario"></div>
        </div>

    </div>

    <!-- COL DERECHA -->
    <div class="col-12 col-lg-5">

        <div class="card panel-card p-4 mb-4">
            <h6 class="fw-semibold mb-3">Imagen de portada</h6>
            <input type="file" name="foto_portada" class="form-control" accept=".jpg,.jpeg,.png,.webp">
            <small class="text-muted">JPG, PNG o WebP. Se guarda en assets/imagenes/</small>
        </div>

        <div class="card panel-card p-4 mb-4">
            <h6 class="fw-semibold mb-3">Socio / Aerolínea</h6>
            <select name="id_proveedor" class="form-select">
                <option value="">— Sin proveedor —</option>
                <?php while ($p = mysqli_fetch_assoc($proveedores)): ?>
                <option value="<?= $p['id_proveedor'] ?>"><?= htmlspecialchars($p['nombre_proveedor']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="card panel-card p-4 mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="seguro" id="seguro">
                <label class="form-check-label fw-medium" for="seguro">
                    Seguro básico incluido
                </label>
            </div>
        </div>

        <!-- Actividades extra -->
        <div class="card panel-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="fw-semibold mb-0">Actividades Extra</h6>
                <button type="button" class="btn btn-sm btn-outline-success" id="btnAgregarExtra">
                    <i class="fa-solid fa-plus me-1"></i> Agregar
                </button>
            </div>
            <div id="contenedorExtras"></div>
        </div>

    </div>

</div>

<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary px-5">
        <i class="fa-solid fa-floppy-disk me-1"></i> Guardar Paquete
    </button>
    <a href="destinos.php" class="btn btn-outline-secondary">Cancelar</a>
</div>
</form>

<script>
let diaCount = 0;
let extraCount = 0;

document.getElementById('btnAgregarDia').addEventListener('click', () => {
    diaCount++;
    const d = diaCount;
    const div = document.createElement('div');
    div.className = 'border rounded p-3 mb-2 position-relative';
    div.innerHTML = `
        <button type="button" class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-2 btn-rm"
                onclick="this.closest('.border').remove()">✕</button>
        <p class="fw-medium mb-2 small text-muted">Día ${d}</p>
        <div class="mb-2">
            <input type="text" name="itinerario_titulo[]" class="form-control form-control-sm"
                   placeholder="Título de la actividad" required>
        </div>
        <textarea name="itinerario_desc[]" class="form-control form-control-sm" rows="2"
                  placeholder="Descripción del día..."></textarea>
    `;
    document.getElementById('contenedorItinerario').appendChild(div);
});

document.getElementById('btnAgregarExtra').addEventListener('click', () => {
    extraCount++;
    const div = document.createElement('div');
    div.className = 'd-flex gap-2 mb-2 align-items-center';
    div.innerHTML = `
        <input type="text" name="extra_nombre[]" class="form-control form-control-sm" placeholder="Nombre actividad" required>
        <div class="input-group input-group-sm" style="max-width:120px;">
            <span class="input-group-text">$</span>
            <input type="number" name="extra_precio[]" class="form-control" min="0" step="0.01" placeholder="0.00">
        </div>
        <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.d-flex').remove()">✕</button>
    `;
    document.getElementById('contenedorExtras').appendChild(div);
});
</script>

<?php require_once 'includes/footer.php'; ?>
