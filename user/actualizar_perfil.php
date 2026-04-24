<?php
include('../Database/conexion.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['id_usuario'])) {
    $id = $_SESSION['id_usuario'];
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $tel = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $fecha = mysqli_real_escape_string($conexion, $_POST['fecha_nac']);

    $query = "UPDATE usuarios SET 
              nombre_completo = '$nombre', 
              telefono = '$tel', 
              fecha_nacimiento = '$fecha' 
              WHERE id = '$id'";

    if (mysqli_query($conexion, $query)) {
        header("Location: viewdata.php?status=updated");
    } else {
        echo "Error al actualizar: " . mysqli_error($conexion);
    }
}
?>