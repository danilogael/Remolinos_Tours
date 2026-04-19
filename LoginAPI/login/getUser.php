<?php
header('Content-Type: application/json; charset=utf-8');
require $_SERVER['DOCUMENT_ROOT'].'/viaje/viaje/LoginAPI/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'No autenticado']);
    exit;
}

$id = intval($_SESSION['user_id']);

try {
    $stmt = $conexion->prepare("SELECT id, nombre, apellido_paterno, apellido_materno, correo, telefono, rol 
                                FROM usuarios WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
        exit;
    }

    echo json_encode([
        'success' => true,
        'user' => $row
    ]);
    exit;

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error interno']);
    exit;
}
