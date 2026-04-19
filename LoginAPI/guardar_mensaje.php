<?php
header("Content-Type: application/json");
require $_SERVER['DOCUMENT_ROOT'].'/viaje/viaje/LoginAPI/db.php';

$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$numero = $_POST['numero'];
$mensaje = $_POST['mensaje'];

$sql = "INSERT INTO mensajes (nombre, correo, numero, mensaje)
        VALUES (:nombre, :correo, :numero, :mensaje)";

$stmt = $conexion->prepare($sql);

$stmt->bindParam(":nombre", $nombre);
$stmt->bindParam(":correo", $correo);
$stmt->bindParam(":numero", $numero);
$stmt->bindParam(":mensaje", $mensaje);

if($stmt->execute()){
    echo json_encode(["status" => "ok"]);
} else {
    echo json_encode(["status" => "error"]);
}
