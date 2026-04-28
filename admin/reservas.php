<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../Login_APP/login.php"); exit();
}
include('../Database/conexion.php');

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// Filtro por estado (opcional)
$filtroEstado = $_GET['estado'] ?? '';
$whereClause  = '';
if (in_array($filtroEstado, ['Pendiente','Confirmada','Cancelada'])) {
    $fe = mysqli_real_escape_string($conexion, $filtroEstado);
    $whereClause = "WHERE r.estado='$fe'";
}

$resultado = mysqli_query($conexion,
    "SELECT r.*, d.nombre AS nombre_destino
     FROM reservas r
     LEFT JOIN destinos d ON r.id_destino = d.id
     $whereClause
     ORDER BY r.fecha_reserva DESC"
);

$tituloPagina = 'Control de Reservas';
$paginaActual = 'reservas';
require_once 'includes/header.php';
?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0">Reservas</h4>
        <p class="text-muted small mb-0">
            <?= $resultado ? mysqli_num_rows($resultado) : 0 ?> registros
            <?= $filtroEstado ? "· filtrando por: <strong>$filtroEstado</strong>" : '' ?>
        </p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <?php foreach (['','Pendiente','Confirmada','Cancelada'] as $est): ?>
        <a href="reservas.php<?= $est ? '?estado='.$est : '' ?>"
           class="btn btn-sm <?= $filtroEstado === $est ? 'btn-primary' : 'btn-outline-secondary' ?>">
            <?= $est ?: 'Todas' ?>
        </a>
        <?php endforeach; ?>
        <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">
            <i class="fa-solid fa-print me-1"></i>Imprimir
        </button>
    </div>
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
                        <th class="ps-3">Folio / Fecha</th>
                        <th>Cliente</th>
                        <th>Destino</th>
                        <th>Pasajeros</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th class="text-end pe-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!$resultado || mysqli_num_rows($resultado) === 0): ?>
                    <tr><td colspan="7" class="text-center text-muted py-5">Sin reservas registradas</td></tr>
                <?php else: ?>
                    <?php while ($r = mysqli_fetch_assoc($resultado)):
                        $e = strtolower($r['estado'] ?? '');
                        $badgeCls = $e === 'confirmada' ? 'badge-confirmada' : ($e === 'cancelada' ? 'badge-cancelada' : 'badge-pendiente');
                    ?>
                    <tr>
                        <td class="ps-3">
                            <div class="fw-medium small"><?= htmlspecialchars($r['folio'] ?? '#'.$r['id']) ?></div>
                            <small class="text-muted"><?= $r['fecha_reserva'] ? date('d/m/Y H:i', strtotime($r['fecha_reserva'])) : '—' ?></small>
                        </td>
                        <td>
                            <div class="fw-semibold small"><?= htmlspecialchars($r['nombre_cliente'] ?? 'Cliente General') ?></div>
                            <?php if (!empty($r['telefono_cliente'])): ?>
                            <small class="text-muted"><?= htmlspecialchars($r['telefono_cliente']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td class="small"><?= htmlspecialchars($r['nombre_destino'] ?? '—') ?></td>
                        <td>
                            <span class="badge bg-light text-dark border small">
                                <i class="fa-solid fa-user me-1"></i><?= (int)($r['num_adultos'] ?? 0) ?>
                                &nbsp;<i class="fa-solid fa-child ms-1 me-1"></i><?= (int)($r['num_ninos'] ?? 0) ?>
                            </span>
                        </td>
                        <td class="fw-bold text-success small">$<?= number_format((float)($r['total_pago'] ?? 0), 2) ?></td>
                        <td>
                            <span class="badge <?= $badgeCls ?> px-2 py-1">
                                <?= htmlspecialchars($r['estado'] ?? 'Pendiente') ?>
                            </span>
                        </td>
                        <td class="text-end pe-3">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border shadow-sm" type="button" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                    <li>
                                        <a class="dropdown-item text-success"
                                           href="cambiar_estado.php?id=<?= $r['id'] ?>&estado=Confirmada">
                                            <i class="fa-solid fa-check me-2"></i>Confirmar
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-warning"
                                           href="cambiar_estado.php?id=<?= $r['id'] ?>&estado=Pendiente">
                                            <i class="fa-solid fa-clock me-2"></i>Pendiente
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger"
                                           href="cambiar_estado.php?id=<?= $r['id'] ?>&estado=Cancelada">
                                            <i class="fa-solid fa-xmark me-2"></i>Cancelar
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger"
                                           href="eliminar_reserva.php?id=<?= $r['id'] ?>"
                                           data-confirm="¿Eliminar la reserva «<?= htmlspecialchars($r['folio'] ?? '#'.$r['id']) ?>»? Esta acción no se puede deshacer.">
                                            <i class="fa-solid fa-trash me-2"></i>Eliminar
                                        </a>
                                    </li>
                                </ul>
                            </div>
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
