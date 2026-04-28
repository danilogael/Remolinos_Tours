<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../Login_APP/login.php"); exit();
}
include('../Database/conexion.php');

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: usuarios.php'); exit(); }

$usuario = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT * FROM usuarios WHERE id=$id LIMIT 1"));
if (!$usuario) { header('Location: usuarios.php'); exit(); }

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim(mysqli_real_escape_string($conexion, $_POST['nombre_completo'] ?? ''));
    $email  = trim(mysqli_real_escape_string($conexion, $_POST['email'] ?? ''));
    $tel    = trim(mysqli_real_escape_string($conexion, $_POST['telefono'] ?? ''));
    $rol    = in_array($_POST['rol'] ?? '', ['admin','cliente']) ? $_POST['rol'] : 'cliente';
    $activo = isset($_POST['activo']) ? 1 : 0;
    $pass   = $_POST['password'] ?? '';

    $usuario['nombre_completo'] = $_POST['nombre_completo'];
    $usuario['email']    = $_POST['email'];
    $usuario['telefono'] = $_POST['telefono'];
    $usuario['rol']      = $rol;
    $usuario['activo']   = $activo;

    if ($nombre === '') $errores[] = 'El nombre es obligatorio.';
    if ($email  === '') $errores[] = 'El email es obligatorio.';

    $chk = mysqli_query($conexion, "SELECT id FROM usuarios WHERE email='$email' AND id!=$id LIMIT 1");
    if (mysqli_num_rows($chk) > 0) $errores[] = 'Ese email ya está en uso.';

    if (empty($errores)) {
        if ($pass !== '') {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $sql  = "UPDATE usuarios SET nombre_completo='$nombre', email='$email', telefono='$tel', rol='$rol', activo=$activo, password='$hash' WHERE id=$id";
        } else {
            $sql  = "UPDATE usuarios SET nombre_completo='$nombre', email='$email', telefono='$tel', rol='$rol', activo=$activo WHERE id=$id";
        }
        if (mysqli_query($conexion, $sql)) {
            $_SESSION['flash'] = ['tipo'=>'success','msg'=>'Usuario actualizado correctamente.'];
            header('Location: usuarios.php'); exit();
        }
        $errores[] = 'Error al actualizar: ' . mysqli_error($conexion);
    }
}

$tituloPagina = 'Editar Usuario';
$paginaActual = 'usuarios';
require_once 'includes/header.php';
?>
<div class="mb-4">
    <a href="usuarios.php" class="text-muted small text-decoration-none"><i class="fa-solid fa-arrow-left me-1"></i> Volver</a>
    <h4 class="fw-bold mt-1 mb-0">Editar Usuario #<?= $id ?></h4>
</div>

<?php if ($errores): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errores as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<div class="card panel-card" style="max-width:520px;">
    <div class="card-body p-4">
        <form method="POST" novalidate>
            <div class="mb-3">
                <label class="form-label fw-medium">Nombre completo</label>
                <input type="text" name="nombre_completo" class="form-control" value="<?= htmlspecialchars($usuario['nombre_completo']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-medium">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-medium">Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label class="form-label fw-medium">Nueva contraseña <small class="text-muted">(vacío = no cambiar)</small></label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="row g-3 mb-4">
                <div class="col">
                    <label class="form-label fw-medium">Rol</label>
                    <select name="rol" class="form-select">
                        <option value="cliente" <?= $usuario['rol']==='cliente'?'selected':'' ?>>Cliente</option>
                        <option value="admin"   <?= $usuario['rol']==='admin'?'selected':'' ?>>Admin</option>
                    </select>
                </div>
                <div class="col-auto d-flex align-items-end pb-1">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="activo" id="activo" <?= (int)$usuario['activo'] ? 'checked' : '' ?>>
                        <label class="form-check-label fw-medium" for="activo">Activo</label>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">Actualizar</button>
                <a href="usuarios.php" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
