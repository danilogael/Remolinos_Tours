<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/Agencia_Remolinos/componentes/header/header.css">

<header class="site-header" id="site-header">
  <div class="header-inner">

    <div class="header-logo">
      <a href="#" class="logo-link">
        <img src="/Agencia_Remolinos/assets/imagenes/Logo.png" alt="Remolinos Tours" class="logo-img">
        <span class="logo-text">
          Remolinos <span class="logo-dot">Tours</span>
        </span>
      </a>
    </div>

    <nav class="header-nav" id="header-nav">
      <ul class="nav-list">
          <li><a href="/Agencia_Remolinos/index.php" class="nav-link">Inicio</a></li>
          <li><a href="/Agencia_Remolinos/componentes/Destinos/destinos.php" class="nav-link">Destinos</a></li>
          <li><a href="/Agencia_Remolinos/componentes/Reserva/reserva.php" class="nav-link">Reserva</a></li>
          <li><a href="/Agencia_Remolinos/componentes/ofertas/ofertas.php" class="nav-link">Ofertas</a></li>
      </ul>
    </nav>

    <div class="header-actions">
      <?php if (isset($_SESSION['id_usuario'])): ?>
        <div class="user-menu-wrap">
          <button class="user-btn" id="user-btn" aria-label="Menú usuario">
            <i class="fas fa-user-circle"></i>
            <span class="user-name"><?php echo explode(' ', $_SESSION['nombre'])[0]; ?></span>
            <i class="fas fa-chevron-down user-chevron"></i>
          </button>
          <ul class="user-dropdown" id="user-dropdown">
            <li>
              <a href="/Agencia_Remolinos/user/viewdata.php">
                <i class="fas fa-user"></i> Mi perfil
              </a>
            </li>
            <li class="dropdown-divider"></li>
            <li>
              <a href="/Agencia_Remolinos/Login_API/logout.php" class="logout-link">
                <i class="fas fa-sign-out-alt"></i> Cerrar sesión
              </a>
            </li>
          </ul>
        </div>
      <?php else: ?>
        <a href="/Agencia_Remolinos/Login_APP/login.php" class="btn-signin">
          <i class="fas fa-user"></i> Iniciar sesión
        </a>
        <a href="/Agencia_Remolinos/Login_APP/registro.php" class="btn-reservar">
          Reservar Ahora
        </a>
      <?php endif; ?>
    </div>

    <button class="hamburger" id="hamburger" aria-label="Abrir menú">
      <span></span>
      <span></span>
      <span></span>
    </button>

  </div>

  <div class="mobile-menu" id="mobile-menu">
    <ul class="mobile-nav-list">
      <li><a href="/Agencia_Remolinos/index.php"><i class="fas fa-home"></i> Inicio</a></li>
      <li><a href="/Agencia_Remolinos/componentes/Destinos/destinos.php"><i class="fas fa-globe"></i> Descubre</a></li>
      <li><a href="/Agencia_Remolinos/componentes/Planea/planea.php"><i class="fas fa-map-marked-alt"></i> Planea tu viaje</a></li>
      <li><a href="/Agencia_Remolinos/componentes/ofertas/ofertas.php"><i class="fas fa-tag"></i> Ofertas</a></li>
      <li class="mobile-divider"></li>
      <?php if (isset($_SESSION['id_usuario'])): ?>
        <li><a href="/Agencia_Remolinos/user/viewdata.php"><i class="fas fa-user"></i> Mi perfil</a></li>
        <li><a href="/Agencia_Remolinos/Login_API/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a></li>
      <?php else: ?>
        <li><a href="/Agencia_Remolinos/Login_APP/login.php" class="mobile-btn-signin"><i class="fas fa-user"></i> Iniciar sesión</a></li>
        <li><a href="/Agencia_Remolinos/Login_APP/registro.php" class="mobile-btn-reservar">Reservar Ahora</a></li>
      <?php endif; ?>
    </ul>
  </div>
</header>

<script>
(function () {
  const header    = document.getElementById('site-header');
  const hamburger = document.getElementById('hamburger');
  const mobileMenu= document.getElementById('mobile-menu');
  const userBtn   = document.getElementById('user-btn');
  const userDrop  = document.getElementById('user-dropdown');
  const userWrap  = userBtn ? userBtn.closest('.user-menu-wrap') : null;

  /* ── Scroll: Efecto Header ── */
  function onScroll() {
    if (header) {
      window.scrollY > 50 ? header.classList.add('scrolled') : header.classList.remove('scrolled');
    }
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  /* ── Hamburguesa ── */
  if (hamburger && mobileMenu) {
    hamburger.addEventListener('click', function (e) {
      e.stopPropagation();
      const open = mobileMenu.classList.toggle('open');
      hamburger.classList.toggle('open', open);
      hamburger.setAttribute('aria-label', open ? 'Cerrar menú' : 'Abrir menú');
    });
  }

  /* ── Dropdown usuario ── */
  if (userBtn && userWrap) {
    userBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      if (mobileMenu) mobileMenu.classList.remove('open');
      userWrap.classList.toggle('open');
    });

    document.addEventListener('click', function (e) {
      if (!userWrap.contains(e.target)) {
        userWrap.classList.remove('open');
      }
      if (mobileMenu && !mobileMenu.contains(e.target) && !hamburger.contains(e.target)) {
        mobileMenu.classList.remove('open');
        hamburger.classList.remove('open');
      }
    });
  }

  /* ── Marcar link activo ── */
  const currentPath = window.location.pathname;
  document.querySelectorAll('.nav-link, .mobile-nav-list a').forEach(function (link) {
    const href = link.getAttribute('href');
    if (href && href !== '#' && currentPath.endsWith(href.split('/').pop())) {
      link.classList.add('active');
    }
  });
})();
</script>
