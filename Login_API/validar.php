<?php
session_start();
include('../Database/conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Limpieza de datos básica
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $pass  = isset($_POST['pass']) ? $_POST['pass'] : '';

    if (empty($email) || empty($pass)) {
        header("Location: ../Login_APP/login.php?error=vacio");
        exit();
    }

    // 2. CONSULTA PREPARADA: El estándar de oro contra SQL Injection
    // La base de datos recibe el "esqueleto" y luego los "datos" por separado
    $stmt = $conexion->prepare("SELECT id, nombre_completo, password, rol FROM usuarios WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email); // "s" de string
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        // 3. Verificación de hash de contraseña
        if (password_verify($pass, $fila['password'])) {
            
            // 4. SEGURIDAD EXTRA: Cambia la llave de la sesión al entrar
            session_regenerate_id(true);

            $_SESSION['id_usuario'] = $fila['id'];
            $_SESSION['nombre']     = $fila['nombre_completo'];
            $_SESSION['rol']        = $fila['rol'];

            // 5. Redirección por roles
            if ($fila['rol'] == 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../index.php");
            }
            exit();
        } else {
            // Contraseña mal: Mandamos código de error por URL
            header("Location: ../Login_APP/login.php?error=datos_incorrectos");
            exit();
        }
    } else {
        // Email no existe
        header("Location: ../Login_APP/login.php?error=datos_incorrectos");
        exit();
    }
    
    $stmt->close();
}

mysqli_close($conexion);
?>