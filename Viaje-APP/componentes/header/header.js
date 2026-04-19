(function () {
  const header    = document.getElementById('site-header');
  const hamburger = document.getElementById('hamburger');
  const mobileMenu= document.getElementById('mobile-menu');
  const userBtn   = document.getElementById('user-btn');
  const userDrop  = document.getElementById('user-dropdown');
  const userWrap  = userBtn ? userBtn.closest('.user-menu-wrap') : null;

  /* ── Scroll: espejo → blanco ── */
  function onScroll() {
    if (window.scrollY > 50) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  /* ── Hamburguesa ── */
  hamburger.addEventListener('click', function () {
    const open = mobileMenu.classList.toggle('open');
    hamburger.classList.toggle('open', open);
    hamburger.setAttribute('aria-label', open ? 'Cerrar menú' : 'Abrir menú');
  });

  /* ── Dropdown usuario ── */
  if (userBtn && userWrap) {
    userBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      userWrap.classList.toggle('open');
    });
    document.addEventListener('click', function () {
      userWrap.classList.remove('open');
    });
  }

  /* ── Marcar link activo ── */
  const currentPath = window.location.pathname;
  document.querySelectorAll('.nav-link, .mobile-nav-list a').forEach(function (link) {
    if (link.getAttribute('href') && currentPath.includes(link.getAttribute('href').split('/').pop())) {
      link.classList.add('active');
    }
  });
})();