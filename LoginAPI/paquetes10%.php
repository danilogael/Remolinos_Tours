<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'].'/viaje/viaje/LoginAPI/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['show' => false]);
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conexion->prepare("SELECT primer_paquete FROM usuarios WHERE id = ?");
$stmt->execute([$user_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row && $row['primer_paquete'] == 1) {
    $update = $conexion->prepare("UPDATE usuarios SET primer_paquete = 0 WHERE id = ?");
    $update->execute([$user_id]);

    echo json_encode(['show' => true]);
} else {
    echo json_encode(['show' => false]);
}
exit;
