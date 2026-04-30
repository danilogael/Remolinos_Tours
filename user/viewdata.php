<?php
// user/viewdata.php
include('../Database/conexion.php');
include('../Login_API/auth.php'); // ← importa requireAuth()

if (session_status() === PHP_SESSION_NONE) session_start();

// requireAuth redirige al admin si el rol es 'admin',
// y al login si no hay sesión
requireAuth($conexion);

$id  = (int)$_SESSION['id_usuario'];
$res = mysqli_query($conexion, "SELECT * FROM usuarios WHERE id = $id LIMIT 1");
$user = mysqli_fetch_assoc($res);

// Conteos
$totalReservas = mysqli_fetch_assoc(
    mysqli_query($conexion, "SELECT COUNT(*) AS t FROM reservas WHERE id_usuario=$id")
)['t'];

$reservasConfirmadas = mysqli_fetch_assoc(
    mysqli_query($conexion, "SELECT COUNT(*) AS t FROM reservas WHERE id_usuario=$id AND estado='Confirmada'")
)['t'];

// Reservas del usuario
$misReservas = mysqli_query($conexion,
    "SELECT r.*, d.nombre AS nombre_destino, d.foto_portada, d.punto_salida
     FROM reservas r
     LEFT JOIN destinos d ON r.id_destino = d.id
     WHERE r.id_usuario = $id
     ORDER BY r.created_at DESC"
);

// Fecha formateada
$fecha_formateada = '—';
if (!empty($user['fecha_nacimiento']) && $user['fecha_nacimiento'] !== '0000-00-00') {
    $fecha_formateada = date("d M, Y", strtotime($user['fecha_nacimiento']));
}

// Flash
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// Tab activo
$tab = $_GET['tab'] ?? 'datos';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil | Remolinos Tours</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/Agencia_Remolinos/componentes/header/header.css">
    <link rel="stylesheet" href="/Agencia_Remolinos/componentes/footer/footer.css">
    <link rel="stylesheet" href="/Agencia_Remolinos/style.css">
    <link rel="stylesheet" href="viewdata.css">
    <style>
        /* ── Modal ── */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.5);
            z-index: 9000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .modal-overlay.open { display: flex; }
        .modal-box {
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 25px 60px rgba(0,0,0,.2);
            position: relative;
            animation: slideUp .25s ease;
        }
        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to   { transform: translateY(0);    opacity: 1; }
        }
        .modal-close {
            position: absolute;
            top: 18px; right: 20px;
            background: none; border: none;
            font-size: 1.4rem; color: #a0aec0;
            cursor: pointer; transition: color .2s;
        }
        .modal-close:hover { color: #e53e3e; }
        .modal-box h3 { font-size: 1.8rem; color: #2d3748; margin: 0 0 24px; }
        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block; font-size: 1.2rem;
            color: #718096; margin-bottom: 6px; font-weight: 600;
        }
        .form-group input {
            width: 100%; padding: 11px 16px;
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            font-size: 1.4rem; color: #2d3748;
            transition: border-color .2s; box-sizing: border-box;
        }
        .form-group input:focus {
            outline: none; border-color: #1a56db;
            box-shadow: 0 0 0 3px rgba(26,86,219,.1);
        }
        .btn-guardar {
            width: 100%; padding: 13px;
            background: #1a56db; color: #fff;
            border: none; border-radius: 12px;
            font-size: 1.5rem; font-weight: 700;
            cursor: pointer; transition: background .2s, transform .15s;
        }
        .btn-guardar:hover { background: #0f3580; transform: translateY(-1px); }
        .pass-divider { border: none; border-top: 1px solid #edf2f7; margin: 22px 0 18px; }

        /* ── Flash ── */
        .perfil-alert { max-width: 1140px; margin: 0 auto 20px; padding: 0 20px; }
        .alert-box {
            padding: 14px 20px; border-radius: 12px;
            font-size: 1.4rem; display: flex; align-items: center; gap: 10px;
        }
        .alert-success { background: #f0fff4; color: #276749; border: 1px solid #c6f6d5; }
        .alert-error   { background: #fff5f5; color: #9b2c2c; border: 1px solid #fed7d7; }

        /* ── Tabla reservas ── */
        .reservas-table-wrap {
            background: #fff; border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,.05); overflow: hidden;
        }
        .reservas-table { width: 100%; border-collapse: collapse; font-size: 1.3rem; }
        .reservas-table thead th {
            background: #f8fafc; color: #a0aec0;
            text-transform: uppercase; font-size: 1.1rem;
            letter-spacing: .05em; padding: 14px 20px;
            text-align: left; border-bottom: 2px solid #edf2f7;
        }
        .reservas-table tbody tr:hover { background: #f8fafc; }
        .reservas-table td {
            padding: 16px 20px; color: #4a5568;
            border-bottom: 1px solid #f1f5f9; vertical-align: middle;
        }
        .reservas-table tbody tr:last-child td { border-bottom: none; }
        .dest-thumb { width: 56px; height: 40px; object-fit: cover; border-radius: 8px; }
        .badge-estado { display: inline-block; padding: 4px 12px; border-radius: 20px; font-weight: 700; font-size: 1.1rem; }
        .badge-pendiente  { background: #fef3c7; color: #92400e; }
        .badge-confirmada { background: #d1fae5; color: #065f46; }
        .badge-cancelada  { background: #fee2e2; color: #991b1b; }
        .empty-state { text-align: center; padding: 60px 20px; color: #a0aec0; }
        .empty-state i { font-size: 4rem; margin-bottom: 14px; display: block; }
        .empty-state p { font-size: 1.5rem; margin: 0; }

        /* ── Cambiar contraseña ── */
        .pass-section {
            background: #fff; border-radius: 20px;
            padding: 35px; box-shadow: 0 10px 30px rgba(0,0,0,.05);
        }
        .pass-section h4 { font-size: 1.8rem; color: #2d3748; margin: 0 0 6px; }
        .pass-section p  { color: #718096; font-size: 1.3rem; margin: 0 0 24px; }
    </style>
</head>
<body class="perfil-page">

    <?php include $_SERVER['DOCUMENT_ROOT'] . "/Agencia_Remolinos/componentes/header/header.php"; ?>

    <div class="perfil-wrapper">
        <div class="perfil-hero-bg"></div>

        <?php if ($flash): ?>
        <div class="perfil-alert">
            <div class="alert-box alert-<?= $flash['tipo'] ?>">
                <i class="fas fa-<?= $flash['tipo'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                <?= htmlspecialchars($flash['msg']) ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="perfil-container">

            <!-- SIDEBAR -->
            <aside class="perfil-sidebar">
                <div class="user-card-top">
                    <div class="avatar-wrapper">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['nombre_completo']) ?>&background=1a56db&color=fff&size=128"
                             alt="Avatar">
                    </div>
                    <h2 class="user-name"><?= htmlspecialchars(explode(' ', $user['nombre_completo'])[0]) ?></h2>
                    <p class="user-rank"><?= $reservasConfirmadas >= 5 ? 'Viajero Frecuente' : 'Miembro Explorer' ?></p>
                </div>

                <nav class="perfil-nav">
                    <a href="?tab=datos"    class="nav-item <?= $tab==='datos'    ? 'active':'' ?>">
                        <i class="fas fa-id-card"></i> Mis Datos
                    </a>
                    <a href="?tab=reservas" class="nav-item <?= $tab==='reservas' ? 'active':'' ?>">
                        <i class="fas fa-suitcase-rolling"></i> Mis Reservas
                        <?php if ($totalReservas > 0): ?>
                        <span style="margin-left:auto;background:#1a56db;color:#fff;font-size:1rem;padding:2px 8px;border-radius:20px;">
                            <?= $totalReservas ?>
                        </span>
                        <?php endif; ?>
                    </a>
                    <a href="?tab=password" class="nav-item <?= $tab==='password' ? 'active':'' ?>">
                        <i class="fas fa-lock"></i> Cambiar Contraseña
                    </a>
                    <div class="nav-spacer"></div>
                    <a href="../Login_API/logout.php" class="nav-item logout">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </a>
                </nav>
            </aside>

            <!-- CONTENIDO -->
            <main class="perfil-content">

                <?php if ($tab === 'datos'): ?>
                <section class="details-card">
                    <div class="card-header">
                        <h3>Información de la Cuenta</h3>
                        <button class="edit-profile-btn" id="btnEditarPerfil">
                            <i class="fas fa-pen"></i> Editar Perfil
                        </button>
                    </div>
                    <div class="details-grid">
                        <div class="detail-box">
                            <span class="label">Nombre Completo</span>
                            <p class="value"><?= htmlspecialchars($user['nombre_completo']) ?></p>
                        </div>
                        <div class="detail-box">
                            <span class="label">Correo Electrónico</span>
                            <p class="value"><?= htmlspecialchars($user['email']) ?></p>
                        </div>
                        <div class="detail-box">
                            <span class="label">Teléfono</span>
                            <p class="value"><?= htmlspecialchars($user['telefono'] ?: 'No registrado') ?></p>
                        </div>
                        <div class="detail-box">
                            <span class="label">Fecha de Nacimiento</span>
                            <p class="value"><?= $fecha_formateada ?></p>
                        </div>
                        <div class="detail-box">
                            <span class="label">Miembro desde</span>
                            <p class="value">
                                <?= !empty($user['fecha_registro']) ? date('d M, Y', strtotime($user['fecha_registro'])) : '—' ?>
                            </p>
                        </div>
                        <div class="detail-box">
                            <span class="label">Tipo de cuenta</span>
                            <p class="value" style="text-transform:capitalize;"><?= htmlspecialchars($user['rol']) ?></p>
                        </div>
                    </div>
                </section>

                <?php elseif ($tab === 'reservas'): ?>
                <div class="reservas-table-wrap">
                    <div style="padding:28px 30px 16px;border-bottom:1px solid #f1f5f9;">
                        <h3 style="margin:0;font-size:2rem;color:#2d3748;">Mis Reservas</h3>
                        <p style="margin:4px 0 0;color:#718096;font-size:1.3rem;">Historial completo de tus viajes</p>
                    </div>
                    <?php if (mysqli_num_rows($misReservas) === 0): ?>
                    <div class="empty-state">
                        <i class="fas fa-suitcase-rolling"></i>
                        <p>Aún no tienes reservas.</p>
                        <a href="/Agencia_Remolinos/componentes/Reserva/reserva.php"
                           style="display:inline-block;margin-top:16px;padding:12px 28px;background:#1a56db;color:#fff;border-radius:12px;text-decoration:none;font-weight:700;font-size:1.4rem;">
                            ¡Reserva tu primer viaje!
                        </a>
                    </div>
                    <?php else: ?>
                    <div style="overflow-x:auto;">
                        <table class="reservas-table">
                            <thead>
                                <tr>
                                    <th>Folio</th><th>Destino</th><th>Pasajeros</th>
                                    <th>Total</th><th>Fecha salida</th><th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php while ($r = mysqli_fetch_assoc($misReservas)):
                                $est = strtolower($r['estado'] ?? 'pendiente');
                                $badgeCls = $est==='confirmada' ? 'badge-confirmada' : ($est==='cancelada' ? 'badge-cancelada' : 'badge-pendiente');
                            ?>
                            <tr>
                                <td><strong style="color:#2d3748;"><?= htmlspecialchars($r['folio'] ?? '#'.$r['id']) ?></strong></td>
                                <td>
                                        <div>
                                            <div style="font-weight:600;color:#2d3748;"><?= htmlspecialchars($r['nombre_destino'] ?? '—') ?></div>
                                            <small style="color:#a0aec0;"><?= htmlspecialchars($r['punto_salida'] ?? '') ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <i class="fas fa-user" style="color:#cbd5e0;margin-right:4px;"></i><?= (int)($r['adultos'] ?? 0) ?>
                                    &nbsp;<i class="fas fa-child" style="color:#cbd5e0;margin-right:4px;"></i><?= (int)($r['ninos'] ?? 0) ?>
                                </td>
                                <td style="font-weight:700;color:#1a56db;">$<?= number_format((float)($r['total_pago'] ?? 0), 2) ?></td>
                                <td style="color:#718096;"><?= !empty($r['fecha_salida']) ? date('d/m/Y', strtotime($r['fecha_salida'])) : '—' ?></td>
                                <td><span class="badge-estado <?= $badgeCls ?>"><?= htmlspecialchars(ucfirst($r['estado'] ?? 'Pendiente')) ?></span></td>
                            </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>

                <?php elseif ($tab === 'password'): ?>
                <div class="pass-section">
                    <h4>Cambiar Contraseña</h4>
                    <p>Elige una contraseña segura de al menos 6 caracteres.</p>
                    <form method="POST" action="actualizar_perfil.php" novalidate>
                        <input type="hidden" name="accion" value="password">
                        <div class="form-group">
                            <label>Contraseña actual</label>
                            <input type="password" name="pass_actual" placeholder="••••••••" required>
                        </div>
                        <div class="form-group">
                            <label>Nueva contraseña</label>
                            <input type="password" name="pass_nueva" placeholder="••••••••" required>
                        </div>
                        <div class="form-group">
                            <label>Confirmar nueva contraseña</label>
                            <input type="password" name="pass_confirmar" placeholder="••••••••" required>
                        </div>
                        <button type="submit" class="btn-guardar" style="width:auto;padding:12px 32px;">
                            <i class="fas fa-lock"></i> Actualizar Contraseña
                        </button>
                    </form>
                </div>
                <?php endif; ?>

            </main>
        </div>
    </div>

    <!-- MODAL EDITAR PERFIL -->
    <div class="modal-overlay" id="modalEditar">
        <div class="modal-box">
            <button class="modal-close" id="cerrarModal">&times;</button>
            <h3><i class="fas fa-pen" style="color:#1a56db;margin-right:10px;"></i>Editar Perfil</h3>
            <form method="POST" action="actualizar_perfil.php" novalidate>
                <input type="hidden" name="accion" value="perfil">
                <div class="form-group">
                    <label>Nombre Completo</label>
                    <input type="text" name="nombre" value="<?= htmlspecialchars($user['nombre_completo']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Correo Electrónico</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                    <small style="color:#a0aec0;font-size:1.1rem;">
                        <i class="fas fa-info-circle" style="margin-right:4px;"></i>
                        Si cambias el correo necesitarás usarlo para iniciar sesión.
                    </small>
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" value="<?= htmlspecialchars($user['telefono'] ?? '') ?>" placeholder="449-000-0000">
                </div>
                <div class="form-group">
                    <label>Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nac" value="<?= htmlspecialchars($user['fecha_nacimiento'] ?? '') ?>">
                </div>
                <hr class="pass-divider">
                <div class="form-group">
                    <label>
                        Contraseña actual
                        <small style="color:#a0aec0;font-weight:400;"> — requerida para guardar cambios</small>
                    </label>
                    <input type="password" name="pass_confirmar_cambio" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn-guardar">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </form>
        </div>
    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . "/Agencia_Remolinos/componentes/footer/footer.php"; ?>

    <script>
        const modal    = document.getElementById('modalEditar');
        const btnAbrir = document.getElementById('btnEditarPerfil');
        const btnCerrar= document.getElementById('cerrarModal');
        if (btnAbrir)  btnAbrir.addEventListener('click',  () => modal.classList.add('open'));
        if (btnCerrar) btnCerrar.addEventListener('click', () => modal.classList.remove('open'));
        modal.addEventListener('click', e => { if (e.target === modal) modal.classList.remove('open'); });
    </script>
</body>
</html>
