<?php
session_start();
include('../Database/conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $pass  = isset($_POST['pass']) ? $_POST['pass'] : '';

    if (empty($email) || empty($pass)) {
        header("Location: ../Login_APP/login.php?error=vacio");
        exit();
    }

    // Consulta preparada
    $stmt = $conexion->prepare("SELECT id, nombre_completo, password, rol FROM usuarios WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        if (password_verify($pass, $fila['password'])) {
            
            session_regenerate_id(true);

            $_SESSION['id_usuario'] = $fila['id'];
            $_SESSION['nombre']     = $fila['nombre_completo'];
          // Forzamos a que el rol sea un número entero al guardarlo
$_SESSION['rol'] = strtolower(trim($fila['rol']));
if ($_SESSION['rol'] === 'admin') {
    header("Location: ../admin/index.php"); 
} else {
    header("Location: ../index.php");
}
exit();

        } else {
            header("Location: ../Login_APP/login.php?error=datos_incorrectos");
            exit();
        }
    } else {
        header("Location: ../Login_APP/login.php?error=datos_incorrectos");
        exit();
    }
    
    $stmt->close();
}

mysqli_close($conexion);
?>