<?php
// admin/includes/sidebar.php
$paginaActual = $paginaActual ?? '';
$menu = [
    'dashboard'   => ['icono'=>'fa-chart-line',     'label'=>'Dashboard',         'url'=>'index.php'],
    'usuarios'    => ['icono'=>'fa-users',           'label'=>'Usuarios',          'url'=>'usuarios.php'],
    'destinos'    => ['icono'=>'fa-earth-americas',  'label'=>'Destinos',          'url'=>'destinos.php'],
    'reservas'    => ['icono'=>'fa-clipboard-list',  'label'=>'Reservas',          'url'=>'reservas.php'],
    'proveedores' => ['icono'=>'fa-plane',           'label'=>'Aerolíneas/Socios', 'url'=>'proveedores.php'],
];
?>
<div id="sidebar">
    <!-- Brand -->
    <a href="index.php" class="sidebar-brand">
        <i class="fa-solid fa-tornado brand-icon"></i>
        <div class="brand-text">
            <div class="name">Remolino's Tours</div>
            <div class="sub">Panel Admin</div>
        </div>
    </a>

    <!-- Usuario activo -->
    <div class="sidebar-user">
        <div class="sidebar-user-avatar">
            <?= strtoupper(mb_substr($_SESSION['nombre'] ?? 'A', 0, 1)) ?>
        </div>
        <div class="sidebar-user-info">
            <div class="user-name"><?= htmlspecialchars($_SESSION['nombre'] ?? 'Admin') ?></div>
            <div class="user-role">● Administrador</div>
        </div>
    </div>

    <!-- Navegación -->
    <nav class="sidebar-nav">
        <div class="nav-label">Menú principal</div>
        <?php foreach ($menu as $key => $item): ?>
        <a href="<?= $item['url'] ?>"
           class="nav-link-item <?= $paginaActual === $key ? 'active' : '' ?>">
            <i class="fa-solid <?= $item['icono'] ?> nav-icon"></i>
            <span><?= $item['label'] ?></span>
        </a>
        <?php endforeach; ?>

        <div class="nav-label" style="margin-top:10px;">Accesos</div>
        <a href="../index.php" class="nav-link-item">
            <i class="fa-solid fa-globe nav-icon"></i>
            <span>Ver sitio web</span>
        </a>
    </nav>

    <!-- Logout -->
    <div class="sidebar-footer">
        <a href="logout.php">
            <i class="fa-solid fa-right-from-bracket nav-icon"></i>
            <span>Cerrar sesión</span>
        </a>
    </div>
</div>
