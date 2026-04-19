<?php
session_start(); // Siempre al inicio
header('Content-Type: application/json'); // JSON al inicio
require $_SERVER['DOCUMENT_ROOT'].'/viaje/viaje/LoginAPI/db.php';

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(["success" => false, "message" => "Método no permitido"]);
  exit;
}

// Recibir datos
$correo = $_POST['correo'] ?? '';
$pwd = $_POST['contraseña'] ?? '';

// Buscar usuario
$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE correo = ?");
$stmt->execute([$correo]);
$u = $stmt->fetch(PDO::FETCH_ASSOC);

// Validar contraseña
if (!$u || !password_verify($pwd, $u['contraseña'])) {
  echo json_encode(["success" => false, "message" => "Correo o contraseña incorrectos"]);
  exit;
}

// Crear sesión (¡CORRECTO!)
$_SESSION['user_id'] = $u['id'];
$_SESSION['nombre'] = $u['nombre'];
$_SESSION['rol'] = $u['rol'];

// Devolver JSON con datos del usuario (¡CORRECTO!)
echo json_encode([
  "success" => true,
  "message" => "Login correcto",
  "user" => [
    "id" => $u['id'],
    "nombre" => $u['nombre'], 
    "apellido_paterno" => $u['apellido_paterno'],
    "apellido_materno" => $u['apellido_materno'],
    "correo" => $u['correo'],
    "rol" => $u['rol'] // Este es el dato clave para la redirección
  ]
]);
exit; // Detiene el script para enviar la respuesta JSON