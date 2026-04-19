<?php
// Incluir la conexión a la base de datos
// Ajusta la ruta si tu archivo db.php está en otro lugar
require __DIR__ . '/../../../LoginAPI/db.php';

// --- 1. Lógica de Consulta ---
try {
    // Consulta SQL para seleccionar *todos* los campos de los usuarios con el rol 'cliente'
    $stmt = $conexion->prepare("SELECT 
        id, 
        nombre, 
        apellido_paterno, 
        apellido_materno, 
        correo, 
        telefono 
        FROM usuarios 
        WHERE rol = 'cliente' 
        ORDER BY fecha_registro DESC"); 
        
    $stmt->execute();
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "<div style='color: red;'>Error al cargar los usuarios: " . $e->getMessage() . "</div>";
    // En un entorno real, solo registra el error, no lo muestres al usuario.
    exit;
}


?>

<div class="user-management">
    <h2>Gestión de Clientes Registrados</h2>
    
    <?php if (count($clientes) > 0): ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Completo</th>
                <th>Correo Electrónico</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clientes as $cliente): ?>
            <tr>
                <td><?php echo htmlspecialchars($cliente['id']); ?></td>
                <td><?php echo htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido_paterno'] . ' ' . $cliente['apellido_materno']); ?></td>
                <td><?php echo htmlspecialchars($cliente['correo']); ?></td>
                <td><?php echo htmlspecialchars($cliente['telefono']); ?></td>
                <td>
                    <button class="btn-edit-user" data-id="<?php echo $cliente['id']; ?>">Editar</button>
                    <button class="btn-delete-user" data-id="<?php echo $cliente['id']; ?>">Eliminar</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>No hay clientes registrados en este momento.</p>
    <?php endif; ?>
</div>

<style>
/* Estilos básicos para la tabla de administración */
.admin-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
.admin-table th, .admin-table td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: left;
}
.admin-table th {
    background-color: #34495e;
    color: white;
}
.admin-table tr:nth-child(even) {
    background-color: #f2f2f2;
}
.admin-table tr:hover {
    background-color: #e0e0e0;
}
.btn-edit-user, .btn-delete-user {
    padding: 5px 10px;
    border: none;
    cursor: pointer;
    border-radius: 4px;
    margin-right: 5px;
}
.btn-edit-user {
    background-color: #2980b9;
    color: white;
}
.btn-delete-user {
    background-color: #e74c3c;
    color: white;
}
</style>