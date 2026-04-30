<?php
// componentes/Reserva/guardar_mi_viaje.php
if (session_status() === PHP_SESSION_NONE) session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['ok' => false, 'error' => 'Sesión requerida.']);
    exit;
}

require_once __DIR__ . '/../../Database/conexion.php';

$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !is_array($data)) {
    echo json_encode(['ok' => false, 'error' => 'Datos inválidos.']);
    exit;
}

// Validar campos obligatorios
if (empty($data['id_destino']) || empty($data['fecha_salida']) || empty($data['total'])) {
    echo json_encode(['ok' => false, 'error' => 'Faltan campos obligatorios.']);
    exit;
}

// Verificar destino activo
$id_destino = (int)$data['id_destino'];
$destino = mysqli_fetch_assoc(
    mysqli_query($conexion, "SELECT id, nombre FROM destinos WHERE id=$id_destino AND estado='Activo' LIMIT 1")
);
if (!$destino) {
    echo json_encode(['ok' => false, 'error' => 'Destino no disponible.']);
    exit;
}

// Generar folio con prefijo MV (Mi Viaje) para diferenciarlo
$anio  = date('Y');
$count = mysqli_fetch_assoc(
    mysqli_query($conexion, "SELECT COUNT(*) AS total FROM reservas WHERE YEAR(created_at) = $anio")
)['total'] ?? 0;
$num   = str_pad((int)$count + 1, 5, '0', STR_PAD_LEFT);
$folio = "MV-{$anio}-{$num}";

// Sanitizar
$id_usuario       = (int)$_SESSION['id_usuario'];
$adultos          = max(1, (int)($data['adultos']  ?? 1));
$ninos            = max(0, (int)($data['ninos']    ?? 0));
$fecha_salida     = mysqli_real_escape_string($conexion, $data['fecha_salida']);
$fecha_regreso    = !empty($data['fecha_regreso'])
                      ? "'" . mysqli_real_escape_string($conexion, $data['fecha_regreso']) . "'"
                      : 'NULL';
$solicitudes      = mysqli_real_escape_string($conexion, $data['solicitudes']      ?? '');
$precio_persona   = (float)($data['precio_por_persona'] ?? 0);
$descuento_ninos  = (float)($data['descuento_ninos']    ?? 0);
$total_pago       = (float)($data['total']              ?? 0);
$metodo_contacto  = mysqli_real_escape_string($conexion, $data['metodo_contacto']  ?? 'WhatsApp');
$nombre_cliente   = mysqli_real_escape_string($conexion, $data['nombre_cliente']   ?? $_SESSION['nombre'] ?? '');
$telefono_cliente = mysqli_real_escape_string($conexion, $data['telefono_cliente'] ?? '');
$folio_esc        = mysqli_real_escape_string($conexion, $folio);

$sql = "INSERT INTO reservas
            (folio, id_destino, id_usuario, nombre_cliente, telefono_cliente,
             adultos, ninos, fecha_salida, fecha_regreso,
             solicitudes, precio_por_persona, descuento_ninos, total_pago,
             metodo_contacto, estado)
        VALUES
            ('$folio_esc', $id_destino, $id_usuario, '$nombre_cliente', '$telefono_cliente',
             $adultos, $ninos, '$fecha_salida', $fecha_regreso,
             '$solicitudes', $precio_persona, $descuento_ninos, $total_pago,
             '$metodo_contacto', 'Pendiente')";

if (mysqli_query($conexion, $sql)) {
    echo json_encode([
        'ok'     => true,
        'folio'  => $folio,
        'id'     => mysqli_insert_id($conexion),
        'destino'=> $destino['nombre']
    ]);
} else {
    echo json_encode([
        'ok'    => false,
        'error' => 'Error al guardar: ' . mysqli_error($conexion)
    ]);
}