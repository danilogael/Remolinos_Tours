<?php
session_start();
// Asegurar que solo el admin pueda ejecutar este script
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Acceso no autorizado.']);
    exit();
}

require __DIR__ . '/../../../LoginAPI/db.php'; 

// 🛑 IMPORTANTE: Ruta absoluta para la carpeta de imágenes 🛑
$target_dir = __DIR__ . "/../../../viaje/viaje/Viaje-APP/assets/paquetes_img/"; 

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Método no permitido o ID faltante.']);
    exit();
}

$id_to_delete = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id_to_delete) {
    echo json_encode(['success' => false, 'message' => 'ID de paquete inválido.']);
    exit();
}

try {
    // 1. Obtener el nombre del archivo de imagen para eliminarlo
    $stmt_fetch = $conexion->prepare("SELECT imagen_url FROM paquetes WHERE id = ?");
    $stmt_fetch->execute([$id_to_delete]);
    $paquete = $stmt_fetch->fetch(PDO::FETCH_ASSOC);

    if (!$paquete) {
        echo json_encode(['success' => false, 'message' => 'Paquete no encontrado en la base de datos.']);
        exit();
    }
    
    // 2. Eliminar el registro de la base de datos
    $stmt_delete = $conexion->prepare("DELETE FROM paquetes WHERE id = ?");
    $stmt_delete->execute([$id_to_delete]);

    if ($stmt_delete->rowCount() > 0) {
        // 3. Eliminar la imagen física del servidor
        if (!empty($paquete['imagen_url'])) {
            $file_path = $target_dir . $paquete['imagen_url'];
            if (file_exists($file_path) && is_file($file_path)) {
                @unlink($file_path); // @ suprime errores si no se puede eliminar
            }
        }

        echo json_encode(['success' => true, 'message' => 'Paquete eliminado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el paquete o ya no existe.']);
    }

} catch (PDOException $e) {
    error_log("Error al eliminar paquete: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()]);
}
?>