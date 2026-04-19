<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Definir moneda por defecto si no existe en sesión
if (!isset($_SESSION['currency'])) {
    $_SESSION['currency'] = 'MXN';
}
$currentCurrency = $_SESSION['currency'];
?>

<header>
  <div class="logo-titulo">
    <img src="/viaje/viaje/Viaje-APP/componentes/header/Logo.png" alt="Logo Remolinos Tours" width="100">
  <a href="#" class="logo">Remolinos Tours <span>.</span></a>
  </div>

  <nav>
    <ul class="nav-links" id="nav-links">
      <li><a href="/viaje/viaje/Viaje-APP/default.php">Listo para tu viaje</a></li>
      <li><a href="/viaje/viaje/Viaje-APP/componentes/paquetes/paquete.php">Descubre</a></li>
      <li><a href="/viaje/viaje/Viaje-APP/componentes/Planea/planea.php">Planea tu viaje</a></li>
      <li><a href="/viaje/viaje/Viaje-APP/componentes/ofertas/ofertas.php">Ofertas</a></li>

      <div id="google_translate_element" class="text-sm p-2">
    </div>

      <?php if (isset($_SESSION['user_id'])): ?>
        <li class="user-menu">
          <div class="user-icon">
            <i class="fas fa-user"></i>
          </div>
          <ul class="dropdown">
            <li><a href="/viaje/viaje/Viaje-APP/componentes/ViewData/ViewData.php">Mi perfil</a></li>
            <li><a href="/viaje/viaje/LoginAPI/login/logOut.php">Cerrar sesión</a></li>
          </ul>
        </li>
      <?php else: ?>
        <li><a href="/viaje/viaje/Viaje-APP/componentes/iniciarsesion/sign.php">Iniciar sesión</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>

<!-- JS -->
<script src="/viaje/viaje/Viaje-APP/componentes/js/header.js"></script>
<script src="/viaje/viaje/Viaje-APP/componentes/js/currency.js"></script>
<script src="/viaje/viaje/Viaje-APP/assets/js/index.js"></script>
