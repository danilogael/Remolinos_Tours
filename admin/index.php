<?php
// admin/index.php — Dashboard principal
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../Login_APP/login.php?error=acceso_restringido");
    exit();
}

include('../Database/conexion.php');

function queryCount($conn, $sql) {
    $r = mysqli_fetch_assoc(mysqli_query($conn, $sql));
    return $r['total'] ?? 0;
}

$stats = [
    'usuarios'    => queryCount($conexion, "SELECT COUNT(*) AS total FROM usuarios"),
    'destinos'    => queryCount($conexion, "SELECT COUNT(*) AS total FROM destinos"),
    'reservas'    => queryCount($conexion, "SELECT COUNT(*) AS total FROM reservas"),
    'proveedores' => queryCount($conexion, "SELECT COUNT(*) AS total FROM proveedores"),
];

$reservasHoy = queryCount($conexion,
    "SELECT COUNT(*) AS total FROM reservas WHERE DATE(fecha_reserva) = CURDATE()"
);

$ingresoTotal = mysqli_fetch_assoc(
    mysqli_query($conexion, "SELECT COALESCE(SUM(total_pago),0) AS total FROM reservas WHERE estado='Confirmada'")
)['total'];

$sqlRes = "SELECT r.id, r.folio, r.nombre_cliente, r.total_pago, r.estado,
                  r.fecha_reserva, d.nombre AS nombre_destino
           FROM reservas r
           LEFT JOIN destinos d ON r.id_destino = d.id
           ORDER BY r.fecha_reserva DESC
           LIMIT 6";
$ultimasReservas = mysqli_query($conexion, $sqlRes);

$tituloPagina = 'Dashboard';
$paginaActual = 'dashboard';
require_once 'includes/header.php';
?>

<div class="row g-4 mb-4">
<?php
$cards = [
    ['label'=>'Total Usuarios',    'valor'=>$stats['usuarios'],    'sub'=>'Registrados', 'icono'=>'fa-users',         'bg'=>'#ede9fe','color'=>'#6366f1','url'=>'usuarios.php'],
    ['label'=>'Destinos',          'valor'=>$stats['destinos'],    'sub'=>'Paquetes activos', 'icono'=>'fa-earth-americas', 'bg'=>'#dbeafe','color'=>'#3b82f6','url'=>'destinos.php'],
    ['label'=>'Reservas',          'valor'=>$stats['reservas'],    'sub'=>$reservasHoy.' nuevas hoy', 'icono'=>'fa-clipboard-list', 'bg'=>'#d1fae5','color'=>'#10b981','url'=>'reservas.php'],
    ['label'=>'Socios/Aerolíneas', 'valor'=>$stats['proveedores'], 'sub'=>'Socios activos', 'icono'=>'fa-plane', 'bg'=>'#fef3c7','color'=>'#f59e0b','url'=>'proveedores.php'],
];
foreach ($cards as $c): ?>
<div class="col-12 col-sm-6 col-xl-3">
    <a href="<?= $c['url'] ?>" class="text-decoration-none">
        <div class="card stat-card p-4">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <p class="text-muted small mb-1"><?= $c['label'] ?></p>
                    <h2 class="fw-bold mb-1"><?= number_format($c['valor']) ?></h2>
                    <small class="text-muted"><?= $c['sub'] ?></small>
                </div>
                <div class="stat-icon" style="background:<?= $c['bg'] ?>;color:<?= $c['color'] ?>;">
                    <i class="fa-solid <?= $c['icono'] ?>"></i>
                </div>
            </div>
        </div>
    </a>
</div>
<?php endforeach; ?>
</div>

<div class="row g-4">
    <div class="col-12 col-md-4">
        <div class="card panel-card h-100 p-4">
            <p class="text-muted small mb-1">Ingresos Confirmados</p>
            <h3 class="fw-bold text-success mb-0">$<?= number_format($ingresoTotal, 2) ?></h3>
            <small class="text-muted">De reservas confirmadas</small>
            <hr>
            <div class="row text-center g-2">
                <?php
                $estados = [
                    ['q'=>"SELECT COUNT(*) AS t FROM reservas WHERE estado='Pendiente'",  'label'=>'Pendientes', 'cls'=>'text-warning'],
                    ['q'=>"SELECT COUNT(*) AS t FROM reservas WHERE estado='Confirmada'", 'label'=>'Confirmadas','cls'=>'text-success'],
                    ['q'=>"SELECT COUNT(*) AS t FROM reservas WHERE estado='Cancelada'",  'label'=>'Canceladas', 'cls'=>'text-danger'],
                ];
                foreach ($estados as $e):
                    $n = mysqli_fetch_assoc(mysqli_query($conexion, $e['q']))['t'] ?? 0;
                ?>
                <div class="col-4">
                    <div class="fw-bold <?= $e['cls'] ?>"><?= $n ?></div>
                    <small class="text-muted" style="font-size:.7rem;"><?= $e['label'] ?></small>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-8">
        <div class="card panel-card">
            <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
                <h6 class="mb-0 fw-semibold">Últimas Reservas</h6>
                <a href="reservas.php" class="btn btn-sm btn-outline-primary">Ver todas</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Folio</th>
                                <th>Cliente</th>
                                <th>Destino</th>
                                <th>Total</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!$ultimasReservas || mysqli_num_rows($ultimasReservas) === 0): ?>
                            <tr><td colspan="5" class="text-center text-muted py-4">Sin reservas aún</td></tr>
                        <?php else: ?>
                            <?php while ($r = mysqli_fetch_assoc($ultimasReservas)):
                                $e = strtolower($r['estado'] ?? '');
                                $badgeCls = $e === 'confirmada' ? 'badge-confirmada' : ($e === 'cancelada' ? 'badge-cancelada' : 'badge-pendiente');
                            ?>
                            <tr>
                                <td class="ps-3 text-muted small"><?= htmlspecialchars($r['folio'] ?? '#'.$r['id']) ?></td>
                                <td class="fw-medium small"><?= htmlspecialchars($r['nombre_cliente'] ?? '—') ?></td>
                                <td class="small"><?= htmlspecialchars($r['nombre_destino'] ?? '—') ?></td>
                                <td class="small fw-semibold text-success">$<?= number_format((float)($r['total_pago'] ?? 0), 2) ?></td>
                                <td><span class="badge <?= $badgeCls ?> px-2 py-1"><?= htmlspecialchars($r['estado'] ?? 'Pendiente') ?></span></td>
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
