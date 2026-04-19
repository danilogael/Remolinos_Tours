<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
  <link rel="stylesheet" href="Register.css">
</head>
<body>

<div class="google-container">

  <div class="google-left">
    <img src="/viaje/viaje/Viaje-APP/imagenes/Logo.png" class="logo">
    <h1 class="title">¡Bienvenido!</h1>
    <p class="subtitle">Regístrate para acceder a todas las funciones y disfrutar de nuestra plataforma.</p>
  </div>

  
  <div class="google-right">
    <form id="registerForm">

      <div class="input-container">
        <input type="text" id="nombre" name="nombre" placeholder=" " required>
        <label for="nombre">Nombre</label>
      </div>

      <div class="input-container">
        <input type="text" id="apellido_paterno" name="apellido_paterno" placeholder=" " required>
        <label for="apellido_paterno">Apellido Paterno</label>
      </div>

      <div class="input-container">
        <input type="text" id="apellido_materno" name="apellido_materno" placeholder=" " required>
        <label for="apellido_materno">Apellido Materno</label>
      </div>

      <div class="input-container">
        <input type="email" id="correo" name="correo" placeholder=" " required>
        <label for="correo">Correo electrónico</label>
      </div>

    
      <div class="input-container phone-container">
        <input type="tel" id="telefono" name="telefono" placeholder=" " required>
      </div>

      <div class="input-container">
        <input type="password" class="password" name="contraseña" placeholder=" " required minlength="6">
        <label>Contraseña</label>
        <i class="fas fa-eye toggle-password"></i>
      </div>

      <div class="input-container">
        <input type="password" class="password" name="confirmar_contraseña" placeholder=" " required minlength="6">
        <label>Confirmar Contraseña</label>
        <i class="fas fa-eye toggle-password"></i>
      </div>

      <div id="password-info">
        <span id="charLength">6+ caracteres</span> |
        <span id="uppercase">Mayúscula</span> |
        <span id="lowercase">Minúscula</span> |
        <span id="number">Número</span> |
        <span id="special">Carácter especial</span>
      </div>

      <div class="buttons">
        <button type="submit" class="next-btn">Registrarse</button>
      </div>

      <div class="links">
        <p>¿Ya tienes cuenta? <a href="/viaje/viaje/Viaje-APP/componentes/iniciarsesion/sign.php">Inicia sesión aquí</a></p>
      </div>

    </form>
  </div>
</div>

<script src="Register.js"></script>
</body>
</html>
