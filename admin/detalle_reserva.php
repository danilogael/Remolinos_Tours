<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../Login_APP/login.php");
    exit();
}
include('../Database/conexion.php');

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: reservas.php');
    exit();
}

$stmt = mysqli_prepare($conexion,
    "SELECT r.*,
            d.nombre AS nombre_destino, d.descripcion, d.precio, d.precio_nino,
            d.foto_portada, d.tipo_trayecto, d.cupo_total, d.punto_salida,
            d.maleta_mano_kg, d.maleta_documentada_kg, d.seguro_basico_incluido,
            d.fecha_salida AS fecha_salida_paquete, d.dias, d.noches,
            p.nombre_proveedor, p.tipo_proveedor,
            u.email AS usuario_email, u.telefono AS usuario_telefono
     FROM reservas r
     LEFT JOIN destinos d ON r.id_destino = d.id
     LEFT JOIN proveedores p ON d.id_proveedor = p.id_proveedor
     LEFT JOIN usuarios u ON r.id_usuario = u.id
     WHERE r.id = ?
     LIMIT 1"
);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$reserva = mysqli_fetch_assoc($res);

if (!$reserva) {
    $_SESSION['flash'] = ['tipo'=>'danger','msg'=>'Reserva no encontrada.'];
    header('Location: reservas.php');
    exit();
}

$idDestino = (int)($reserva['id_destino'] ?? 0);

$itinerario = [];
if ($idDestino > 0) {
    $resIt = mysqli_query($conexion,
        "SELECT dia_numero, titulo_actividad, descripcion_actividad
         FROM itinerarios
         WHERE id_destino = $idDestino
         ORDER BY dia_numero ASC"
    );
    if ($resIt) {
        while ($row = mysqli_fetch_assoc($resIt)) $itinerario[] = $row;
    }
}

$extras = [];
if ($idDestino > 0) {
    $resEx = mysqli_query($conexion,
        "SELECT nombre_actividad, precio_extra
         FROM actividades_extra
         WHERE id_destino = $idDestino
         ORDER BY nombre_actividad ASC"
    );
    if ($resEx) {
        while ($row = mysqli_fetch_assoc($resEx)) $extras[] = $row;
    }
}

$estado = $reserva['estado'] ?? 'Pendiente';
$badgeCls = strtolower($estado) === 'confirmada' ? 'badge-confirmada' : (strtolower($estado) === 'cancelada' ? 'badge-cancelada' : 'badge-pendiente');
$tituloPagina = 'Detalle de Reserva';
$paginaActual = 'reservas';
require_once 'includes/header.php';
?>

<style>
    .detail-hero {
        display: grid;
        grid-template-columns: 160px minmax(0, 1fr);
        gap: 1rem;
        align-items: center;
    }
    .detail-hero img {
        width: 160px;
        height: 110px;
        object-fit: cover;
        border-radius: .75rem;
        border: 1px solid #e2e8f0;
    }
    .detail-label { font-size: .72rem; text-transform: uppercase; letter-spacing: .05em; color: #64748b; font-weight: 700; margin-bottom: .2rem; }
    .detail-value { color: #0f172a; font-weight: 600; }
    .detail-muted { color: #64748b; }
    .detail-box { border: 1px solid #e2e8f0; border-radius: .85rem; padding: 1rem; background: #fff; height: 100%; }
    .detail-list { display: grid; gap: .75rem; }
    .detail-list-row { display: flex; justify-content: space-between; gap: 1rem; border-bottom: 1px solid #f1f5f9; padding-bottom: .65rem; }
    .detail-list-row:last-child { border-bottom: 0; padding-bottom: 0; }
    .timeline-item { display: grid; grid-template-columns: 58px 1fr; gap: .85rem; padding: .85rem 0; border-bottom: 1px solid #f1f5f9; }
    .timeline-item:last-child { border-bottom: 0; }
    .timeline-day { width: 48px; height: 48px; border-radius: 50%; display: grid; place-items: center; background: #eef2ff; color: #4f46e5; font-weight: 800; font-size: .75rem; text-align: center; }
    @media print {
        #sidebar, #topbar, .no-print { display: none !important; }
        #content { margin: 0 !important; padding: 0 !important; }
        .page-body { padding: 0 !important; }
        .card { box-shadow: none !important; border: 1px solid #ddd !important; }
    }
    @media (max-width: 640px) {
        .detail-hero { grid-template-columns: 1fr; }
        .detail-hero img { width: 100%; height: 180px; }
    }
</style>

<div class="mb-4 no-print">
    <a href="reservas.php" class="back-link">
        <i class="fa-solid fa-arrow-left"></i> Volver a Reservas
    </a>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mt-1 mb-0">Reserva <?= htmlspecialchars($reserva['folio'] ?? '#'.$reserva['id']) ?></h4>
            <p class="text-muted small mb-0">Consulta completa del cliente, paquete y datos para confirmar la reserva.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                <i class="fa-solid fa-print me-1"></i>Imprimir
            </button>
            <button type="button" class="btn btn-primary" id="btnAdminPdf">
                <i class="fa-solid fa-file-pdf me-1"></i>Descargar PDF
            </button>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-xl-8">
        <div class="card panel-card p-4 mb-4">
            <div class="detail-hero">
                <img src="../assets/imagenes/<?= htmlspecialchars($reserva['foto_portada'] ?: 'default.png') ?>"
                     alt="<?= htmlspecialchars($reserva['nombre_destino'] ?? 'Paquete') ?>"
                     onerror="this.src='../assets/imagenes/default.png'">
                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                        <span class="badge <?= $badgeCls ?>"><?= htmlspecialchars($estado) ?></span>
                        <span class="badge bg-light text-dark border"><?= htmlspecialchars($reserva['tipo_trayecto'] ?? 'Redondo') ?></span>
                    </div>
                    <h3 class="fw-bold mb-2"><?= htmlspecialchars($reserva['nombre_destino'] ?? 'Destino no disponible') ?></h3>
                    <p class="text-muted mb-0"><?= htmlspecialchars($reserva['descripcion'] ?? 'Sin descripcion registrada.') ?></p>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12 col-md-6">
                <div class="detail-box">
                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-user me-2 text-primary"></i>Cliente</h6>
                    <div class="detail-list">
                        <div>
                            <div class="detail-label">Nombre</div>
                            <div class="detail-value"><?= htmlspecialchars($reserva['nombre_cliente'] ?? 'Cliente general') ?></div>
                        </div>
                        <div>
                            <div class="detail-label">Telefono</div>
                            <div class="detail-value"><?= htmlspecialchars($reserva['telefono_cliente'] ?: ($reserva['usuario_telefono'] ?? 'No registrado')) ?></div>
                        </div>
                        <div>
                            <div class="detail-label">Email</div>
                            <div class="detail-value"><?= htmlspecialchars($reserva['usuario_email'] ?? 'No registrado') ?></div>
                        </div>
                        <div>
                            <div class="detail-label">Contacto preferido</div>
                            <div class="detail-value"><?= htmlspecialchars($reserva['metodo_contacto'] ?: 'No especificado') ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="detail-box">
                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-calendar-days me-2 text-primary"></i>Viaje</h6>
                    <div class="detail-list">
                        <div class="detail-list-row">
                            <span class="detail-muted">Salida</span>
                            <strong><?= $reserva['fecha_salida'] ? date('d/m/Y', strtotime($reserva['fecha_salida'])) : 'Por confirmar' ?></strong>
                        </div>
                        <div class="detail-list-row">
                            <span class="detail-muted">Regreso</span>
                            <strong><?= $reserva['fecha_regreso'] ? date('d/m/Y', strtotime($reserva['fecha_regreso'])) : 'No especificada' ?></strong>
                        </div>
                        <div class="detail-list-row">
                            <span class="detail-muted">Adultos</span>
                            <strong><?= (int)($reserva['adultos'] ?? 0) ?></strong>
                        </div>
                        <div class="detail-list-row">
                            <span class="detail-muted">Ninos</span>
                            <strong><?= (int)($reserva['ninos'] ?? 0) ?></strong>
                        </div>
                        <div class="detail-list-row">
                            <span class="detail-muted">Solicitudes</span>
                            <strong class="text-end"><?= htmlspecialchars($reserva['solicitudes'] ?: 'Ninguna') ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card panel-card p-4 mb-4">
            <h6 class="fw-bold mb-3"><i class="fa-solid fa-list-check me-2 text-primary"></i>Detalles del paquete</h6>
            <div class="row g-3">
                <div class="col-6 col-md-4">
                    <div class="detail-label">Salida del paquete</div>
                    <div class="detail-value"><?= htmlspecialchars($reserva['punto_salida'] ?: 'Por confirmar') ?></div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="detail-label">Duracion</div>
                    <div class="detail-value"><?= (int)($reserva['dias'] ?? 0) ?> dias / <?= (int)($reserva['noches'] ?? 0) ?> noches</div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="detail-label">Proveedor</div>
                    <div class="detail-value"><?= htmlspecialchars($reserva['nombre_proveedor'] ?: 'Sin proveedor') ?></div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="detail-label">Maleta mano</div>
                    <div class="detail-value"><?= (int)($reserva['maleta_mano_kg'] ?? 10) ?> kg</div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="detail-label">Maleta documentada</div>
                    <div class="detail-value"><?= (int)($reserva['maleta_documentada_kg'] ?? 25) ?> kg</div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="detail-label">Seguro basico</div>
                    <div class="detail-value"><?= !empty($reserva['seguro_basico_incluido']) ? 'Incluido' : 'No incluido' ?></div>
                </div>
            </div>
        </div>

        <div class="card panel-card p-4">
            <h6 class="fw-bold mb-3"><i class="fa-solid fa-route me-2 text-primary"></i>Itinerario del paquete</h6>
            <?php if (empty($itinerario)): ?>
                <p class="text-muted mb-0">No hay itinerario registrado para este paquete.</p>
            <?php else: ?>
                <?php foreach ($itinerario as $item): ?>
                    <div class="timeline-item">
                        <div class="timeline-day">Dia <?= (int)$item['dia_numero'] ?></div>
                        <div>
                            <div class="fw-semibold"><?= htmlspecialchars($item['titulo_actividad']) ?></div>
                            <div class="text-muted small"><?= htmlspecialchars($item['descripcion_actividad'] ?: 'Actividad por confirmar.') ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card panel-card p-4 mb-4">
            <h6 class="fw-bold mb-3"><i class="fa-solid fa-dollar-sign me-2 text-success"></i>Resumen de pago</h6>
            <div class="detail-list">
                <div class="detail-list-row">
                    <span class="detail-muted">Precio adulto</span>
                    <strong>$<?= number_format((float)($reserva['precio_por_persona'] ?? 0), 2) ?></strong>
                </div>
                <div class="detail-list-row">
                    <span class="detail-muted">Descuento ninos</span>
                    <strong>$<?= number_format((float)($reserva['descuento_ninos'] ?? 0), 2) ?></strong>
                </div>
                <div class="detail-list-row">
                    <span class="detail-muted">Total pagado/estimado</span>
                    <strong class="text-success fs-5">$<?= number_format((float)($reserva['total_pago'] ?? 0), 2) ?></strong>
                </div>
            </div>
        </div>

        <div class="card panel-card p-4 mb-4">
            <h6 class="fw-bold mb-3"><i class="fa-solid fa-plus me-2 text-primary"></i>Actividades extra</h6>
            <?php if (empty($extras)): ?>
                <p class="text-muted mb-0">Sin actividades extra registradas.</p>
            <?php else: ?>
                <div class="detail-list">
                    <?php foreach ($extras as $extra): ?>
                        <div class="detail-list-row">
                            <span><?= htmlspecialchars($extra['nombre_actividad']) ?></span>
                            <strong>$<?= number_format((float)$extra['precio_extra'], 2) ?></strong>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="card panel-card p-4 no-print">
            <h6 class="fw-bold mb-3">Acciones rapidas</h6>
            <div class="d-grid gap-2">
                <a href="cambiar_estado.php?id=<?= (int)$reserva['id'] ?>&estado=Confirmada" class="btn btn-outline-success">
                    <i class="fa-solid fa-check me-1"></i>Confirmar reserva
                </a>
                <a href="cambiar_estado.php?id=<?= (int)$reserva['id'] ?>&estado=Pendiente" class="btn btn-outline-warning">
                    <i class="fa-solid fa-clock me-1"></i>Marcar pendiente
                </a>
                <a href="cambiar_estado.php?id=<?= (int)$reserva['id'] ?>&estado=Cancelada" class="btn btn-outline-danger">
                    <i class="fa-solid fa-xmark me-1"></i>Cancelar reserva
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
const reservaAdmin = <?= json_encode([
    'folio' => $reserva['folio'] ?? ('#'.$reserva['id']),
    'estado' => $estado,
    'cliente' => $reserva['nombre_cliente'] ?? 'Cliente general',
    'telefono' => $reserva['telefono_cliente'] ?: ($reserva['usuario_telefono'] ?? ''),
    'email' => $reserva['usuario_email'] ?? '',
    'destino' => $reserva['nombre_destino'] ?? 'Destino no disponible',
    'salida' => $reserva['fecha_salida'] ?? '',
    'regreso' => $reserva['fecha_regreso'] ?? '',
    'adultos' => (int)($reserva['adultos'] ?? 0),
    'ninos' => (int)($reserva['ninos'] ?? 0),
    'contacto' => $reserva['metodo_contacto'] ?? '',
    'solicitudes' => $reserva['solicitudes'] ?? '',
    'total' => (float)($reserva['total_pago'] ?? 0),
    'proveedor' => $reserva['nombre_proveedor'] ?? '',
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;

function fechaMx(fecha) {
    if (!fecha) return 'No especificada';
    const partes = fecha.split('-');
    if (partes.length !== 3) return fecha;
    return `${partes[2]}/${partes[1]}/${partes[0]}`;
}

document.getElementById('btnAdminPdf')?.addEventListener('click', () => {
    if (!window.jspdf) {
        alert('No se pudo cargar la libreria PDF.');
        return;
    }

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({ unit: 'mm', format: 'a4' });
    const azul = [79, 70, 229];
    const oscuro = [15, 23, 42];
    const gris = [100, 116, 139];
    let y = 20;

    const fila = (label, valor) => {
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(9);
        doc.setTextColor(...gris);
        doc.text(label, 20, y);
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(...oscuro);
        doc.text(String(valor || 'No especificado'), 78, y);
        doc.setDrawColor(226, 232, 240);
        doc.line(20, y + 3, 190, y + 3);
        y += 9;
    };

    const seccion = (titulo) => {
        y += 6;
        doc.setFillColor(238, 242, 255);
        doc.roundedRect(15, y - 5, 180, 9, 2, 2, 'F');
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(10);
        doc.setTextColor(...azul);
        doc.text(titulo, 20, y + 1);
        y += 11;
    };

    doc.setFillColor(...azul);
    doc.rect(0, 0, 210, 36, 'F');
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(20);
    doc.setTextColor(255, 255, 255);
    doc.text("Remolino's Tours", 20, 17);
    doc.setFontSize(9);
    doc.text('Detalle administrativo de reserva', 20, 27);
    doc.text(`Folio: ${reservaAdmin.folio}`, 190, 17, { align: 'right' });
    doc.text(`Estado: ${reservaAdmin.estado}`, 190, 26, { align: 'right' });

    y = 48;
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(15);
    doc.setTextColor(...oscuro);
    doc.text('DETALLE DE RESERVA', 105, y, { align: 'center' });
    y += 8;

    seccion('CLIENTE');
    fila('Nombre', reservaAdmin.cliente);
    fila('Telefono', reservaAdmin.telefono);
    fila('Email', reservaAdmin.email);
    fila('Contacto preferido', reservaAdmin.contacto);

    seccion('PAQUETE Y VIAJE');
    fila('Destino', reservaAdmin.destino);
    fila('Proveedor', reservaAdmin.proveedor || 'Sin proveedor');
    fila('Salida', fechaMx(reservaAdmin.salida));
    fila('Regreso', fechaMx(reservaAdmin.regreso));
    fila('Adultos', reservaAdmin.adultos);
    fila('Ninos', reservaAdmin.ninos);
    fila('Solicitudes', reservaAdmin.solicitudes || 'Ninguna');

    seccion('PAGO');
    fila('Total', `$${reservaAdmin.total.toLocaleString('es-MX', { minimumFractionDigits: 2 })} MXN`);

    y += 8;
    doc.setFont('helvetica', 'italic');
    doc.setFontSize(8);
    doc.setTextColor(...gris);
    doc.text('Documento generado desde el panel de administracion.', 105, y, { align: 'center' });

    doc.save(`Detalle_${reservaAdmin.folio}.pdf`);
});
</script>

<?php require_once 'includes/footer.php'; ?>
