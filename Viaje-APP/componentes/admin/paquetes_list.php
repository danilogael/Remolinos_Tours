<?php
// VISTA: Muestra la tabla de paquetes con los nuevos campos de fecha.
// Asume que la conexión ($conexion) ya está disponible desde paquetes.php

// Función auxiliar para formatear la fecha
function format_date($date_str) {
    if (!$date_str || $date_str === '0000-00-00') {
        return 'N/A';
    }
    // Formato: AAAA/MM/DD
    return date('Y/m/d', strtotime($date_str)); 
}

try {
// CONSULTA: Trae las nuevas columnas de fecha
$stmt = $conexion->prepare("SELECT id, nombre, destino, precio_base, moneda_base, descuento, fecha_inicio, fecha_fin FROM paquetes ORDER BY fecha_inicio DESC");
$stmt->execute();
$paquetes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
echo "<div style='color: red;'>Error al cargar paquetes: " . $e->getMessage() . "</div>";
exit;
}
?>

<div class="package-management">
<h2>Gestión de Paquetes y Tours</h2>

<a href="?section=paquetes&action=create" 
class="btn-create-package btn-action" 
style="background-color: #27ae60; color: white; padding: 10px 15px; border: none; cursor: pointer; border-radius: 4px; margin-bottom: 20px; display: inline-block; text-decoration: none;">
<i class="fa-solid fa-plus"></i> Crear Nuevo Paquete
</a>

<?php if (count($paquetes) > 0): ?>
<table class="admin-table">
<thead>
<tr>
<th>ID</th>
<th>Nombre</th>
<th>Destino</th>
<th>Precio Base</th>
<th>Descuento</th>
<th>Inicio</th> <th>Fin</th> <th>Acciones</th>
</tr>
</thead>
<tbody>
<?php foreach ($paquetes as $paquete): ?>
<tr>
<td><?= htmlspecialchars($paquete['id']); ?></td>
<td><?= htmlspecialchars($paquete['nombre']); ?></td>
<td><?= htmlspecialchars($paquete['destino']); ?></td>

<td>
$<?= number_format($paquete['precio_base'] ?? 0, 2); ?> 
<strong><?= htmlspecialchars($paquete['moneda_base']); ?></strong>
</td>

<td>
<?php 
if (($paquete['descuento'] ?? 0) > 0) {
echo "<span style='color:red;font-weight:bold;'>-{$paquete['descuento']}%</span>";
} else {
echo "No";
}
?>
</td>

<td><?= format_date($paquete['fecha_inicio']); ?></td>
<td><?= format_date($paquete['fecha_fin']); ?></td>

<td>
<a href="/viaje/viaje/Viaje-APP/componentes/admin/tour_detalle.php?id=<?= $paquete['id']; ?>" 
 target="_blank" class="btn-action view">Ver</a>

<a href="?section=paquetes&action=edit&id=<?= $paquete['id']; ?>" 
 class="btn-action edit">Editar</a>

<button class="btn-delete-package btn-action delete" data-id="<?= $paquete['id']; ?>">
Eliminar
</button>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php else: ?>
<p>No hay paquetes registrados.</p>
<?php endif; ?>
</div>

<script>
document.querySelectorAll('.btn-delete-package').forEach(boton => {
boton.addEventListener('click', function() {
const id = this.dataset.id;

Swal.fire({
title: '¿Eliminar paquete?',
text: "Se eliminará también la imagen y todos los datos asociados.",
icon: 'warning',
showCancelButton: true,
confirmButtonColor: '#d33',
cancelButtonColor: '#3085d6',
confirmButtonText: 'Sí, eliminar'
}).then(res => {
if (res.isConfirmed) borrarPaquete(id);
});
});
});

async function borrarPaquete(id) {
const formData = new FormData();
formData.append('id', id);

// Asegúrate de que 'eliminar_paquete.php' esté bien configurado en tu proyecto
const res = await fetch('eliminar_paquete.php', {
method: 'POST',
body: formData
});

try {
        const data = await res.json();
        if (data.success) {
            Swal.fire('Eliminado', data.message, 'success').then(() => location.reload());
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    } catch (e) {
        // En caso de que el script no devuelva JSON
        Swal.fire('Error', 'Respuesta no válida del servidor. Consulta la consola.', 'error');
        console.error('Error de parseo:', await res.text());
    }
}
</script>