<?php
require_once '../config.php';
require_once '../conexion_local.php';
session_start();

// =================================================================
// PROCESAMIENTO DEL FORMULARIO DE DONACIÓN
// =================================================================

## 1. Verificaciones Iniciales de Seguridad
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit();
}
if (!isset($_SESSION['id'])) {
    header('Location: ../login.php?error=2');
    exit();
}

## 2. Recolección y Validación de Datos
$donante_id = $_SESSION['id'];
$aviso_id = filter_input(INPUT_POST, 'aviso_id', FILTER_VALIDATE_INT);
$cantidad = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_INT);
// CORRECCIÓN: Se elimina la variable $organizacion_id que no se usa.

$redirect_url = '../donacion_registrar.php?aviso_id=' . $aviso_id;

// Se actualiza la validación.
if (!$aviso_id || !$cantidad) {
    $_SESSION['error_message'] = "Faltan datos en el formulario. Por favor, inténtalo de nuevo.";
    header('Location: ' . $redirect_url);
    exit();
}

## 3. Preparación de Datos Específicos y Foto
$item_nombre = null;
$item_detalle = null;
$fecha_caducidad = null;
$ruta_foto_db = null;

$sql_categoria = "SELECT categoria_id FROM avisos WHERE id = ?";
$stmt_cat = $conexion->prepare($sql_categoria);
$stmt_cat->bind_param("i", $aviso_id);
$stmt_cat->execute();
$resultado_cat = $stmt_cat->get_result();
$categoria_id = $resultado_cat->fetch_assoc()['categoria_id'];
$stmt_cat->close();

switch ($categoria_id) {
    case 2: // Medicamentos
        $item_nombre = trim($_POST['nombre_medicamento']);
        $item_detalle = null; 
        $fecha_caducidad = trim($_POST['caducidad']);
        break;
    case 3: // Dispositivos
        $item_nombre = trim($_POST['nombre_dispositivo']);
        $item_detalle = trim($_POST['estado_dispositivo']);
        $fecha_caducidad = null;
        break;
}

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $foto_tmp = $_FILES['foto']['tmp_name'];
    $nombre_foto = uniqid('donacion_') . '_' . basename($_FILES['foto']['name']);
    $directorio_destino = '../uploads/donaciones/';

    if (!file_exists($directorio_destino)) {
        mkdir($directorio_destino, 0777, true);
    }
    $ruta_destino = $directorio_destino . $nombre_foto;

    if (move_uploaded_file($foto_tmp, $ruta_destino)) {
        $ruta_foto_db = 'uploads/donaciones/' . $nombre_foto;
    } else {
        $_SESSION['error_message'] = "Error al guardar la imagen.";
        header('Location: ' . $redirect_url);
        exit();
    }
}

## 4. Inserción en la Base de Datos
$conexion->begin_transaction();

try {
    $estatus_id = 1; 
    
    // CORRECCIÓN: Se elimina 'organizacion_id' de la consulta.
    $sql_insert = "INSERT INTO donaciones 
                    (aviso_id, donante_id, cantidad, estatus_id, fecha_compromiso, 
                     item_nombre, item_detalle, fecha_caducidad, ruta_foto) 
                   VALUES (?, ?, ?, ?, NOW(), ?, ?, ?, ?)";
    
    $stmt_insert = $conexion->prepare($sql_insert);
    
    if ($stmt_insert === false) {
        throw new Exception("Error en la preparación de la consulta: " . $conexion->error);
    }
    
    // CORRECCIÓN: Se elimina una 'i' de la cadena de tipos y la variable $organizacion_id.
    $stmt_insert->bind_param("iiiissss", 
        $aviso_id, $donante_id, $cantidad, $estatus_id,
        $item_nombre, $item_detalle, $fecha_caducidad, $ruta_foto_db
    );
    
    $stmt_insert->execute();
    $stmt_insert->close();

    $conexion->commit();

    $_SESSION['success_message'] = "¡Gracias! Tu compromiso de donación ha sido registrado con éxito.";
    header('Location: ../index.php');
    exit();

} catch (Exception $e) {
    $conexion->rollback();
    $_SESSION['error_message'] = "Ocurrió un error al registrar tu donación: " . $e->getMessage();
    header('Location: ' . $redirect_url);
    exit();
}
?>