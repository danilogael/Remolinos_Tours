<?php
session_start();

// 1. VERIFICACIÓN DE SEGURIDAD CRUCIAL
if (!isset($_SESSION['user_id'])) {
  header('Location: /viaje/viaje/Viaje-APP/componentes/iniciarsesion/sign.php');
  exit();
}

if ($_SESSION['rol'] !== 'admin') {
  header('Location: /viaje/viaje/Viaje-APP/componentes/ViewData/ViewData.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Administración — Remolinos Tours</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>   <style>
    /* Estilos básicos que ya tenías */
    .admin-container { display: flex; min-height: 100vh; }
    .admin-sidebar { width: 250px; background-color: #2c3e50; color: white; padding-top: 20px; }
    .admin-menu { list-style: none; padding: 0; }
    .admin-menu li a { display: block; padding: 15px 20px; color: #ecf0f1; text-decoration: none; }
    .admin-menu li a:hover,
    .admin-menu li a.active { background-color: #34495e; color: #1abc9c; }
    .admin-content { flex-grow: 1; padding: 30px; }
    /* Estilos básicos de tabla para que funcione paquetes_list.php */
    .admin-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .admin-table th, .admin-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .btn-action { padding: 5px 10px; border-radius: 4px; text-decoration: none; margin: 2px; display: inline-block; font-size: 0.9em; }
        .btn-action.view { background-color: #3498db; color: white; }
        .btn-action.edit { background-color: #f39c12; color: white; }
        .btn-action.delete { background-color: #e74c3c; color: white; border: none; cursor: pointer; }
  </style>
</head>
<body>

<div class="admin-container">
  
  <aside class="admin-sidebar">
    <h3 style="text-align:center;margin-bottom:30px;color:#1abc9c;">⚙️ Admin Panel</h3>
    <ul class="admin-menu">
      <li><a href="?section=usuarios"><i class="fa-solid fa-users"></i> Usuarios</a></li>
      <li><a href="?section=paquetes"><i class="fa-solid fa-plane-departure"></i> Paquetes (Tours)</a></li>
      <li><a href="?section=ofertas"><i class="fa-solid fa-tag"></i> Ofertas / Descuentos</a></li>
      <li><a href="?section=reseñas"><i class="fa-solid fa-star"></i> Reseñas</a></li>
      <li><a href="?section=mensajes"><i class="fa-solid fa-envelope"></i> Mensajes de Contacto</a></li>
      <li><a href="?section=cotizaciones"><i class="fa-solid fa-file-invoice"></i> Cotizaciones</a></li>
      <li><a href="/viaje/viaje/LoginAPI/login/logOut.php" style="color:#e74c3c;"><i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión</a></li>
    </ul>
  </aside>

  <main class="admin-content">

    <?php
    $section = $_GET['section'] ?? 'dashboard';
        $action = $_GET['action'] ?? 'list'; // Define la acción para el controlador

    switch ($section) {

      case 'usuarios':
        include 'usuarios.php';
        break;

      case 'paquetes': // ¡El único punto de entrada para Paquetes!
        include 'paquetes.php';
        break;
                
            // ELIMINAMOS EL CASO 'paquetes_form'

      case 'tour_detalle': 
        include 'tour_detalle.php';
        break;
                
      case 'ofertas':
        echo '<h2>Gestión de Ofertas</h2>';
        break;

      case 'reseñas':
        echo '<h2>Gestión de Reseñas</h2>';
        break;

      case 'mensajes':
        echo '<h2>Bandeja de Mensajes</h2>';
        break;

      case 'cotizaciones':
        echo '<h2>Gestión de Cotizaciones</h2>';
        break;

      case 'dashboard':
        echo '<h1>Bienvenido al Dashboard</h1>';
        break;

      default:
        echo '<h1>Error 404</h1>';
        break;
    }
    ?>

  </main>
</div>

</body>
</html>