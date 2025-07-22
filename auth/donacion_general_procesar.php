<?php
require_once '../config.php';
require_once '../conexion_local.php';
session_start();

// =================================================================
// PROCESAMIENTO DEL FORMULARIO DE DONACIÓN GENERAL
// =================================================================

## 1. Verificaciones Iniciales de Seguridad
// -----------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit();
}
if (!isset($_SESSION['id'])) {
    header('Location: ../login.php?error=2');
    exit();
}

## 2. Recolección y Validación de Datos
// -----------------------------------------------------------------
$donante_id = $_SESSION['id'];
$organizacion_id = filter_input(INPUT_POST, 'organizacion_id', FILTER_VALIDATE_INT);
$categoria_seleccionada = $_POST['categoria_donacion'] ?? null;

// URL para redirigir en caso de error
$redirect_url = '../donacion_general.php';

// Si faltan datos esenciales, redirigimos con un mensaje de error.
if (!$organizacion_id || !$categoria_seleccionada) {
    $_SESSION['error_message'] = "Faltan datos en el formulario. Debes seleccionar una categoría y una organización.";
    header('Location: ' . $redirect_url);
    exit();
}

## 3. Preparación de Datos Específicos y Foto
// -----------------------------------------------------------------
$aviso_id = null; // Para donaciones generales, el aviso_id siempre es NULL.
$cantidad = null;
$item_nombre = null;
$item_detalle = null;
$fecha_caducidad = null;
$ruta_foto_db = null;
$nombre_campo_foto = null;

// Asignamos los valores de POST a las variables correspondientes según la categoría.
switch ($categoria_seleccionada) {
    case 'sangre':
        $cantidad = filter_input(INPUT_POST, 'cantidad_sangre', FILTER_VALIDATE_INT);
        break;
    case 'medicamentos':
        $cantidad = filter_input(INPUT_POST, 'cantidad_medicamento', FILTER_VALIDATE_INT);
        $item_nombre = trim($_POST['nombre_medicamento']);
        $fecha_caducidad = trim($_POST['caducidad']);
        $nombre_campo_foto = 'foto_medicamento';
        break;
    case 'dispositivos':
        $cantidad = filter_input(INPUT_POST, 'cantidad_dispositivo', FILTER_VALIDATE_INT);
        $item_nombre = trim($_POST['nombre_dispositivo']);
        $item_detalle = trim($_POST['estado_dispositivo']);
        $nombre_campo_foto = 'foto_dispositivo';
        break;
}

// Validamos que los campos requeridos para la categoría seleccionada no estén vacíos.
if ($cantidad === null || ($categoria_seleccionada !== 'sangre' && empty($item_nombre))) {
    $_SESSION['error_message'] = "Por favor, completa todos los detalles requeridos para tu donación.";
    header('Location: ' . $redirect_url);
    exit();
}

// Se maneja la foto (si se subió).
if ($nombre_campo_foto && isset($_FILES[$nombre_campo_foto]) && $_FILES[$nombre_campo_foto]['error'] === UPLOAD_ERR_OK) {
    $foto_tmp = $_FILES[$nombre_campo_foto]['tmp_name'];
    $nombre_foto = uniqid('donacion_general_') . '_' . basename($_FILES[$nombre_campo_foto]['name']);
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
// -----------------------------------------------------------------
$conexion->begin_transaction();

try {
    $estatus_id = 1; // 1 = Pendiente de Aprobación.
    
    $sql_insert = "INSERT INTO donaciones 
                    (aviso_id, donante_id, organizacion_id, cantidad, estatus_id, fecha_compromiso, 
                     item_nombre, item_detalle, fecha_caducidad, ruta_foto) 
                   VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?)";
    
    $stmt_insert = $conexion->prepare($sql_insert);
    
    if ($stmt_insert === false) {
        throw new Exception("Error en la preparación de la consulta: " . $conexion->error);
    }
    
    // El primer 'i' corresponde a aviso_id, que es NULL pero se trata como integer.
    $stmt_insert->bind_param("iiiiissss", 
        $aviso_id, $donante_id, $organizacion_id, $cantidad, $estatus_id,
        $item_nombre, $item_detalle, $fecha_caducidad, $ruta_foto_db
    );
    
    $stmt_insert->execute();
    $stmt_insert->close();

    $conexion->commit();

    $_SESSION['success_message'] = "¡Gracias! Tu donación general ha sido registrada con éxito.";
    header('Location: ../index.php');
    exit();

} catch (Exception $e) {
    $conexion->rollback();
    $_SESSION['error_message'] = "Ocurrió un error al registrar tu donación: " . $e->getMessage();
    header('Location: ' . $redirect_url);
    exit();
}
?>