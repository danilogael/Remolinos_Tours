<?php
// CONTROLADOR DE LA SECCIÓN PAQUETES

// Ruta a la conexión (debe estar disponible para las vistas que se incluyen)
require __DIR__ . '/../../../LoginAPI/db.php'; 

// La variable $action ya está definida en admin_panel.php, pero la inicializamos por seguridad
$action = $_GET['action'] ?? 'list'; 

// Decidir qué componente incluir: lista o formulario.
if ($action === 'create' || $action === 'edit') {
    // Si la acción es crear o editar, cargamos el formulario.
    include 'paquetes_form.php'; 

} elseif ($action === 'list') {
    // Si la acción es listar (el valor por defecto), cargamos la tabla.
    include 'paquetes_list.php'; 

} else {
    echo "<h1>Acción no válida.</h1>";
}
?>