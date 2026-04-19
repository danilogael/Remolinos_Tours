<?php
// Incluir la conexión a la base de datos
require __DIR__ . '/../../../LoginAPI/db.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
  // Si no hay ID válido, mostramos un error simple y terminamos
  die("ID de paquete no válido.");
}

$stmt = $conexion->prepare("SELECT * FROM paquetes WHERE id = ?");
$stmt->execute([$id]);
$paquete = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$paquete) {
  // Si el ID es válido pero no se encuentra el paquete en la DB
  die("Paquete no encontrado.");
}

//
$base_image_path = '/viaje/viaje/Viaje-APP/assets/paquetes_img/'; 

// Función auxiliar para formatear la fecha a un formato más legible
function format_date($date_str) {
    if (!$date_str || $date_str === '0000-00-00') {
        return 'N/A';
    }
    // Formato: 1 Diciembre, 2025
    return date('j M, Y', strtotime($date_str));
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Detalles del Paquete: <?= htmlspecialchars($paquete['nombre']); ?></title>
<style>
  body { font-family: Arial, sans-serif; padding: 20px; line-height: 1.6; }
  h1 { color: #2c3e50; border-bottom: 2px solid #ecf0f1; padding-bottom: 10px; }
  h3 { color: #34495e; margin-top: 20px; }
  img { max-width: 100%; width: 400px; height: auto; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
  strong { display: inline-block; width: 130px; font-weight: bold; }
    ul { list-style-type: disc; margin-left: 20px; }
</style>
</head>
<body>

<h1><?= htmlspecialchars($paquete['nombre']); ?></h1>

<?php if (!empty($paquete['imagen_url'])): ?>
  <img src="<?= $base_image_path . htmlspecialchars($paquete['imagen_url']); ?>" alt="Imagen del Paquete <?= htmlspecialchars($paquete['nombre']); ?>">
<?php endif; ?>

<hr>

<h2>📅 Fechas y Precios</h2>

<p><strong>Inicia:</strong> <?= format_date($paquete['fecha_inicio']); ?></p>
<p><strong>Termina:</strong> <?= format_date($paquete['fecha_fin']); ?></p>
<p><strong>Destino:</strong> <?= htmlspecialchars($paquete['destino']); ?></p>
<p><strong>Precio Base:</strong> $<?= number_format($paquete['precio_base'] ?? 0, 2); ?> <?= htmlspecialchars($paquete['moneda_base']); ?></p> 
<?php if ($paquete['descuento'] > 0): ?>
    <p style="color: red;"><strong>¡Oferta! Descuento:</strong> <?= htmlspecialchars($paquete['descuento']); ?>%</p>
    <?php if ($paquete['precio_original'] > 0): ?>
        <p><strong>Precio Original:</strong> <s style="color: #999;">$<?= number_format($paquete['precio_original'], 2); ?></s></p>
    <?php endif; ?>
<?php endif; ?>

<hr>

<h2>🏨 Alojamiento y Transporte</h2>

<p><strong>Hotel Fijo:</strong> <?= htmlspecialchars($paquete['nombre_hotel'] ?? 'No especificado'); ?></p>
<p><strong>Tipo Habitación:</strong> <?= htmlspecialchars($paquete['tipo_habitacion'] ?? 'Estándar'); ?></p>
<p><strong>Ocupación:</strong> <?= htmlspecialchars($paquete['cant_adultos'] ?? 1); ?> Adultos / <?= htmlspecialchars($paquete['cant_ninos'] ?? 0); ?> Niños</p>
<p><strong>Transporte:</strong> <?= htmlspecialchars($paquete['tipo_transporte'] ?? 'No especificado'); ?></p>
<p><strong>Equipaje Máx.:</strong> <?= htmlspecialchars($paquete['equipaje_peso_kg'] ?? 'N/A'); ?> kg</p>

<hr>

<h2>📝 Descripción y Actividades</h2>

<p><strong>Categoría:</strong> <?= htmlspecialchars($paquete['categoria'] ?? 'N/A'); ?></p>
<p><strong>Calificación:</strong> <?= htmlspecialchars($paquete['calificacion'] ?? 0); ?> estrellas</p>

<h3>Descripción:</h3>
<p><?= nl2br(htmlspecialchars($paquete['descripcion'])); ?></p>

<?php if (!empty($paquete['actividades_txt'])): ?>
    <h3>Actividades Incluidas:</h3>
    <ul>
    <?php
    $actividades = explode("\n", $paquete['actividades_txt']);
    foreach ($actividades as $actividad) {
        $trimmed_act = trim($actividad);
        if (!empty($trimmed_act)) {
            echo "<li>" . htmlspecialchars($trimmed_act) . "</li>";
        }
    }
    ?>
    </ul>
<?php endif; ?>

<?php if (!empty($paquete['enlace_reserva'])): ?>
    <div style="margin-top: 30px; text-align: center;">
        <a href="<?= htmlspecialchars($paquete['enlace_reserva']); ?>" target="_blank" style="padding: 15px 30px; background-color: #e67e22; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 1.2em; transition: background-color 0.3s;">
            ¡Reservar Este Paquete Ahora!
        </a>
    </div>
<?php endif; ?>

</body>
</html>