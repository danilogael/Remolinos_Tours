<?php
// componentes/Reserva/guardar_reserva.php
if (session_status() === PHP_SESSION_NONE) session_start();

header('Content-Type: application/json');

// ── Seguridad: sesión requerida ───────────────────────────
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['ok' => false, 'error' => 'No autenticado. Inicia sesión primero.']);
    exit;
}

// ── Conexión con mysqli (como usa todo el proyecto) ───────
require_once __DIR__ . '/../../Database/conexion.php';

// ── Leer JSON del body ────────────────────────────────────
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !is_array($data)) {
    echo json_encode(['ok' => false, 'error' => 'Datos inválidos o vacíos.']);
    exit;
}

// ── Validar campos obligatorios ───────────────────────────
$requeridos = ['id_destino', 'adultos', 'fecha_salida', 'total'];
foreach ($requeridos as $campo) {
    if (empty($data[$campo])) {
        echo json_encode(['ok' => false, 'error' => "Campo requerido faltante: $campo"]);
        exit;
    }
}

// ── Verificar que el destino exista y esté activo ─────────
$id_destino = (int)$data['id_destino'];
$destino    = mysqli_fetch_assoc(
    mysqli_query($conexion, "SELECT id, nombre, permite_ninos, min_adultos, max_adultos, max_ninos, cupo_total, tipo_cupo FROM destinos WHERE id=$id_destino AND estado='Activo' LIMIT 1")
);
if (!$destino) {
    echo json_encode(['ok' => false, 'error' => 'El destino seleccionado no existe o no está disponible.']);
    exit;
}

// ── Generar folio único: RT-AÑO-NNNNN ────────────────────
$anio  = date('Y');
$count = mysqli_fetch_assoc(
    mysqli_query($conexion, "SELECT COUNT(*) AS total FROM reservas WHERE YEAR(created_at) = $anio")
);
$num   = str_pad(((int)$count['total']) + 1, 5, '0', STR_PAD_LEFT);
$folio = "RT-{$anio}-{$num}";

// ── Sanitizar datos ───────────────────────────────────────
$id_usuario        = (int)$_SESSION['id_usuario'];
$adultos           = max(1, (int)($data['adultos'] ?? 1));
$ninos             = max(0, (int)($data['ninos'] ?? 0));

$min_adultos = (int)($destino['min_adultos'] ?? 1);
$max_adultos = (int)($destino['max_adultos'] ?? 10);
$max_ninos   = (int)($destino['max_ninos'] ?? 6);
$cupo_total  = (int)($destino['cupo_total'] ?? 20);
$permite_ninos = (int)($destino['permite_ninos'] ?? 1) === 1;
$tipo_cupo = $destino['tipo_cupo'] ?? 'flexible';

if ($adultos < $min_adultos || $adultos > $max_adultos) {
    echo json_encode(['ok' => false, 'error' => "Este paquete permite de $min_adultos a $max_adultos adultos."]);
    exit;
}
if (!$permite_ninos && $ninos > 0) {
    echo json_encode(['ok' => false, 'error' => 'Este paquete no permite ninos.']);
    exit;
}
if ($ninos > $max_ninos) {
    echo json_encode(['ok' => false, 'error' => "Este paquete permite maximo $max_ninos ninos."]);
    exit;
}
if (($adultos + $ninos) > $cupo_total) {
    echo json_encode(['ok' => false, 'error' => "Este paquete tiene cupo maximo de $cupo_total viajeros."]);
    exit;
}
if ($tipo_cupo === 'fijo' && ($adultos + $ninos) !== $cupo_total) {
    echo json_encode(['ok' => false, 'error' => "Este paquete requiere exactamente $cupo_total viajeros."]);
    exit;
}

$fecha_salida      = mysqli_real_escape_string($conexion, $data['fecha_salida'] ?? '');
$fecha_regreso     = !empty($data['fecha_regreso'])
                       ? "'" . mysqli_real_escape_string($conexion, $data['fecha_regreso']) . "'"
                       : 'NULL';
$solicitudes       = mysqli_real_escape_string($conexion, $data['solicitudes'] ?? '');
$precio_persona    = (float)($data['precio_por_persona'] ?? 0);
$descuento_ninos   = (float)($data['descuento_ninos'] ?? 0);
$total_pago        = (float)($data['total'] ?? 0);
$metodo_contacto   = mysqli_real_escape_string($conexion, $data['metodo_contacto'] ?? 'WhatsApp');
$nombre_cliente    = mysqli_real_escape_string($conexion, $data['nombre_cliente'] ?? $_SESSION['nombre'] ?? '');
$telefono_cliente  = mysqli_real_escape_string($conexion, $data['telefono_cliente'] ?? '');
$folio_esc         = mysqli_real_escape_string($conexion, $folio);

// ── INSERT con columnas reales de tu tabla ────────────────
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
    $nuevo_id = mysqli_insert_id($conexion);
    echo json_encode([
        'ok'     => true,
        'folio'  => $folio,
        'id'     => $nuevo_id,
        'destino'=> $destino['nombre']
    ]);
} else {
    echo json_encode([
        'ok'    => false,
        'error' => 'Error al guardar en la base de datos: ' . mysqli_error($conexion)
    ]);
}
