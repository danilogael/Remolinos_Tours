<?php
header('Content-Type: application/json; charset=utf-8');
require $_SERVER['DOCUMENT_ROOT'].'/viaje/viaje/LoginAPI/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'No autenticado']);
    exit;
}

$id = intval($_SESSION['user_id']);
$campo = $_POST['campo'] ?? '';
$valor = $_POST['valor'] ?? '';

if (!$campo || $valor === '') {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

/* Solo permitir correo y teléfono */
$map = [
    'correo' => 'correo',
    'telefono' => 'telefono'
];

if (!isset($map[$campo])) {
    echo json_encode(['success' => false, 'message' => 'Campo no permitido']);
    exit;
}

$columna = $map[$campo];

/* Validación correo */
if ($columna === 'correo') {
    if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Correo inválido']);
        exit;
    }

    $q = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ? AND id <> ? LIMIT 1");
    $q->execute([$valor, $id]);

    if ($q->fetch()) {
        echo json_encode(['success' => false, 'message' => 'El correo ya está en uso']);
        exit;
    }
}

/* Validación teléfono */
if ($columna === 'telefono') {
    if (!preg_match('/^[0-9]{10}$/', $valor)) {
        echo json_encode(['success' => false, 'message' => 'Teléfono inválido (10 dígitos)']);
        exit;
    }

    // <-- INICIO DEL CÓDIGO AÑADIDO PARA VALIDAR EL TELÉFONO -->
    $q = $conexion->prepare("SELECT id FROM usuarios WHERE telefono = ? AND id <> ? LIMIT 1");
    $q->execute([$valor, $id]);

    if ($q->fetch()) {
        echo json_encode(['success' => false, 'message' => 'El teléfono ya está en uso por otro usuario']);
        exit;
    }
    // <-- FIN DEL CÓDIGO AÑADIDO -->
}

try {
    $stmt = $conexion->prepare("UPDATE usuarios SET `$columna` = ? WHERE id = ?");
    $stmt->execute([$valor, $id]);

    echo json_encode(['success' => true, 'message' => 'Actualizado correctamente']);
} catch (Exception $e) {
    // Manejo de excepciones más específico si usas la restricción UNIQUE en BD
    echo json_encode(['success' => false, 'message' => 'Error al actualizar: ' . $e->getMessage()]);
}
