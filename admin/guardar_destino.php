<?php
// admin/guardar_destino.php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../Login_APP/login.php"); exit();
}
include('../Database/conexion.php');

// Subida de imagen
$foto = 'default.png';
if (!empty($_FILES['foto_portada']['name'])) {
    $ext   = strtolower(pathinfo($_FILES['foto_portada']['name'], PATHINFO_EXTENSION));
    $allow = ['jpg','jpeg','png','webp'];
    if (in_array($ext, $allow)) {
        $nombre   = uniqid('dest_') . '.' . $ext;
        $destino_upload = __DIR__ . '/../assets/imagenes/' . $nombre;
        if (move_uploaded_file($_FILES['foto_portada']['tmp_name'], $destino_upload)) {
            $foto = $nombre;
        }
    }
}

// Sanitizar campos — usando columnas REALES de tu tabla
$nombre       = mysqli_real_escape_string($conexion, trim($_POST['titulo']        ?? ''));
$descripcion  = mysqli_real_escape_string($conexion, trim($_POST['descripcion']   ?? ''));
$precio       = (float)($_POST['precio_adulto']  ?? 0);
$precio_nino  = (float)($_POST['precio_nino']    ?? 0);
$id_proveedor = (int)($_POST['id_proveedor']     ?? 0);
$tipo_trayecto= mysqli_real_escape_string($conexion, $_POST['tipo_trayecto']      ?? 'Redondo');
$cupo         = (int)($_POST['cupo']             ?? 20);
$punto_salida = mysqli_real_escape_string($conexion, trim($_POST['punto_salida']  ?? ''));
$kg_mano      = (int)($_POST['kg_mano']          ?? 10);
$kg_doc       = (int)($_POST['kg_doc']           ?? 25);
$seguro       = isset($_POST['seguro']) ? 1 : 0;
$dias         = (int)($_POST['dias']             ?? 1);
$noches       = (int)($_POST['noches']           ?? 0);
$estado       = mysqli_real_escape_string($conexion, $_POST['estado']             ?? 'Activo');
$fecha_salida = !empty($_POST['fecha_salida']) ? "'" . mysqli_real_escape_string($conexion, $_POST['fecha_salida']) . "'" : 'NULL';
$foto_esc     = mysqli_real_escape_string($conexion, $foto);
$prov_val     = $id_proveedor > 0 ? $id_proveedor : 'NULL';

if ($nombre === '') {
    header("Location: nuevo_destino.php?error=" . urlencode('El nombre es obligatorio.'));
    exit();
}

$sql = "INSERT INTO destinos
            (nombre, descripcion, precio, precio_nino, id_proveedor, foto_portada,
             tipo_trayecto, cupo_total, punto_salida, maleta_mano_kg, maleta_documentada_kg,
             seguro_basico_incluido, dias, noches, fecha_salida, estado)
        VALUES
            ('$nombre','$descripcion',$precio,$precio_nino,$prov_val,'$foto_esc',
             '$tipo_trayecto',$cupo,'$punto_salida',$kg_mano,$kg_doc,
             $seguro,$dias,$noches,$fecha_salida,'$estado')";

if (!mysqli_query($conexion, $sql)) {
    header("Location: nuevo_destino.php?error=" . urlencode('Error al guardar: ' . mysqli_error($conexion)));
    exit();
}

$id_destino = mysqli_insert_id($conexion);

// Guardar itinerario
$titulos = $_POST['itinerario_titulo'] ?? [];
$descs   = $_POST['itinerario_desc']   ?? [];
foreach ($titulos as $i => $titulo) {
    $titulo = trim(mysqli_real_escape_string($conexion, $titulo));
    $desc   = trim(mysqli_real_escape_string($conexion, $descs[$i] ?? ''));
    $dia    = $i + 1;
    if ($titulo !== '') {
        mysqli_query($conexion,
            "INSERT INTO itinerarios (id_destino, dia_numero, titulo_actividad, descripcion_actividad)
             VALUES ($id_destino, $dia, '$titulo', '$desc')"
        );
    }
}

// Guardar actividades extra
$extras_nombre = $_POST['extra_nombre'] ?? [];
$extras_precio = $_POST['extra_precio'] ?? [];
foreach ($extras_nombre as $i => $enombre) {
    $enombre = trim(mysqli_real_escape_string($conexion, $enombre));
    $eprecio = (float)($extras_precio[$i] ?? 0);
    if ($enombre !== '') {
        mysqli_query($conexion,
            "INSERT INTO actividades_extra (id_destino, nombre_actividad, precio_extra)
             VALUES ($id_destino, '$enombre', $eprecio)"
        );
    }
}

$_SESSION['flash'] = ['tipo'=>'success','msg'=>'Destino «'.$_POST['titulo'].'» creado correctamente.'];
header('Location: destinos.php');
exit();
