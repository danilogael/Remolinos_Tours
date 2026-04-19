<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="sign.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>
<body>

<div class="google-container">

    <div class="google-card">

        <!-- LADO IZQUIERDO -->
        <div class="google-left">
            <img src="/viaje/viaje/Viaje-APP/imagenes/Logo.png" class="logo">

            <h1 class="title">Inicia sesión</h1>
            <p class="subtitle">Usa tu cuenta de Remolinos Tours</p>
        </div>

        <!-- LADO DERECHO (FORMULARIO) -->
        <div class="google-right">
            <form id="loginForm">

                <div class="input-container">
                    <input type="email" id="email" name="correo" required>
                    <label for="email">Correo electrónico</label>
                </div>

              <div class="input-container">
    <input type="password" id="password" name="contraseña" placeholder=" " required>
    <label for="password">Contraseña</label>
    <i class="fas fa-eye toggle-password"></i> <!-- ojo de FontAwesome -->
</div>



                <div class="links">
                    <a href="#">¿Has olvidado tu correo?</a>
                </div>

                <div class="buttons">
                    <a href="/viaje/viaje/Viaje-APP/componentes/Register/Register.php" class="create-account">
                        Crear cuenta
                    </a>

                    <button type="submit" class="next-btn">Siguiente</button>
                </div>
            </form>
        </div>

    </div>
</div>

<script src="sign.js"></script>

</body>
</html>
