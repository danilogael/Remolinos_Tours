<?php
session_start();
header('Content-Type: application/json');
require __DIR__ . "/db.php"; // Ajusta la ruta si es necesario

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(["success" => false, "message" => "Método no permitido"]);
        exit;
    }

    $nombre   = $_POST['nombre'] ?? null;
    $destino  = $_POST['destino'] ?? null;
    $dias     = $_POST['dias'] ?? null;
    $calificacion = $_POST['rating'] ?? null;
    $mensaje  = $_POST['mensaje'] ?? null;

    if (!$nombre || !$destino || !$dias || !$calificacion || !$mensaje) {
        echo json_encode(["success" => false, "message" => "Todos los campos son obligatorios"]);
        exit;
    }

    $foto = null;
    if (!empty($_FILES["foto"]["name"])) {
        if ($_FILES["foto"]["size"] > 3000000) {
            echo json_encode(["success" => false, "message" => "La imagen es demasiado grande (máximo 3MB)"]);
            exit;
        }

        $permitidos = ["image/jpeg","image/png","image/jpg"];
        if (!in_array($_FILES["foto"]["type"], $permitidos)) {
            echo json_encode(["success" => false, "message" => "Solo se permiten imágenes JPG y PNG"]);
            exit;
        }

        $carpeta = __DIR__ . "/fotos/";
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $nombreArchivo = time() . "_" . $_FILES["foto"]["name"];
        $foto = "fotos/" . $nombreArchivo;
        move_uploaded_file($_FILES["foto"]["tmp_name"], $carpeta . $nombreArchivo);
    }

    $sql = "INSERT INTO reseñas (nombre, destino, dias, calificacion, mensaje, foto) VALUES (?,?,?,?,?,?)";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$nombre, $destino, $dias, $calificacion, $mensaje, $foto]);

    echo json_encode(["success" => true, "message" => "Reseña guardada correctamente"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error al guardar la reseña: " . $e->getMessage()]);
}
