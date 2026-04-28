<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../Login_APP/login.php"); exit();
}
include('../Database/conexion.php');

$id     = (int)($_GET['id'] ?? 0);
$estado = $_GET['estado'] ?? '';

if ($id > 0 && in_array($estado, ['Pendiente','Confirmada','Cancelada'])) {
    $est = mysqli_real_escape_string($conexion, $estado);
    mysqli_query($conexion, "UPDATE reservas SET estado='$est' WHERE id=$id");
    $_SESSION['flash'] = ['tipo'=>'success','msg'=>"Reserva actualizada a «$estado»."];
}

header('Location: reservas.php');
exit();
