<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../Login_APP/login.php"); exit();
}
include('../Database/conexion.php');

$id = (int)($_GET['id'] ?? 0);

if ($id > 0 && $id !== (int)($_SESSION['id_usuario'] ?? 0)) {
    mysqli_query($conexion, "DELETE FROM usuarios WHERE id=$id");
    $_SESSION['flash'] = ['tipo'=>'success','msg'=>'Usuario eliminado correctamente.'];
} elseif ($id === (int)($_SESSION['id_usuario'] ?? 0)) {
    $_SESSION['flash'] = ['tipo'=>'warning','msg'=>'No puedes eliminar tu propia cuenta.'];
}

header('Location: usuarios.php');
exit();
