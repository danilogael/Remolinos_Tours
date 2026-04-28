<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../Login_APP/login.php"); exit();
}
include('../Database/conexion.php');

$flash   = $_SESSION['flash'] ?? null;
$errores = [];
unset($_SESSION['flash']);

// Guardar nuevo proveedor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar'])) {
    $nombre   = trim(mysqli_real_escape_string($conexion, $_POST['nombre'] ?? ''));
    $tipo     = trim(mysqli_real_escape_string($conexion, $_POST['tipo']   ?? ''));
    $contacto = trim(mysqli_real_escape_string($conexion, $_POST['contacto'] ?? ''));
    $telefono = trim(mysqli_real_escape_string($conexion, $_POST['telefono'] ?? ''));
    $email    = trim(mysqli_real_escape_string($conexion, $_POST['email']    ?? ''));

    if ($nombre === '') $errores[] = 'El nombre es obligatorio.';
    if ($tipo   === '') $errores[] = 'El tipo es obligatorio.';

    if (empty($errores)) {
        $sql = "INSERT INTO proveedores (nombre_proveedor, tipo_proveedor, contacto, telefono, email)
                VALUES ('$nombre','$tipo','$contacto','$telefono','$email')";
        if (mysqli_query($conexion, $sql)) {
            $_SESSION['flash'] = ['tipo'=>'success','msg'=>'Proveedor registrado correctamente.'];
            header('Location: proveedores.php'); exit();
        }
        $errores[] = 'Error al guardar: ' . mysqli_error($conexion);
    }
}

$proveedores = mysqli_query($conexion,
    "SELECT * FROM proveedores ORDER BY nombre_proveedor ASC"
);

$tituloPagina = 'Aerolíneas y Socios';
$paginaActual = 'proveedores';
require_once 'includes/header.php';
?>

<div class="mb-4">
    <h4 class="fw-bold mb-0">Aerolíneas y Socios</h4>
    <p class="text-muted small mb-0">Gestiona los proveedores y socios comerciales</p>
</div>

<?php if ($flash): ?>
<div class="alert alert-<?= $flash['tipo'] ?> alert-dismissible show" data-auto-dismiss role="alert">
    <?= htmlspecialchars($flash['msg']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row g-4">

    <!-- Formulario -->
    <div class="col-12 col-md-4">
        <div class="card panel-card p-4">
            <h6 class="fw-semibold mb-3">Nuevo Proveedor</h6>

            <?php if ($errores): ?>
            <div class="alert alert-danger py-2 small">
                <ul class="mb-0"><?php foreach ($errores as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
            </div>
            <?php endif; ?>

            <form method="POST" novalidate>
                <div class="mb-3">
                    <label class="form-label fw-medium small">Nombre</label>
                    <input type="text" name="nombre" class="form-control form-control-sm"
                           placeholder="Ej: Volaris" value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium small">Tipo</label>
                    <select name="tipo" class="form-select form-select-sm">
                        <option value="">— Seleccionar —</option>
                        <?php foreach(['Aerolínea','Hotel','Crucero','Agencia Local','Transporte','Otro'] as $t): ?>
                        <option value="<?= $t ?>" <?= ($_POST['tipo'] ?? '') === $t ? 'selected' : '' ?>><?= $t ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium small">Contacto</label>
                    <input type="text" name="contacto" class="form-control form-control-sm"
                           placeholder="Nombre del contacto" value="<?= htmlspecialchars($_POST['contacto'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium small">Teléfono</label>
                    <input type="text" name="telefono" class="form-control form-control-sm"
                           placeholder="55-1234-5678" value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-medium small">Email</label>
                    <input type="email" name="email" class="form-control form-control-sm"
                           placeholder="contacto@empresa.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <button type="submit" name="agregar" class="btn btn-primary w-100 btn-sm">
                    <i class="fa-solid fa-plus me-1"></i> Registrar Socio
                </button>
            </form>
        </div>
    </div>

    <!-- Tabla -->
    <div class="col-12 col-md-8">
        <div class="card panel-card">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-semibold">Socios Registrados</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Nombre</th>
                                <th>Tipo</th>
                                <th>Contacto</th>
                                <th>Teléfono</th>
                                <th class="text-end pe-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!$proveedores || mysqli_num_rows($proveedores) === 0): ?>
                            <tr><td colspan="5" class="text-center text-muted py-4">Sin proveedores registrados</td></tr>
                        <?php else: ?>
                            <?php while ($p = mysqli_fetch_assoc($proveedores)): ?>
                            <tr>
                                <td class="ps-3 fw-semibold small"><?= htmlspecialchars($p['nombre_proveedor']) ?></td>
                                <td>
                                    <span class="badge bg-light text-dark border small">
                                        <?= htmlspecialchars($p['tipo_proveedor']) ?>
                                    </span>
                                </td>
                                <td class="small text-muted"><?= htmlspecialchars($p['contacto'] ?? '—') ?></td>
                                <td class="small text-muted"><?= htmlspecialchars($p['telefono'] ?? '—') ?></td>
                                <td class="text-end pe-3">
                                    <a href="editar_proveedor.php?id=<?= $p['id_proveedor'] ?>"
                                       class="btn btn-sm btn-outline-primary me-1" title="Editar">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <a href="eliminar_proveedor.php?id=<?= $p['id_proveedor'] ?>"
                                       class="btn btn-sm btn-outline-danger"
                                       data-confirm="¿Eliminar a «<?= htmlspecialchars($p['nombre_proveedor']) ?>»?"
                                       title="Eliminar">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<?php require_once 'includes/footer.php'; ?>
