
<?php

session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Restricción de acceso
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    die("Acceso no autorizado.");
}

// Ruta a la conexión
require __DIR__ . '/../../../LoginAPI/db.php'; 

$is_editing = isset($_POST['id']) && !empty($_POST['id']);

// Carpeta de imágenes
$target_dir = __DIR__ . '/../../assets/paquetes_img/'; 
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true);
}

// Función para redireccionar con SweetAlert al cliente
function redirect_with_alert($icon, $title, $text, $redirect_url = '/viaje/viaje/Viaje-APP/componentes/paquetes/paquetes.php') {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
    Swal.fire({
        icon: '$icon',
        title: '$title',
        text: '$text'
    }).then(() => {
        window.location.href = '$redirect_url';
    });
    </script>";
    exit;
}

// --- 1. PROCESAR SUBIDA DE IMAGEN ---
$imagen_url = $_POST['imagen_actual'] ?? null;

if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
    $file_info = pathinfo($_FILES["imagen"]["name"]);
    $file_extension = strtolower($file_info['extension']);
    $allowed_extensions = ["jpg", "jpeg", "png", "gif"];

    if (!in_array($file_extension, $allowed_extensions)) {
        redirect_with_alert("error", "Error de Archivo", "Solo se permiten archivos JPG, JPEG, PNG y GIF.");
    }

    $new_file_name = uniqid("pkg_") . "." . $file_extension;
    $target_file = $target_dir . $new_file_name;

    if (!move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
        redirect_with_alert("error", "Error de Subida", "Hubo un error al mover el archivo de imagen.");
    }

    $imagen_url = $new_file_name;

    // Eliminar imagen anterior si estamos editando
    if ($is_editing && isset($_POST['imagen_actual']) && !empty($_POST['imagen_actual'])) {
        $old_file = $target_dir . $_POST['imagen_actual'];
        if (file_exists($old_file) && is_file($old_file)) {
            @unlink($old_file);
        }
    }
} elseif (!$is_editing && is_null($imagen_url)) {
    redirect_with_alert("error", "Error de Formulario", "La imagen principal es obligatoria al crear un paquete.");
}

// --- 2. CAPTURAR Y SANITIZAR TODOS LOS DATOS ---
$nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
$destino = filter_input(INPUT_POST, 'destino', FILTER_SANITIZE_STRING);
$fecha_inicio = filter_input(INPUT_POST, 'fecha_inicio', FILTER_SANITIZE_STRING);
$fecha_fin = filter_input(INPUT_POST, 'fecha_fin', FILTER_SANITIZE_STRING);

$nombre_hotel = filter_input(INPUT_POST, 'nombre_hotel', FILTER_SANITIZE_STRING); 
$tipo_habitacion = filter_input(INPUT_POST, 'tipo_habitacion', FILTER_SANITIZE_STRING);
$cant_adultos = filter_input(INPUT_POST, 'cant_adultos', FILTER_VALIDATE_INT); 
$cant_ninos = filter_input(INPUT_POST, 'cant_ninos', FILTER_VALIDATE_INT); 

$tipo_transporte = filter_input(INPUT_POST, 'transporte', FILTER_SANITIZE_STRING); 
$equipaje_peso_kg = filter_input(INPUT_POST, 'equipaje_peso_kg', FILTER_VALIDATE_INT);

$precio_base = filter_input(INPUT_POST, 'precio_base', FILTER_VALIDATE_FLOAT); 
$moneda_base = filter_input(INPUT_POST, 'moneda_base', FILTER_SANITIZE_STRING);
$descuento = filter_input(INPUT_POST, 'descuento', FILTER_VALIDATE_FLOAT);
$precio_original = filter_input(INPUT_POST, 'precio_original', FILTER_VALIDATE_FLOAT);

$categoria = filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_STRING);
$calificacion = filter_input(INPUT_POST, 'calificacion', FILTER_VALIDATE_INT);
$descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$actividades_txt = filter_input(INPUT_POST, 'actividades_txt', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$enlace_reserva = filter_input(INPUT_POST, 'enlace_reserva', FILTER_SANITIZE_URL);

// Validación básica
if (!$nombre || !$destino || !$precio_base || !$fecha_inicio || !$fecha_fin || !$descripcion || !$nombre_hotel || !$cant_adultos || !$tipo_habitacion) { 
    redirect_with_alert("error", "Datos Faltantes", "Por favor, completa todos los campos requeridos.");
}

// --- 3. DEFINICIÓN DE CAMPOS Y PARÁMETROS ---
$fields = [
    'nombre', 'destino', 'fecha_inicio', 'fecha_fin',
    'hotel_nombre', 'tipo_habitacion', 'cant_adultos', 'cant_ninos',
    'transporte', 'equipaje_peso_kg',
    'precio_base', 'moneda_base', 'descuento', 'precio_original',
    'categoria', 'calificacion', 'descripcion', 'actividades_txt', 'enlace_reserva', 'imagen_url'
];

$params = [
    $nombre, $destino, $fecha_inicio, $fecha_fin,
    $nombre_hotel, $tipo_habitacion, $cant_adultos, $cant_ninos,
    $tipo_transporte, $equipaje_peso_kg,
    $precio_base, $moneda_base, $descuento, $precio_original,
    $categoria, $calificacion, $descripcion, $actividades_txt, $enlace_reserva, $imagen_url
];

try {
    if ($is_editing) {
        // --- UPDATE ---
        $setClauses = implode(' = ?, ', $fields) . ' = ?';
        $sql = "UPDATE paquetes SET {$setClauses} WHERE id = ?";
        $params[] = $_POST['id'];

        $stmt = $conexion->prepare($sql);
        $stmt->execute($params);

        redirect_with_alert("success", "Actualizado", "Paquete actualizado exitosamente.");
    } else {
        // --- INSERT ---
        $placeholders = str_repeat('?, ', count($fields) - 1) . '?';
        $columnNames = implode(', ', $fields);

        $sql = "INSERT INTO paquetes ({$columnNames}) VALUES ({$placeholders})";

        $stmt = $conexion->prepare($sql);
        $stmt->execute($params);

        redirect_with_alert("success", "Creado", "Paquete creado exitosamente.");
    }

} catch (PDOException $e) {
    error_log("Error de BD: " . $e->getMessage());
    redirect_with_alert("error", "Error de Base de Datos", "No se pudo guardar el paquete. Detalle: {$e->getMessage()}");
}
?>
