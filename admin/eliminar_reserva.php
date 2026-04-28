<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../Login_APP/login.php"); exit();
}
include('../Database/conexion.php');

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    mysqli_query($conexion, "DELETE FROM reservas WHERE id=$id");
    $_SESSION['flash'] = ['tipo'=>'success','msg'=>'Reserva eliminada.'];
}

header('Location: reservas.php');
exit();
