<?php
// =================================================================
// 1. INICIALIZACIÓN Y SEGURIDAD
// =================================================================
require_once '../config.php';
require_once '../conexion_local.php';
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
error_log("--- Inicia crear_aviso_process.php ---");

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    error_log("Error: Usuario no autenticado.");
    header('Location: ' . BASE_URL . 'login.php?error=session');
    exit;
}
$creador_id = $_SESSION['id'];
error_log("Usuario autenticado. Creador ID: " . $creador_id);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    error_log("Error: Método no es POST.");
    header('Location: ' . BASE_URL . 'avisos_crear.php');
    exit;
}

// =================================================================
// 2. RECEPCIÓN DE DATOS DEL FORMULARIO
// =================================================================
$titulo = trim($_POST['titulo']);
$descripcion = trim($_POST['descripcion']);
$urgencia_id = (int)$_POST['urgencia_id'];
$organizacion_id = (int)$_POST['organizacion_id'];
$categoria_id = 1;

$solicitud_tipo_sangre_id = (int)$_POST['solicitud_tipo_sangre_id'];
$unidades_requeridas = (int)$_POST['unidades_requeridas'];

$es_para_mi = isset($_POST['es_para_mi']);
$donatario_id = null;

error_log("Datos recibidos del formulario. Título: " . $titulo);

// =================================================================
// 3. INICIO DE LA TRANSACCIÓN A LA BASE DE DATOS
// =================================================================
$conexion->begin_transaction();
error_log("Iniciando transacción...");

try {
    // --- PASO 3.1: MANEJO DEL DONATARIO ---
    if ($es_para_mi) {
        error_log("Checkbox 'es_para_mi' está marcado. Buscando donatario existente...");
        $stmt_find = $conexion->prepare("SELECT id FROM donatarios WHERE usuario_id = ?");
        $stmt_find->bind_param("i", $creador_id);
        $stmt_find->execute();
        $res_find = $stmt_find->get_result();
        
        if ($res_find->num_rows > 0) {
            $donatario_id = $res_find->fetch_assoc()['id'];
            error_log("Donatario existente encontrado. ID: " . $donatario_id);
        } else {
            error_log("No se encontró donatario existente. Creando uno nuevo a partir del perfil...");
            $stmt_get_user = $conexion->prepare("SELECT nombre, apellido_paterno, apellido_materno, fecha_nacimiento, sexo FROM personas_perfil WHERE usuario_id = ?");
            $stmt_get_user->bind_param("i", $creador_id);
            $stmt_get_user->execute();
            $user_data = $stmt_get_user->get_result()->fetch_assoc();

            $stmt_insert_don = $conexion->prepare("INSERT INTO donatarios (usuario_id, nombre, apellido_paterno, apellido_materno, fecha_nacimiento, sexo) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_insert_don->bind_param("isssss", $creador_id, $user_data['nombre'], $user_data['apellido_paterno'], $user_data['apellido_materno'], $user_data['fecha_nacimiento'], $user_data['sexo']);
            $stmt_insert_don->execute();
            $donatario_id = $conexion->insert_id;
            error_log("Nuevo donatario creado a partir del perfil. ID: " . $donatario_id);
        }
    } else {
        error_log("Checkbox 'es_para_mi' NO está marcado. Creando donatario externo...");
        $donatario_nombre = trim($_POST['donatario_nombre']);
        $donatario_paterno = trim($_POST['donatario_paterno']);
        $donatario_materno = trim($_POST['donatario_materno']);
        $donatario_nacimiento = $_POST['donatario_nacimiento'];
        $donatario_sexo = $_POST['donatario_sexo'];
        $donatario_tipo_sangre_id = (int)$_POST['donatario_tipo_sangre_id'];
        
        $stmt_insert_don = $conexion->prepare("INSERT INTO donatarios (nombre, apellido_paterno, apellido_materno, fecha_nacimiento, sexo, tipo_sangre_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt_insert_don->bind_param("sssssi", $donatario_nombre, $donatario_paterno, $donatario_materno, $donatario_nacimiento, $donatario_sexo, $donatario_tipo_sangre_id);
        $stmt_insert_don->execute();
        $donatario_id = $conexion->insert_id;
        error_log("Nuevo donatario externo creado. ID: " . $donatario_id);
    }

    // --- PASO 3.2: MANEJO DEL DOCUMENTO ---
    $documento_id = null;
    if (isset($_FILES['documento']) && $_FILES['documento']['error'] === UPLOAD_ERR_OK) {
        error_log("Procesando archivo subido: " . $_FILES['documento']['name']);
        $upload_dir = '../uploads/avisos_docs/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file = $_FILES['documento'];
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_file_name = 'doc_aviso_' . uniqid() . '_' . time() . '.' . $file_extension;
        $upload_path = $upload_dir . $new_file_name;
        $db_path = 'uploads/avisos_docs/' . $new_file_name;

        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            error_log("Archivo movido exitosamente a: " . $upload_path);
            $tipo_doc_id = 6;
            $stmt_insert_doc = $conexion->prepare("INSERT INTO documentos (propietario_id, tipo_documento_id, ruta_archivo) VALUES (?, ?, ?)");
            $stmt_insert_doc->bind_param("iis", $creador_id, $tipo_doc_id, $db_path);
            $stmt_insert_doc->execute();
            $documento_id = $conexion->insert_id;
            error_log("Documento registrado en la BD. ID: " . $documento_id);
        } else {
            throw new Exception("Error al mover el archivo subido.");
        }
    } else {
        throw new Exception("El documento de soporte es obligatorio. Código de error: " . ($_FILES['documento']['error'] ?? 'No file'));
    }

    // --- PASO 3.3: CREACIÓN DEL AVISO PRINCIPAL ---
    error_log("Creando aviso principal...");
    $estatus_id = 1; // "Pendiente de Validar"
    $stmt_aviso = $conexion->prepare("INSERT INTO avisos (titulo, descripcion, creador_id, organizacion_id, donatario_id, contacto_responsable_id, categoria_id, urgencia_id, estatus_id, fecha_creacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    
    // =========================================================
    // INICIO DE LA CORRECCIÓN
    // =========================================================
    // La cadena de tipos ahora tiene 9 letras ('ssiiiiiii') para coincidir con los 9 '?'
    $stmt_aviso->bind_param("ssiiiiiii", $titulo, $descripcion, $creador_id, $organizacion_id, $donatario_id, $creador_id, $categoria_id, $urgencia_id, $estatus_id);
    // =========================================================
    // FIN DE LA CORRECCIÓN
    // =========================================================

    $stmt_aviso->execute();
    $aviso_id = $conexion->insert_id;
    error_log("Aviso principal creado. ID: " . $aviso_id);

    // --- PASO 3.4: CREACIÓN DE VÍNCULOS ---
    error_log("Creando vínculos...");
    $stmt_link_doc = $conexion->prepare("INSERT INTO avisos_documentos (aviso_id, documento_id) VALUES (?, ?)");
    $stmt_link_doc->bind_param("ii", $aviso_id, $documento_id);
    $stmt_link_doc->execute();
    
    $stmt_link_sangre = $conexion->prepare("INSERT INTO solicitudes_sangre (aviso_id, tipo_sangre_id, unidades_requeridas) VALUES (?, ?, ?)");
    $stmt_link_sangre->bind_param("iii", $aviso_id, $solicitud_tipo_sangre_id, $unidades_requeridas);
    $stmt_link_sangre->execute();
    error_log("Vínculos creados exitosamente.");

    // =================================================================
    // 4. FINALIZACIÓN DE LA TRANSACCIÓN
    // =================================================================
    $conexion->commit();
    error_log("Transacción completada (COMMIT). Redirigiendo a página de éxito.");
    header('Location: ' . BASE_URL . 'aviso_exito.php');
    exit;

} catch (Exception $e) {
    $conexion->rollback();
    error_log("!!! ERROR EN LA TRANSACCIÓN: " . $e->getMessage() . " !!!");
    header('Location: ' . BASE_URL . 'avisos_crear.php?status=error');
    exit;
}
?>
