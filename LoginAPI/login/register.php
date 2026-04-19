<?php
require __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$ap = trim($_POST['apellido_paterno'] ?? '');
$am = trim($_POST['apellido_materno'] ?? '');
$correo = filter_var($_POST['correo'] ?? '', FILTER_VALIDATE_EMAIL);
$telefono = trim($_POST['telefono'] ?? '');
$pwd = $_POST['contraseña'] ?? '';
$pwd2 = $_POST['confirmar_contraseña'] ?? '';

if (!$nombre || !$ap || !$correo || !$telefono || !$pwd) {
    echo json_encode(["success" => false, "message" => "Faltan campos obligatorios"]);
    exit;
}

if ($pwd !== $pwd2) {
    echo json_encode(["success" => false, "message" => "Las contraseñas no coinciden"]);
    exit;
}

if (strlen($pwd) < 6) {
    echo json_encode(["success" => false, "message" => "La contraseña debe tener al menos 6 caracteres"]);
    exit;
}

$hash = password_hash($pwd, PASSWORD_DEFAULT);

// verificar correo repetido
$stmt = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ?");
$stmt->execute([$correo]);
if ($stmt->fetch()) {
    echo json_encode(["success" => false, "message" => "El correo ya está registrado"]);
    exit;
}
$stmt = $conexion->prepare("SELECT id FROM usuarios WHERE telefono = ?");
$stmt->execute([$telefono]);
if ($stmt->fetch()) {
    echo json_encode(["success" => false, "message" => "El telefono ya está registrado"]);
    exit;
}

// insertar usuario
$verify_token = bin2hex(random_bytes(16));

$ins = $conexion->prepare("
    INSERT INTO usuarios (nombre, apellido_paterno, apellido_materno, correo, telefono, contraseña, rol, verify_token)
    VALUES (?, ?, ?, ?, ?, ?, 'cliente', ?)
");
$ins->execute([$nombre, $ap, $am, $correo, $telefono, $hash, $verify_token]);

echo json_encode(["success" => true, "message" => "Registro exitoso"]);
exit;
