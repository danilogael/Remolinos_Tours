<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../Login_APP/login.php"); exit();
}
include('../Database/conexion.php');

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: proveedores.php'); exit(); }

$proveedor = mysqli_fetch_assoc(mysqli_query($conexion,
    "SELECT * FROM proveedores WHERE id_proveedor=$id LIMIT 1"
));
if (!$proveedor) { header('Location: proveedores.php'); exit(); }

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim(mysqli_real_escape_string($conexion, $_POST['nombre']   ?? ''));
    $tipo     = trim(mysqli_real_escape_string($conexion, $_POST['tipo']     ?? ''));
    $contacto = trim(mysqli_real_escape_string($conexion, $_POST['contacto'] ?? ''));
    $telefono = trim(mysqli_real_escape_string($conexion, $_POST['telefono'] ?? ''));
    $email    = trim(mysqli_real_escape_string($conexion, $_POST['email']    ?? ''));

    $proveedor = array_merge($proveedor,
        ['nombre_proveedor'=>$_POST['nombre'],'tipo_proveedor'=>$_POST['tipo'],
         'contacto'=>$_POST['contacto'],'telefono'=>$_POST['telefono'],'email'=>$_POST['email']]
    );

    if ($nombre === '') $errores[] = 'El nombre es obligatorio.';
    if ($tipo   === '') $errores[] = 'El tipo es obligatorio.';

    if (empty($errores)) {
        $sql = "UPDATE proveedores SET nombre_proveedor='$nombre', tipo_proveedor='$tipo',
                contacto='$contacto', telefono='$telefono', email='$email'
                WHERE id_proveedor=$id";
        if (mysqli_query($conexion, $sql)) {
            $_SESSION['flash'] = ['tipo'=>'success','msg'=>'Proveedor actualizado.'];
            header('Location: proveedores.php'); exit();
        }
        $errores[] = 'Error al actualizar: ' . mysqli_error($conexion);
    }
}

$tituloPagina = 'Editar Proveedor';
$paginaActual = 'proveedores';
require_once 'includes/header.php';
?>
<div class="mb-4">
    <a href="proveedores.php" class="text-muted small text-decoration-none"><i class="fa-solid fa-arrow-left me-1"></i> Volver</a>
    <h4 class="fw-bold mt-1 mb-0">Editar Proveedor</h4>
</div>

<?php if ($errores): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errores as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<div class="card panel-card" style="max-width:520px;">
    <div class="card-body p-4">
        <form method="POST" novalidate>
            <div class="mb-3">
                <label class="form-label fw-medium">Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($proveedor['nombre_proveedor']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-medium">Tipo</label>
                <select name="tipo" class="form-select">
                    <?php foreach(['Aerolínea','Hotel','Crucero','Agencia Local','Transporte','Otro'] as $t): ?>
                    <option value="<?= $t ?>" <?= $proveedor['tipo_proveedor']===$t?'selected':'' ?>><?= $t ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-medium">Contacto</label>
                <input type="text" name="contacto" class="form-control" value="<?= htmlspecialchars($proveedor['contacto'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label class="form-label fw-medium">Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($proveedor['telefono'] ?? '') ?>">
            </div>
            <div class="mb-4">
                <label class="form-label fw-medium">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($proveedor['email'] ?? '') ?>">
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">Actualizar</button>
                <a href="proveedores.php" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
