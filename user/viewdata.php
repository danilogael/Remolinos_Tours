<?php
// 1. Conexión a la base de datos
include('../Database/conexion.php');

// 2. Iniciar sesión y validar seguridad
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../Login_APP/login.php");
    exit();
}

$id = $_SESSION['id_usuario'];
$res = mysqli_query($conexion, "SELECT * FROM usuarios WHERE id = '$id'");
$user = mysqli_fetch_assoc($res);

// Formatear la fecha
$fecha_formateada = date("d M, Y", strtotime($user['fecha_nacimiento']));
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
</head>
<body class="perfil-page">

    <?php include $_SERVER['DOCUMENT_ROOT'] . "/Agencia_Remolinos/componentes/header/header.php"; ?>

    <div class="perfil-wrapper">
        <div class="perfil-hero-bg"></div>
        
        <div class="perfil-container">
            <aside class="perfil-sidebar">
                <div class="user-card-top">
                    <div class="avatar-wrapper">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['nombre_completo']); ?>&background=1a56db&color=fff&size=128" alt="Avatar">
                    </div>
                    <h2 class="user-name"><?php echo explode(' ', $user['nombre_completo'])[0]; ?></h2>
                    <p class="user-rank">Miembro Explorer</p>
                </div>
                
                <nav class="perfil-nav">
                    <a href="#" class="nav-item active"><i class="fas fa-id-card"></i> Mis Datos</a>
                    <a href="#" class="nav-item"><i class="fas fa-suitcase-rolling"></i> Mis Reservas</a>
                    <a href="#" class="nav-item"><i class="fas fa-heart"></i> Mis Favoritos</a>
                    <div class="nav-spacer"></div>
                    <a href="../Login_API/logout.php" class="nav-item logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
                </nav>
            </aside>

            <main class="perfil-content">
                <section class="details-card">
                    <div class="card-header">
                        <h3>Información de la Cuenta</h3>
                        <button class="edit-profile-btn"><i class="fas fa-pen"></i> Editar Perfil</button>
                    </div>
                    
                    <div class="details-grid">
                        <div class="detail-box">
                            <span class="label">Nombre Completo</span>
                            <p class="value"><?php echo $user['nombre_completo']; ?></p>
                        </div>
                        <div class="detail-box">
                            <span class="label">Correo Electrónico</span>
                            <p class="value"><?php echo $user['email']; ?></p>
                        </div>
                        <div class="detail-box">
                            <span class="label">Teléfono</span>
                            <p class="value"><?php echo $user['telefono'] ?: 'No asignado'; ?></p>
                        </div>
                        <div class="detail-box">
                            <span class="label">Fecha de Nacimiento</span>
                            <p class="value"><?php echo $fecha_formateada; ?></p>
                        </div>
                    </div>
                </section>

                <div class="perfil-stats">
                    <div class="stat-item">
                        <span class="stat-val">0</span>
                        <span class="stat-lab">Tours Tomados</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-val">0</span>
                        <span class="stat-lab">Reseñas</span>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>