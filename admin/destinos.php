<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../Login_APP/login.php"); exit();
}
include('../Database/conexion.php');

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$resultado = mysqli_query($conexion,
    "SELECT d.*, p.nombre_proveedor
     FROM destinos d
     LEFT JOIN proveedores p ON d.id_proveedor = p.id_proveedor
     ORDER BY d.id DESC"
);

$tituloPagina = 'Gestión de Destinos';
$paginaActual = 'destinos';
require_once 'includes/header.php';
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0">Destinos</h4>
        <p class="text-muted small mb-0">Administra los paquetes de viaje</p>
    </div>
    <a href="nuevo_destino.php" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="fa-solid fa-plus"></i> Nuevo paquete
    </a>
</div>

<?php if ($flash): ?>
<div class="alert alert-<?= $flash['tipo'] ?> alert-dismissible show" data-auto-dismiss role="alert">
    <?= htmlspecialchars($flash['msg']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="card panel-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3" style="width:90px;">Imagen</th>
                        <th>Destino</th>
                        <th>Precio Adulto</th>
                        <th>Precio Niño</th>
                        <th>Trayecto</th>
                        <th>Socio/Aerolínea</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!$resultado || mysqli_num_rows($resultado) === 0): ?>
                    <tr><td colspan="8" class="text-center text-muted py-5">
                        <i class="fa-solid fa-box-open fa-2x d-block mb-2"></i>
                        Sin destinos registrados
                    </td></tr>
                <?php else: ?>
                    <?php while ($d = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td class="ps-3">
                            <img src="../assets/imagenes/<?= htmlspecialchars($d['foto_portada'] ?? 'default.png') ?>"
                                 alt="<?= htmlspecialchars($d['nombre']) ?>"
                                 style="width:80px;height:55px;object-fit:cover;border-radius:.5rem;"
                                 onerror="this.src='../assets/imagenes/cancun.png'">
                        </td>
                        <td>
                            <div class="fw-semibold"><?= htmlspecialchars($d['nombre']) ?></div>
                            <small class="text-muted"><?= htmlspecialchars($d['punto_salida'] ?? '—') ?></small>
                        </td>
                        <td>
                            <span class="badge bg-success text-white">$<?= number_format((float)($d['precio'] ?? 0), 2) ?></span>
                        </td>
                        <td class="text-muted small">
                            $<?= number_format((float)($d['precio_nino'] ?? 0), 2) ?>
                        </td>
                        <td class="small">
                            <i class="fa-solid fa-plane-up text-primary me-1"></i>
                            <?= htmlspecialchars($d['tipo_trayecto'] ?? 'Redondo') ?>
                        </td>
                        <td class="small"><?= htmlspecialchars($d['nombre_proveedor'] ?? '—') ?></td>
                        <td>
                            <?php
                            $est = $d['estado'] ?? 'Activo';
                            $estCls = strtolower($est) === 'activo' ? 'badge-confirmada' : 'badge-cancelada';
                            ?>
                            <span class="badge <?= $estCls ?> px-2 py-1"><?= htmlspecialchars($est) ?></span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="editar_destino.php?id=<?= $d['id'] ?>"
                               class="btn btn-sm btn-outline-primary me-1" title="Editar">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <a href="eliminar_destino.php?id=<?= $d['id'] ?>"
                               class="btn btn-sm btn-outline-danger"
                               data-confirm="¿Eliminar el destino «<?= htmlspecialchars($d['nombre']) ?>»? Esta acción no se puede deshacer."
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

<?php require_once 'includes/footer.php'; ?>
