<?php
// Configuración de la base de datos
$host     = "localhost";
$user     = "root";     // Tu usuario de base de datos
$password = "";         // Tu contraseña (vacío en XAMPP por defecto)
$db_name  = "remolino_tours"; // Asegúrate de que este nombre sea igual al de tu DB

// Crear la conexión
$conexion = mysqli_connect($host, $user, $password, $db_name);

// Verificar si la conexión falló
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Configurar el conjunto de caracteres a UTF-8 para que acepten acentos y la "ñ"
mysqli_set_charset($conexion, "utf8");
?>