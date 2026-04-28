<?php
// admin/includes/header.php
// Variables: $tituloPagina, $paginaActual
$tituloPagina = $tituloPagina ?? 'Panel Admin';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tituloPagina) ?> — Remolino's Tours</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-w: 240px;
            --sidebar-bg: #0f172a;
            --topbar-h: 62px;
            --accent: #6366f1;
            --accent-hover: #4f46e5;
            --body-bg: #f1f5f9;
        }

        * { box-sizing: border-box; }

        body {
            background: var(--body-bg);
            font-family: 'Segoe UI', system-ui, sans-serif;
            margin: 0;
        }

        /* ── SIDEBAR ── */
        #sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0;
            z-index: 1030;
            overflow-y: auto;
            transition: transform .25s ease;
        }

        .sidebar-brand { color: #e2e8f0; }
        .sidebar-brand:hover { background: rgba(255,255,255,.06); border-radius: .5rem; }

        .sidebar-divider { border-color: rgba(255,255,255,.1); }

        .sidebar-icon { width: 18px; text-align: center; }

        #sidebar .nav-link {
            border-radius: .5rem;
            font-size: .875rem;
            padding: .5rem .75rem;
            transition: background .15s, color .15s;
        }
        #sidebar .nav-link:hover { background: rgba(255,255,255,.08); color: #fff !important; }
        #sidebar .nav-link.active { background: var(--accent); color: #fff !important; }

        /* ── TOPBAR ── */
        #topbar {
            height: var(--topbar-h);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            z-index: 1020;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            gap: 1rem;
            box-shadow: 0 1px 4px rgba(0,0,0,.05);
        }

        /* ── MAIN ── */
        #content {
            margin-left: var(--sidebar-w);
            padding-top: var(--topbar-h);
            min-height: 100vh;
        }

        .page-body { padding: 1.75rem; }

        /* ── CARDS ── */
        .stat-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,.07);
            transition: transform .2s, box-shadow .2s;
            overflow: hidden;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 6px 16px rgba(0,0,0,.12); }

        .stat-icon {
            width: 50px; height: 50px;
            border-radius: .75rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
        }

        /* ── TABLES ── */
        .panel-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,.07);
            overflow: hidden;
        }
        .table thead th {
            background: #f8fafc;
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #64748b;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
        }
        .table-hover tbody tr:hover { background: #f8fafc; }

        /* ── BADGE ESTADO ── */
        .badge-pendiente  { background: #fef3c7; color: #92400e; }
        .badge-confirmada { background: #d1fae5; color: #065f46; }
        .badge-cancelada  { background: #fee2e2; color: #991b1b; }

        /* ── ALERTS ── */
        .alert { border-radius: .75rem; border: none; }

        /* ── BOTONES ── */
        .btn-primary { background: var(--accent); border-color: var(--accent); }
        .btn-primary:hover { background: var(--accent-hover); border-color: var(--accent-hover); }

        /* ── MOBILE ── */
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
            #topbar, #content { left: 0; margin-left: 0; }
        }
    </style>
</head>
<body>

<?php require_once __DIR__ . '/sidebar.php'; ?>

<!-- TOPBAR -->
<header id="topbar">
    <button class="btn btn-sm btn-light d-md-none" id="sidebarToggle">
        <i class="fa-solid fa-bars"></i>
    </button>
    <h5 class="mb-0 fw-semibold flex-grow-1"><?= htmlspecialchars($tituloPagina) ?></h5>

    <span class="badge text-bg-light border fw-normal">
        <i class="fa-solid fa-circle-check text-success me-1"></i>Admin
    </span>
</header>

<!-- CONTENIDO PRINCIPAL -->
<div id="content">
<div class="page-body">
