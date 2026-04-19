<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header class="site-header" id="site-header">
  <div class="header-inner">

    <!-- LOGO -->
    <div class="header-logo">
      <a href="/Viaje-APP/default.php" class="logo-link">
        <img src="/Viaje-APP/componentes/header/Logo.png" alt="Remolinos Tours" class="logo-img">
        <span class="logo-text">Remolinos <span class="logo-dot">Tours</span></span>
      </a>
    </div>

    <!-- NAV DESKTOP -->
    <nav class="header-nav" id="header-nav">
      <ul class="nav-list">
        <li><a href="/Viaje-APP/default.php" class="nav-link">Inicio</a></li>
        <li><a href="/Viaje-APP/componentes/paquetes/paquete.php" class="nav-link">Descubre</a></li>
        <li><a href="/Viaje-APP/componentes/Planea/planea.php" class="nav-link">Planea tu viaje</a></li>
        <li><a href="/Viaje-APP/componentes/ofertas/ofertas.php" class="nav-link">Ofertas</a></li>
      </ul>
    </nav>

    <!-- ACCIONES -->
    <div class="header-actions">
      <?php if (isset($_SESSION['user_id'])): ?>
        <div class="user-menu-wrap">
          <button class="user-btn" id="user-btn" aria-label="Menú usuario">
            <i class="fas fa-user-circle"></i>
            <span class="user-name">Mi cuenta</span>
            <i class="fas fa-chevron-down user-chevron"></i>
          </button>
          <ul class="user-dropdown" id="user-dropdown">
            <li>
              <a href="/Viaje-APP/componentes/ViewData/ViewData.php">
                <i class="fas fa-user"></i> Mi perfil
              </a>
            </li>
            <li class="dropdown-divider"></li>
            <li>
              <a href="/LoginAPI/login/logOut.php" class="logout-link">
                <i class="fas fa-sign-out-alt"></i> Cerrar sesión
              </a>
            </li>
          </ul>
        </div>
      <?php else: ?>
        <a href="/Viaje-APP/componentes/iniciarsesion/sign.php" class="btn-signin">
          <i class="fas fa-user"></i> Iniciar sesión
        </a>
        <a href="/Viaje-APP/componentes/paquetes/paquete.php" class="btn-reservar">
          Reservar Ahora
        </a>
      <?php endif; ?>
    </div>

    <!-- HAMBURGUESA -->
    <button class="hamburger" id="hamburger" aria-label="Abrir menú">
      <span></span>
      <span></span>
      <span></span>
    </button>

  </div>

  <!-- NAV MÓVIL -->
  <div class="mobile-menu" id="mobile-menu">
    <ul class="mobile-nav-list">
      <li><a href="/Viaje-APP/default.php"><i class="fas fa-home"></i> Inicio</a></li>
      <li><a href="/Viaje-APP/componentes/paquetes/paquete.php"><i class="fas fa-globe"></i> Descubre</a></li>
      <li><a href="/Viaje-APP/componentes/Planea/planea.php"><i class="fas fa-map-marked-alt"></i> Planea tu viaje</a></li>
      <li><a href="/Viaje-APP/componentes/ofertas/ofertas.php"><i class="fas fa-tag"></i> Ofertas</a></li>
      <li class="mobile-divider"></li>
      <?php if (isset($_SESSION['user_id'])): ?>
        <li><a href="/Viaje-APP/componentes/ViewData/ViewData.php"><i class="fas fa-user"></i> Mi perfil</a></li>
        <li><a href="/LoginAPI/login/logOut.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a></li>
      <?php else: ?>
        <li><a href="/Viaje-APP/componentes/iniciarsesion/sign.php" class="mobile-btn-signin"><i class="fas fa-user"></i> Iniciar sesión</a></li>
        <li><a href="/Viaje-APP/componentes/paquetes/paquete.php" class="mobile-btn-reservar">Reservar Ahora</a></li>
      <?php endif; ?>
    </ul>
  </div>
</header>
