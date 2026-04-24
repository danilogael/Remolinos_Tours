<?php
if (session_status() === PHP_SESSION_NONE) session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['ok' => false, 'error' => 'No autenticado']);
    exit;
}

require_once __DIR__ . '/../../Database/conexion.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['ok' => false, 'error' => 'Datos inválidos']);
    exit;
}

// Generar folio único: RT-AÑO-NNNNN
$anio = date('Y');
$res  = $conn->query("SELECT COUNT(*) as total FROM reservas WHERE YEAR(created_at) = $anio");
$row  = $res->fetch_assoc();
$num  = str_pad($row['total'] + 1, 5, '0', STR_PAD_LEFT);
$folio = "RT-{$anio}-{$num}";

$stmt = $conn->prepare("
    INSERT INTO reservas 
    (folio, id_destino, id_usuario, adultos, ninos, fecha_salida, fecha_regreso, 
     solicitudes, precio_por_persona, descuento_ninos, total, metodo_contacto, estado)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pendiente')
");

$fecha_regreso = !empty($data['fecha_regreso']) ? $data['fecha_regreso'] : null;

$stmt->bind_param(
    'siiiiissddds',
    $folio,
    $data['id_destino'],
    $data['id_usuario'],
    $data['adultos'],
    $data['ninos'],
    $data['fecha_salida'],
    $fecha_regreso,
    $data['solicitudes'],
    $data['precio_por_persona'],
    $data['descuento_ninos'],
    $data['total'],
    $data['metodo_contacto']
);

if ($stmt->execute()) {
    echo json_encode(['ok' => true, 'folio' => $folio, 'id' => $conn->insert_id]);
} else {
    echo json_encode(['ok' => false, 'error' => $stmt->error]);
}
