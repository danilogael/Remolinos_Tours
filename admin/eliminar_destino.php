<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../Login_APP/login.php"); exit();
}
include('../Database/conexion.php');

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $row = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT foto_portada FROM destinos WHERE id=$id LIMIT 1"));
    if (mysqli_query($conexion, "DELETE FROM destinos WHERE id=$id")) {
        if (!empty($row['foto_portada']) && $row['foto_portada'] !== 'default.png') {
            $ruta = __DIR__ . '/../assets/imagenes/' . $row['foto_portada'];
            if (file_exists($ruta)) unlink($ruta);
        }
        $_SESSION['flash'] = ['tipo'=>'success','msg'=>'Destino eliminado.'];
    } else {
        $_SESSION['flash'] = ['tipo'=>'danger','msg'=>'No se pudo eliminar. Puede tener reservas asociadas.'];
    }
}

header('Location: destinos.php');
exit();
