<?php
// 1. Conexión y sesión
include('../Database/conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Limpiamos espacios en blanco pero no necesitamos escape_string aquí 
    // porque bind_param se encarga de la seguridad.
    $nombre    = trim($_POST['nombre']);
    $email     = trim($_POST['email']);
    $telefono  = trim($_POST['telefono']);
    $fecha_nac = $_POST['fecha_nac'];
    $password  = $_POST['pass'];

    // 2. Encriptamos la contraseña (esto ya lo hacías bien, ¡perfecto!)
    $pass_encriptada = password_hash($password, PASSWORD_DEFAULT);

    // 3. Verificamos si el correo ya existe usando SENTENCIA PREPARADA
    $stmt_check = $conexion->prepare("SELECT id FROM usuarios WHERE email = ? LIMIT 1");
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $resultado_check = $stmt_check->get_result();

    if ($resultado_check->num_rows > 0) {
        echo "<script>
                alert('Este correo ya está registrado. Intenta con otro.');
                window.location.href='../Login_APP/registro.php';
              </script>";
        $stmt_check->close();
        exit();
    }
    $stmt_check->close();

    // 4. Insertamos el nuevo usuario usando SENTENCIA PREPARADA
    // Usamos '?' como marcadores de posición para cada dato
    $sql = "INSERT INTO usuarios (nombre_completo, fecha_nacimiento, email, telefono, password, rol) 
            VALUES (?, ?, ?, ?, ?, 'cliente')";
    
    $stmt_insert = $conexion->prepare($sql);
    
    // "sssss" significa que pasaremos 5 Strings (nombre, fecha, email, tel, pass)
    $stmt_insert->bind_param("sssss", $nombre, $fecha_nac, $email, $telefono, $pass_encriptada);

    if ($stmt_insert->execute()) {
        echo "<script>
                alert('¡Registro exitoso! Bienvenido a Remolinos Tours.');
                window.location.href='../Login_APP/login.php';
              </script>";
    } else {
        // Error genérico para no dar pistas técnicas en producción
        echo "Lo sentimos, hubo un problema al procesar tu registro.";
    }

    $stmt_insert->close();
}

mysqli_close($conexion);
?>