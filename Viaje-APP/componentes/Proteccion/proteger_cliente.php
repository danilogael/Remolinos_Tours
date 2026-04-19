<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "cliente") {
    header("Location: /viaje/viaje/Viaje-APP/componentes/iniciarSesion/login.php");
    exit;
}
?>
