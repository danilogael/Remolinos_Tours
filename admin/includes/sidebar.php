<?php
// admin/includes/sidebar.php
// Variable requerida: $paginaActual (string)
$paginaActual = $paginaActual ?? '';

$menu = [
    'dashboard'   => ['icono' => 'fa-chart-line',        'label' => 'Dashboard',         'url' => 'index.php'],
    'usuarios'    => ['icono' => 'fa-users',              'label' => 'Usuarios',          'url' => 'usuarios.php'],
    'destinos'    => ['icono' => 'fa-earth-americas',     'label' => 'Destinos',          'url' => 'destinos.php'],
    'reservas'    => ['icono' => 'fa-clipboard-list',     'label' => 'Reservas',          'url' => 'reservas.php'],
    'proveedores' => ['icono' => 'fa-plane',              'label' => 'Aerolíneas/Socios', 'url' => 'proveedores.php'],
];
?>
<div id="sidebar" class="d-flex flex-column p-0">
    <!-- Brand -->
    <a href="index.php" class="sidebar-brand d-flex align-items-center gap-2 px-4 py-4 text-decoration-none">
        <i class="fa-solid fa-tornado fs-4 brand-icon"></i>
        <div class="lh-sm">
            <div class="fw-bold">Remolino's</div>
            <small class="opacity-75 fw-normal" style="font-size:.75rem;">Tours Admin</small>
        </div>
    </a>

    <hr class="sidebar-divider mx-3 my-0">

    <!-- Sesión activa -->
    <div class="px-4 py-3 d-flex align-items-center gap-2">
        <div class="user-avatar-sm d-flex align-items-center justify-content-center rounded-circle fw-bold"
             style="width:34px;height:34px;background:#6366f1;color:#fff;font-size:.85rem;">
            <?= strtoupper(mb_substr($_SESSION['nombre'] ?? 'A', 0, 1)) ?>
        </div>
        <div class="lh-sm">
            <div class="text-white small fw-medium" style="max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                <?= htmlspecialchars($_SESSION['nombre'] ?? 'Administrador') ?>
            </div>
            <small class="text-success" style="font-size:.7rem;">● Admin</small>
        </div>
    </div>

    <hr class="sidebar-divider mx-3 my-0">

    <!-- Navegación -->
    <ul class="nav nav-pills flex-column px-3 py-2 gap-1 flex-grow-1">
        <?php foreach ($menu as $key => $item): ?>
        <li class="nav-item">
            <a href="<?= $item['url'] ?>"
               class="nav-link d-flex align-items-center gap-2 <?= $paginaActual === $key ? 'active' : 'text-white-50' ?>">
                <i class="fa-solid <?= $item['icono'] ?> sidebar-icon"></i>
                <span><?= $item['label'] ?></span>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>

    <hr class="sidebar-divider mx-3 my-0">

    <!-- Links del pie -->
    <div class="px-3 py-3 d-flex flex-column gap-1">
        <a href="../index.php" class="nav-link d-flex align-items-center gap-2 text-white-50 text-decoration-none">
            <i class="fa-solid fa-globe sidebar-icon"></i>
            <span>Ver Sitio Web</span>
        </a>
        <a href="logout.php" class="nav-link d-flex align-items-center gap-2 text-danger text-decoration-none">
            <i class="fa-solid fa-right-from-bracket sidebar-icon"></i>
            <span>Cerrar sesión</span>
        </a>
    </div>
</div>
