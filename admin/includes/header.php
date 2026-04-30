<?php
// admin/includes/header.php
$tituloPagina = $tituloPagina ?? "Panel Admin";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tituloPagina) ?> — Remolino's Tours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-w: 240px; --sidebar-bg: #0f172a;
            --topbar-h: 62px;   --accent: #6366f1; --accent-dark: #4f46e5;
            --body-bg: #f1f5f9; --card-radius: 1rem;
            --shadow-sm: 0 2px 8px rgba(0,0,0,.07);
            --shadow-md: 0 6px 20px rgba(0,0,0,.10);
        }
        *, *::before, *::after { box-sizing: border-box; }
        body { background: var(--body-bg); font-family: "Segoe UI", system-ui, sans-serif; margin: 0; font-size: .875rem; }

        /* SIDEBAR */
        #sidebar {
            width: var(--sidebar-w); min-height: 100vh; background: var(--sidebar-bg);
            position: fixed; top: 0; left: 0; z-index: 1030;
            overflow-y: auto; overflow-x: hidden; transition: transform .25s ease;
            display: flex; flex-direction: column;
        }
        .sidebar-brand { color: #e2e8f0; padding: 20px 16px; display: flex; align-items: center; gap: 10px; text-decoration: none; border-bottom: 1px solid rgba(255,255,255,.08); }
        .sidebar-brand:hover { background: rgba(255,255,255,.05); color: #fff; }
        .sidebar-brand .brand-icon { color: var(--accent); font-size: 1.5rem; }
        .sidebar-brand .brand-text .name { font-weight: 700; font-size: .95rem; color: #f1f5f9; }
        .sidebar-brand .brand-text .sub  { font-size: .72rem; color: #64748b; }
        .sidebar-user { padding: 14px 16px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid rgba(255,255,255,.08); }
        .sidebar-user-avatar { width: 34px; height: 34px; background: var(--accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #fff; font-size: .85rem; flex-shrink: 0; }
        .sidebar-user-info .user-name { font-size: .8rem; font-weight: 600; color: #e2e8f0; max-width: 155px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .sidebar-user-info .user-role  { font-size: .68rem; color: #22c55e; }
        .sidebar-nav { flex: 1; padding: 10px; display: flex; flex-direction: column; gap: 2px; }
        .sidebar-nav .nav-label { font-size: .65rem; text-transform: uppercase; letter-spacing: .08em; color: #475569; padding: 10px 8px 4px; font-weight: 700; }
        .nav-link-item { display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 8px; color: #94a3b8; text-decoration: none; font-size: .85rem; font-weight: 500; transition: background .15s, color .15s; }
        .nav-link-item:hover  { background: rgba(255,255,255,.07); color: #e2e8f0; }
        .nav-link-item.active { background: var(--accent); color: #fff; }
        .nav-link-item .nav-icon { width: 18px; text-align: center; font-size: .95rem; flex-shrink: 0; }
        .sidebar-footer { padding: 10px; border-top: 1px solid rgba(255,255,255,.08); }
        .sidebar-footer a { display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 8px; color: #f87171; text-decoration: none; font-size: .85rem; font-weight: 500; transition: background .15s; }
        .sidebar-footer a:hover { background: rgba(248,113,113,.1); }

        /* TOPBAR */
        #topbar { height: var(--topbar-h); background: #fff; border-bottom: 1px solid #e2e8f0; position: fixed; top: 0; left: var(--sidebar-w); right: 0; z-index: 1020; display: flex; align-items: center; padding: 0 1.5rem; gap: 1rem; box-shadow: 0 1px 4px rgba(0,0,0,.05); }
        .topbar-title { flex: 1; font-size: 1rem; font-weight: 600; color: #0f172a; margin: 0; }
        .topbar-badge { display: inline-flex; align-items: center; gap: 6px; background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; border-radius: 50px; padding: 4px 12px; font-size: .78rem; font-weight: 600; }
        .topbar-badge i { color: #22c55e; font-size: .7rem; }

        /* MAIN */
        #content { margin-left: var(--sidebar-w); padding-top: var(--topbar-h); min-height: 100vh; }
        .page-body { padding: 1.75rem; }

        /* STAT CARDS */
        .stat-card { background: #fff; border: none; border-radius: var(--card-radius); box-shadow: var(--shadow-sm); transition: transform .2s, box-shadow .2s; overflow: hidden; }
        .stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }
        .stat-icon { width: 48px; height: 48px; border-radius: .75rem; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; }

        /* PANEL CARDS */
        .panel-card { background: #fff; border: none; border-radius: var(--card-radius); box-shadow: var(--shadow-sm); overflow: hidden; }

        /* TABLAS */
        .table { font-size: .85rem; margin: 0; }
        .table thead th { background: #f8fafc; font-size: .72rem; text-transform: uppercase; letter-spacing: .06em; color: #64748b; border-bottom: 2px solid #e2e8f0; white-space: nowrap; padding: .85rem 1rem; font-weight: 700; }
        .table tbody td { padding: .85rem 1rem; vertical-align: middle; border-color: #f1f5f9; }
        .table-hover tbody tr:hover { background: #fafafa; }
        .table tbody tr:last-child td { border-bottom: none; }

        /* BADGES */
        .badge { font-size: .72rem; padding: .35em .75em; border-radius: .4rem; font-weight: 600; }
        .badge-pendiente  { background: #fef3c7 !important; color: #92400e !important; }
        .badge-confirmada { background: #d1fae5 !important; color: #065f46 !important; }
        .badge-cancelada  { background: #fee2e2 !important; color: #991b1b !important; }
        .badge-activo     { background: #d1fae5 !important; color: #065f46 !important; }
        .badge-inactivo   { background: #f1f5f9 !important; color: #475569 !important; }

        /* BOTONES */
        .btn-primary   { background: var(--accent); border-color: var(--accent); }
        .btn-primary:hover { background: var(--accent-dark); border-color: var(--accent-dark); }
        .btn-sm { font-size: .8rem; }

        /* FORMS */
        .form-control:focus, .form-select:focus { border-color: var(--accent); box-shadow: 0 0 0 .2rem rgba(99,102,241,.15); }
        .form-label { font-weight: 600; font-size: .82rem; color: #374151; margin-bottom: .35rem; }

        /* ALERTS */
        .alert { border-radius: .75rem; border: none; font-size: .875rem; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #f0fdf4; color: #166534; }
        .alert-danger  { background: #fef2f2; color: #991b1b; }
        .alert-warning { background: #fffbeb; color: #92400e; }

        /* ENCABEZADOS */
        .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: .75rem; }
        .page-header h4 { font-size: 1.15rem; font-weight: 700; color: #0f172a; margin: 0; }
        .page-header p  { font-size: .8rem; color: #64748b; margin: 3px 0 0; }
        .back-link { display: inline-flex; align-items: center; gap: 6px; color: #64748b; text-decoration: none; font-size: .82rem; margin-bottom: .5rem; transition: color .15s; }
        .back-link:hover { color: var(--accent); }

        /* EMPTY STATE */
        .empty-row td { text-align: center; padding: 3rem 1rem !important; color: #94a3b8; }
        .empty-row i  { font-size: 2.5rem; display: block; margin-bottom: .75rem; }

        /* MOBILE */
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
            #topbar, #content { left: 0; margin-left: 0; }
            .page-body { padding: 1rem; }
        }
    </style>
</head>
<body>

<?php require_once __DIR__ . "/sidebar.php"; ?>

<header id="topbar">
    <button class="btn btn-sm btn-light border d-md-none" id="sidebarToggle">
        <i class="fa-solid fa-bars"></i>
    </button>
    <h5 class="topbar-title"><?= htmlspecialchars($tituloPagina) ?></h5>
    <span class="topbar-badge">
        <i class="fa-solid fa-circle"></i>
        <?= htmlspecialchars($_SESSION["nombre"] ?? "Administrador") ?>
    </span>
</header>

<div id="content">
<div class="page-body">
