<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "No has iniciado sesión"]);
    exit;
}

require $_SERVER['DOCUMENT_ROOT'].'/viaje/viaje/LoginAPI/db.php';

$user_id = $_SESSION['user_id'];

$currentPassword = $_POST["current_password"] ?? "";
$newPassword = $_POST["new_password"] ?? "";

if (empty($currentPassword) || empty($newPassword)) {
    echo json_encode(["success" => false, "message" => "Completa todos los campos"]);
    exit;
}

try {
    // Obtener contraseña actual
    $stmt = $conexion->prepare("SELECT contraseña FROM usuarios WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(["success" => false, "message" => "Usuario no encontrado"]);
        exit;
    }

    // Verificar contraseña actual
    if (!password_verify($currentPassword, $user["contraseña"])) {
        echo json_encode(["success" => false, "message" => "La contraseña actual es incorrecta"]);
        exit;
    }

    // Encriptar nueva contraseña
    $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

    // Guardar nueva
    $update = $conexion->prepare("UPDATE usuarios SET contraseña = ? WHERE id = ?");
    $update->execute([$newPasswordHash, $user_id]);

    echo json_encode(["success" => true, "message" => "Tu contraseña fue actualizada correctamente"]);

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>
