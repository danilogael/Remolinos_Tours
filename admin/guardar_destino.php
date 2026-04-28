<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin' ) {
    header("Location: ../Login_APP/login.php");
    exit();
}

include('../Database/conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. CORRECCIÓN DE CONEXIÓN: Usamos $conexion
    mysqli_begin_transaction($conexion);

    try {
        // --- PROCESAMIENTO DE IMAGEN ---
        $nombre_imagen = "default.png";
        if (isset($_FILES['foto_portada']) && $_FILES['foto_portada']['error'] == 0) {
            $extension = pathinfo($_FILES['foto_portada']['name'], PATHINFO_EXTENSION);
            $nombre_imagen = "img_" . time() . "." . $extension;
            $ruta_destino = "../assets/imagenes/" . $nombre_imagen; 
            
            if (!is_dir("../assets/imagenes/")) {
                mkdir("../assets/imagenes/", 0777, true);
            }
            move_uploaded_file($_FILES['foto_portada']['tmp_name'], $ruta_destino);
        }

        // --- 2. RECOLECCIÓN DE DATOS (Mismos nombres que el formulario) ---
        $nombre        = mysqli_real_escape_string($conexion, $_POST['titulo']);
        $descripcion   = mysqli_real_escape_string($conexion, $_POST['descripcion']);
        $precio_adulto = $_POST['precio_adulto'];
        $precio_nino   = !empty($_POST['precio_nino']) ? $_POST['precio_nino'] : 0;
        $id_proveedor  = !empty($_POST['id_proveedor']) ? $_POST['id_proveedor'] : 'NULL';
        $tipo_trayecto = $_POST['tipo_trayecto'];
        $cupo          = $_POST['cupo'];
        $punto_salida  = mysqli_real_escape_string($conexion, $_POST['punto_salida']);
        $kg_mano       = $_POST['kg_mano'];
        $kg_doc        = $_POST['kg_doc'];
        $seguro        = isset($_POST['seguro']) ? 1 : 0;
        
        // Nuevos campos que agregamos a la DB
        $fecha_salida = $_POST['fecha_salida'];
        $dias         = $_POST['dias'];
        $noches       = $_POST['noches'];
        $estado       = $_POST['estado'];

        // --- 3. INSERTAR EN TABLA DESTINOS (Sincronizado con tu foto de DB) ---
        $sql_destino = "INSERT INTO destinos (
            nombre, descripcion, precio, precio_nino, id_proveedor, 
            imagen, tipo_trayecto, cupo_total, punto_salida, 
            maleta_mano_kg, maleta_documentada_kg, seguro_basico_incluido,
            fecha_salida, dias, noches, estado
        ) VALUES (
            '$nombre', '$descripcion', '$precio_adulto', $precio_nino, $id_proveedor, 
            '$nombre_imagen', '$tipo_trayecto', $cupo, '$punto_salida', 
            $kg_mano, $kg_doc, $seguro,
            '$fecha_salida', $dias, $noches, '$estado'
        )";
        
        if (!mysqli_query($conexion, $sql_destino)) {
            throw new Exception("Error en destinos: " . mysqli_error($conexion));
        }

        $id_nuevo_destino = mysqli_insert_id($conexion);

        // --- 4. INSERTAR ITINERARIO ---
        if (isset($_POST['itinerario_titulo'])) {
            foreach ($_POST['itinerario_titulo'] as $i => $titulo_it) {
                $dia_num = $i + 1;
                $t_it = mysqli_real_escape_string($conexion, $titulo_it);
                $d_it = mysqli_real_escape_string($conexion, $_POST['itinerario_desc'][$i]);
                
                if (!empty($t_it)) {
                    $sql_it = "INSERT INTO itinerarios (id_destino, dia_numero, titulo_actividad, descripcion_actividad) 
                               VALUES ($id_nuevo_destino, $dia_num, '$t_it', '$d_it')";
                    mysqli_query($conexion, $sql_it);
                }
            }
        }

        // --- 5. INSERTAR ACTIVIDADES EXTRA ---
        if (isset($_POST['extra_nombre'])) {
            foreach ($_POST['extra_nombre'] as $i => $n_ex_raw) {
                $n_ex = mysqli_real_escape_string($conexion, $n_ex_raw);
                $p_ex = !empty($_POST['extra_precio'][$i]) ? $_POST['extra_precio'][$i] : 0;

                if (!empty($n_ex)) {
                    $sql_ex = "INSERT INTO actividades_extra (id_destino, nombre_actividad, precio_extra) 
                               VALUES ($id_nuevo_destino, '$n_ex', $p_ex)";
                    mysqli_query($conexion, $sql_ex);
                }
            }
        }

        mysqli_commit($conexion);
        header("Location: destinos.php?msj=success");

    } catch (Exception $e) {
        mysqli_rollback($conexion);
        echo "Error al guardar: " . $e->getMessage();
    }
}
?>