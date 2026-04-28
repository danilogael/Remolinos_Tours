<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../Login_APP/login.php"); exit();
}
include('../Database/conexion.php');

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    if (mysqli_query($conexion, "DELETE FROM proveedores WHERE id_proveedor=$id")) {
        $_SESSION['flash'] = ['tipo'=>'success','msg'=>'Proveedor eliminado.'];
    } else {
        $_SESSION['flash'] = ['tipo'=>'danger','msg'=>'No se pudo eliminar. Puede tener destinos asociados.'];
    }
}

header('Location: proveedores.php');
exit();
