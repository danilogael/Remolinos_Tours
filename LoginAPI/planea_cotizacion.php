<?php
session_start();
header('Content-Type: application/json');
require $_SERVER['DOCUMENT_ROOT'].'/viaje/viaje/LoginAPI/db.php';// Ajusta la ruta a tu db.php

// Verificar sesión
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'mensaje' => 'Debes iniciar sesión']);
    exit;
}

// Leer el JSON enviado por fetch
$input = json_decode(file_get_contents('php://input'), true);

// Sanitizar datos básicos
$usuario_id = $_SESSION['user_id'];
$destino = $input['destino'] ?? '';
$fecha_salida = $input['fecha_salida'] ?? '';
$fecha_regreso = $input['fecha_regreso'] ?? '';
$num_adultos = intval($input['num_adultos'] ?? 1);
$num_ninos = intval($input['num_ninos'] ?? 0);
$servicios_solicitados = isset($input['servicios']) ? implode(',', $input['servicios']) : '';
$tipo_transporte = $input['tipo_transporte'] ?? null;
$clase_vuelo = $input['clase_vuelo'] ?? null;
$tipo_alojamiento = $input['tipo_alojamiento'] ?? null;
$nombre_hotel = $input['nombre_hotel'] ?? null;
$coche_personas = intval($input['coche_personas'] ?? 0);
$coche_modelo = $input['coche_modelo'] ?? null;
$presupuesto_total = $input['presupuesto_total'] ?? '';
$comentarios_especiales = $input['comentarios_especiales'] ?? '';
$fecha_solicitud = date('Y-m-d H:i:s');
$estado = 'pendiente';

try {
    $stmt = $conexion->prepare("INSERT INTO cotizaciones_viaje (
        usuario_id, destino, fecha_salida, fecha_regreso, num_adultos, num_ninos,
        servicios_solicitados, tipo_transporte, clase_vuelo, tipo_alojamiento,
        nombre_hotel, coche_personas, coche_modelo, presupuesto_total,
        comentarios_especiales, fecha_solicitud, estado
    ) VALUES (
        :usuario_id, :destino, :fecha_salida, :fecha_regreso, :num_adultos, :num_ninos,
        :servicios_solicitados, :tipo_transporte, :clase_vuelo, :tipo_alojamiento,
        :nombre_hotel, :coche_personas, :coche_modelo, :presupuesto_total,
        :comentarios_especiales, :fecha_solicitud, :estado
    )");

    $stmt->execute([
        ':usuario_id' => $usuario_id,
        ':destino' => $destino,
        ':fecha_salida' => $fecha_salida,
        ':fecha_regreso' => $fecha_regreso,
        ':num_adultos' => $num_adultos,
        ':num_ninos' => $num_ninos,
        ':servicios_solicitados' => $servicios_solicitados,
        ':tipo_transporte' => $tipo_transporte,
        ':clase_vuelo' => $clase_vuelo,
        ':tipo_alojamiento' => $tipo_alojamiento,
        ':nombre_hotel' => $nombre_hotel,
        ':coche_personas' => $coche_personas,
        ':coche_modelo' => $coche_modelo,
        ':presupuesto_total' => $presupuesto_total,
        ':comentarios_especiales' => $comentarios_especiales,
        ':fecha_solicitud' => $fecha_solicitud,
        ':estado' => $estado
    ]);

    echo json_encode(['success' => true, 'mensaje' => 'Cotización guardada con éxito']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'mensaje' => 'Error al guardar: '.$e->getMessage()]);
}
?>