<?php
session_start();
session_destroy(); // Borra toda la información de la sesión
header("Location: ../index.php");
exit();
?>