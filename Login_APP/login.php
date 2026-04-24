<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Remolinos Tours</title>
    <link rel="stylesheet" href="estilos_compartidos.css"> 
</head>
<body>

<div class="auth-card"> <div class="header">
        <div class="auth-badge">Acceso Clientes</div>
        <h2>Bienvenido de nuevo</h2>
        <p>Ingresa tus credenciales para continuar</p>
    </div>
    
    <div class="auth-divider"></div>

    <form action="../Login_API/validar.php" method="POST">
        <div class="form-group">
            <label>Correo Electrónico</label>
            <input type="email" name="email" required placeholder="tu@email.com">
        </div>

        <div class="form-group">
            <label>Contraseña</label>
            <div class="pass-wrapper"> <input type="password" name="pass" id="pass-login" required placeholder="••••••••">
                <button type="button" class="eye-toggle" data-target="pass-login" tabindex="-1">
                    </button>
            </div>
        </div>

        <button type="submit" class="btn-auth">Entrar a mi cuenta</button>
    </form>

    <div class="footer-link">
        ¿Aún no viajas con nosotros? <a href="registro.php">Regístrate aquí</a>
    </div>
</div>

<script src="validar.js"></script>
</body>
</html>