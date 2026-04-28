<?php
// admin/usuarios.php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../Login_APP/login.php?error=acceso_restringido");
    exit();
}
include('../Database/conexion.php');

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$resultado = mysqli_query($conexion,
    "SELECT id, nombre_completo, email, telefono, rol, fecha_registro, activo
     FROM usuarios ORDER BY fecha_registro DESC"
);

$tituloPagina = 'Gestión de Usuarios';
$paginaActual = 'usuarios';
require_once 'includes/header.php';
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0">Usuarios</h4>
        <p class="text-muted small mb-0">
            <?= mysqli_num_rows($resultado) ?> usuarios en el sistema
        </p>
    </div>
    <a href="nuevo_usuario.php" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="fa-solid fa-user-plus"></i> Nuevo usuario
    </a>
</div>

<?php if ($flash): ?>
<div class="alert alert-<?= $flash['tipo'] ?> alert-dismissible show d-flex align-items-center gap-2" data-auto-dismiss role="alert">
    <i class="fa-solid fa-<?= $flash['tipo'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
    <?= htmlspecialchars($flash['msg']) ?>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="card panel-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Usuario</th>
                        <th>Contacto</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Registro</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (mysqli_num_rows($resultado) === 0): ?>
                    <tr><td colspan="6" class="text-center text-muted py-5">Sin usuarios registrados</td></tr>
                <?php else: ?>
                    <?php while ($u = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-2">
                                <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold text-white"
                                     style="width:36px;height:36px;background:#6366f1;flex-shrink:0;font-size:.85rem;">
                                    <?= strtoupper(mb_substr($u['nombre_completo'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="fw-semibold small"><?= htmlspecialchars($u['nombre_completo']) ?></div>
                                    <small class="text-muted">ID #<?= $u['id'] ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small"><i class="fa-solid fa-envelope me-1 text-muted"></i><?= htmlspecialchars($u['email']) ?></div>
                            <?php if ($u['telefono']): ?>
                            <div class="small text-muted"><i class="fa-solid fa-phone me-1"></i><?= htmlspecialchars($u['telefono']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($u['rol'] === 'admin'): ?>
                                <span class="badge px-2 py-1" style="background:#6366f1;">Admin</span>
                            <?php else: ?>
                                <span class="badge bg-secondary px-2 py-1">Cliente</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ((int)$u['activo'] === 1): ?>
                                <span class="small text-success"><i class="fa-solid fa-circle me-1" style="font-size:8px;"></i>Activo</span>
                            <?php else: ?>
                                <span class="small text-danger"><i class="fa-solid fa-circle me-1" style="font-size:8px;"></i>Suspendido</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted small">
                            <?= $u['fecha_registro'] ? date('d/m/Y', strtotime($u['fecha_registro'])) : '—' ?>
                        </td>
                        <td class="text-end pe-4">
                            <a href="editar_usuario.php?id=<?= $u['id'] ?>"
                               class="btn btn-sm btn-outline-primary me-1" title="Editar">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <?php if ((int)$u['id'] !== (int)$_SESSION['id_usuario']): ?>
                            <a href="eliminar_usuario.php?id=<?= $u['id'] ?>"
                               class="btn btn-sm btn-outline-danger"
                               data-confirm="¿Eliminar al usuario «<?= htmlspecialchars($u['nombre_completo']) ?>»? Esta acción no se puede deshacer."
                               title="Eliminar">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
