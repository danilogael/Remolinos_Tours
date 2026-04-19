<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// VISTA: Formulario de Creación/Edición de Paquetes.
// Asume que la conexión ($conexion) ya está disponible desde paquetes.php

// 🚨 INICIALIZACIÓN DE VARIABLES PARA EVITAR ADVERTENCIAS (WARNINGS) 🚨
$paquete_a_editar = [];
$form_title = "Crear Nuevo Paquete"; 
$submit_text = "Guardar Paquete"; 
$paquete_id = null;
// --------------------------------------------------------------------

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
 $paquete_id = $_GET['id'];
 
 // Obtener TODOS los datos del paquete a editar
 $stmt = $conexion->prepare("SELECT * FROM paquetes WHERE id = ?");
 $stmt->execute([$paquete_id]);
 $paquete_a_editar = $stmt->fetch(PDO::FETCH_ASSOC);

 if ($paquete_a_editar) {
  // Sobreescribe los valores para el modo Edición
  $form_title = "Editar Paquete: " . htmlspecialchars($paquete_a_editar['nombre']);
  $submit_text = "Actualizar Paquete";
 } else {
     $paquete_id = null;
  }
}
?>

<div class="package-form-container">
  <h2><?php echo $form_title; ?></h2> 
  
  <form id="paquetesForm" action="paquetes_crud.php" method="POST" enctype="multipart/form-data">
    
    <?php if ($paquete_id): ?>
     <input type="hidden" name="id" value="<?php echo $paquete_id; ?>">
    <?php endif; ?>

    <div class="form-group">
     <label for="nombre">Nombre del Paquete:</label>
     <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($paquete_a_editar['nombre'] ?? ''); ?>" required>
    </div>

    <div class="form-group">
     <label for="destino">Destino:</label>
     <input type="text" name="destino" id="destino" value="<?php echo htmlspecialchars($paquete_a_editar['destino'] ?? ''); ?>" required>
    </div>
        
        <hr>
        
        <div class="form-group">
     <label for="fecha_inicio">Fecha de Inicio:</label>
     <input type="date" name="fecha_inicio" id="fecha_inicio" 
       value="<?php echo htmlspecialchars($paquete_a_editar['fecha_inicio'] ?? date('Y-m-d')); ?>" required>
        </div>

        <div class="form-group">
     <label for="fecha_fin">Fecha de Fin:</label>
     <input type="date" name="fecha_fin" id="fecha_fin" 
       value="<?php echo htmlspecialchars($paquete_a_editar['fecha_fin'] ?? ''); ?>" required>
        </div>
            <div class="form-group">
     <label for="categoria">Categoría (Ej: Aventura, Familiar):</label>
     <input type="text" name="categoria" id="categoria" value="<?php echo htmlspecialchars($paquete_a_editar['categoria'] ?? ''); ?>" required>
    </div>
    
    <div class="form-group">
     <label for="calificacion">Calificación (1 a 5 estrellas):</label>
     <input type="number" step="1" min="1" max="5" name="calificacion" id="calificacion" value="<?php echo htmlspecialchars($paquete_a_editar['calificacion'] ?? '4'); ?>" required>
    </div>
    
    <hr>
    
    <div class="form-group">
     <label for="nombre_hotel">Nombre del Hotel Fijo:</label>
     <input type="text" name="nombre_hotel" id="nombre_hotel" 
      value="<?php echo htmlspecialchars($paquete_a_editar['nombre_hotel'] ?? ''); ?>" required>
    </div>
      
    <div class="form-group">
     <label for="tipo_habitacion">Tipo de Habitación Fija:</label>
     <input type="text" name="tipo_habitacion" id="tipo_habitacion" 
      value="<?php echo htmlspecialchars($paquete_a_editar['tipo_habitacion'] ?? ''); ?>" placeholder="Ej: Doble Estándar" required>
    </div>
    <div class="form-group">
     <label for="cant_adultos">Capacidad Máx. Adultos:</label>
     <input type="number" step="1" min="1" name="cant_adultos" id="cant_adultos" 
      value="<?php echo htmlspecialchars($paquete_a_editar['cant_adultos'] ?? '2'); ?>" required>
    </div>
    <div class="form-group">
     <label for="cant_ninos">Capacidad Máx. Niños:</label>
     <input type="number" step="1" min="0" name="cant_ninos" id="cant_ninos" 
      value="<?php echo htmlspecialchars($paquete_a_editar['cant_ninos'] ?? '0'); ?>" required>
    </div>

    <div class="form-group">
     <label for="transporte">Transporte:</label>
     <select name="transporte" id="transporte" required>
      <option value="Avión" <?php echo (isset($paquete_a_editar['tipo_transporte']) && $paquete_a_editar['tipo_transporte'] == 'Avión') ? 'selected' : ''; ?>>Avión</option>
      <option value="Camión" <?php echo (isset($paquete_a_editar['tipo_transporte']) && $paquete_a_editar['tipo_transporte'] == 'Camión') ? 'selected' : ''; ?>>Camión</option>
     </select>
    </div>

    <div class="form-group">
     <label for="equipaje_peso_kg">Equipaje Máx. (kg):</label>
     <input type="number" step="1" min="0" name="equipaje_peso_kg" id="equipaje_peso_kg" 
      value="<?php echo htmlspecialchars($paquete_a_editar['equipaje_peso_kg'] ?? ''); ?>">
    </div>
      
    <hr>

    <div class="form-group">
     <label for="precio_base">Precio Base del Paquete (por persona):</label>
     <input type="number" step="0.01" name="precio_base" id="precio_base" 
      value="<?php echo htmlspecialchars($paquete_a_editar['precio_base'] ?? ''); ?>" required>
    </div>
    
    <div class="form-group">
     <label for="moneda_base">Moneda Base:</label>
     <select name="moneda_base" id="moneda_base" required>
      <option value="MXN" <?php echo (isset($paquete_a_editar['moneda_base']) && $paquete_a_editar['moneda_base'] == 'MXN') ? 'selected' : ''; ?>>MXN (Pesos Mexicanos)</option>
      <option value="USD" <?php echo (isset($paquete_a_editar['moneda_base']) && $paquete_a_editar['moneda_base'] == 'USD') ? 'selected' : ''; ?>>USD (Dólares)</option>
     </select>
    </div>
    
    <div class="form-group">
     <label for="descuento">Descuento (%) (Ej: 15.00):</label>
     <input type="number" step="0.01" min="0" max="100" name="descuento" id="descuento" value="<?php echo htmlspecialchars($paquete_a_editar['descuento'] ?? '0.00'); ?>">
    </div>

    <div class="form-group">
     <label for="precio_original">Precio Original (Si hay descuento):</label>
     <input type="number" step="0.01" name="precio_original" id="precio_original" value="<?php echo htmlspecialchars($paquete_a_editar['precio_original'] ?? ''); ?>">
    </div>

    <hr>

    <div class="form-group">
     <label for="descripcion">Descripción Completa (Contenido principal):</label>
     <textarea name="descripcion" id="descripcion" rows="8" required><?php echo htmlspecialchars($paquete_a_editar['descripcion'] ?? ''); ?></textarea>
    </div>

    <div class="form-group">
     <label for="actividades_txt">Lista de Actividades (Usar ENTER para separar ítems):</label>
     <textarea name="actividades_txt" id="actividades_txt" rows="5"><?php echo htmlspecialchars($paquete_a_editar['actividades_txt'] ?? ''); ?></textarea>
    </div>
    
    <div class="form-group">
     <label for="enlace_reserva">URL de Reserva (Formulario de Contacto/Pago):</label>
     <input type="url" name="enlace_reserva" id="enlace_reserva" value="<?php echo htmlspecialchars($paquete_a_editar['enlace_reserva'] ?? ''); ?>" placeholder="/formularios/reserva_contacto.php?id=...">
    </div>
    
    <div class="form-group">
     <label for="imagen">Imagen Principal:</label>
     <input type="file" name="imagen" id="imagen" accept="image/*" 
       <?php echo $paquete_a_editar ? '' : 'required'; ?>>
     <?php if (isset($paquete_a_editar['imagen_url']) && $paquete_a_editar['imagen_url']): ?>
      <p style="margin-top: 5px;">Imagen actual: 
       <a href="<?php echo '/viaje/viaje/Viaje-APP/assets/paquetes_img/' . htmlspecialchars($paquete_a_editar['imagen_url']); ?>" target="_blank">Ver</a>
      </p>
      <input type="hidden" name="imagen_actual" value="<?php echo htmlspecialchars($paquete_a_editar['imagen_url']); ?>">
     <?php endif; ?>
    </div>

    <button type="submit" class="btn-action edit"><?php echo $submit_text; ?></button>
    <a href="?section=paquetes" class="btn-action view">Cancelar</a>
    
  </form>
</div>