<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | Remolinos Tours</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23/build/css/intlTelInput.css">
    <link rel="stylesheet" href="estilos_compartidos.css">
</head>
<body>

<div class="auth-card registro"> <div class="header">
        <h2>Únete a la Aventura</h2>
        <p>Crea tu cuenta en Remolinos Tours</p>
    </div>
    <div class="auth-divider"></div>

    <form id="registroForm" action="../Login_API/registrar.php" method="POST">
    <div class="form-grid">
        <div class="form-group full-width">
            <label>Nombre Completo</label>
            <input type="text" name="nombre" id="nombre" required placeholder="Tu nombre completo">
        </div>

        <div class="form-group">
            <label>Correo Electrónico</label>
            <input type="email" name="email" id="email" required placeholder="tu@email.com">
        </div>

        <div class="form-group">
            <label>WhatsApp / Teléfono</label>
            <input type="tel" name="telefono" id="telefono" required>
        </div>

        <div class="form-group full-width">
            <label>Fecha de Nacimiento</label>
            <input type="date" name="fecha_nac" id="fecha_nac" required>
        </div>

        <div class="form-group full-width">
            <label>Contraseña</label>
            <div class="pass-wrapper">
                <input type="password" name="pass" id="pass" required placeholder="Crea una clave segura">
                <button type="button" class="eye-toggle" data-target="pass" tabindex="-1"></button>
            </div>
            
            <div class="strength-bar">
                <div id="seg1" class="strength-seg"></div>
                <div id="seg2" class="strength-seg"></div>
                <div id="seg3" class="strength-seg"></div>
            </div>

            <div class="horizontal-hints">
                <span id="hint-len" class="hint-inline">8+ caracteres</span>
                <span class="hint-divider"></span>
                <span id="hint-up" class="hint-inline">ABC / 123</span>
                <span class="hint-divider"></span>
                <span id="hint-sp" class="hint-inline">Símbolo (@#!)</span>
            </div>
        </div>
    </div>

    <button type="submit" class="btn-auth">Registrarme ahora</button>
</form>
    <div class="footer-link">
        ¿Ya tienes cuenta? <a href="login.php">Inicia Sesión</a>
    </div>
</div>

<script src="validar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23/build/js/intlTelInput.min.js"></script>
<script>
    const inputTel = document.querySelector("#telefono");
    const iti = window.intlTelInput(inputTel, {
        initialCountry: "mx",
        preferredCountries: ["mx", "us", "es"],
        separateDialCode: true,
        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23/build/js/utils.js"
    });

    document.querySelector("#registroForm").addEventListener("submit", function (e) {
        if (!iti.isValidNumber()) {
            e.preventDefault();
            inputTel.style.borderColor = "#f87171";
            inputTel.focus();
            alert("Por favor ingresa un número de teléfono válido.");
            return;
        }
        inputTel.value = iti.getNumber();
    })
    // Calculamos la fecha máxima permitida (Hoy menos 18 años)
    const fechaInput = document.getElementById('fecha_nac');
    const hoy = new Date();
    const hace18 = new Date(hoy.getFullYear() - 18, hoy.getMonth(), hoy.getDate());
    
    // Formateamos a YYYY-MM-DD que es lo que entiende el input date
    const fechaLimite = hace18.toISOString().split('T')[0];
    
    // Aplicamos el límite al calendario
    fechaInput.setAttribute('max', fechaLimite);

</script>
</body>
</html>