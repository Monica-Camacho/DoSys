<?php
// =================================================================
// 1. INICIALIZACIÓN Y SEGURIDAD
// =================================================================
require_once '../config.php';
require_once '../conexion_local.php';

// Añadimos esta línea para que cualquier error de base de datos lance una excepción.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ' . BASE_URL . 'login.php?error=session');
    exit;
}
$creador_id = $_SESSION['id'];

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header('Location: ' . BASE_URL . 'avisos_crear_dispositivo.php');
    exit;
}

// =================================================================
// 2. RECEPCIÓN DE DATOS DEL FORMULARIO
// =================================================================
// --- Datos del Aviso (Estos son generales y se mantienen) ---
$titulo = trim($_POST['titulo']);
$descripcion = trim($_POST['descripcion']);
$urgencia_id = (int)$_POST['urgencia_id'];
$organizacion_id = (int)$_POST['organizacion_id'];
$categoria_id = 3; // Fijo para DISPOSITIVOS en este formulario.

// --- Datos específicos de la Solicitud de Medicamento ---
$nombre_medicamento = trim($_POST['nombre_medicamento']);
$dosis = trim($_POST['dosis']);
$presentacion = trim($_POST['presentacion']);
$cantidad_requerida = (int)$_POST['cantidad_requerida'];
// --- FIN  ---

// --- Lógica del Donatario ---
$es_para_mi = isset($_POST['es_para_mi']);
$donatario_id = null;

// =================================================================
// 3. INICIO DE LA TRANSACCIÓN A LA BASE DE DATOS
// =================================================================
$conexion->begin_transaction();

try {
    // --- PASO 3.1: MANEJO DEL DONATARIO ---
    if ($es_para_mi) {
        $stmt_find = $conexion->prepare("SELECT id FROM donatarios WHERE usuario_id = ?");
        $stmt_find->bind_param("i", $creador_id);
        $stmt_find->execute();
        $res_find = $stmt_find->get_result();
        
        if ($res_find->num_rows > 0) {
            $donatario_id = $res_find->fetch_assoc()['id'];
        } else {
            // --- INICIO DE LA CORRECCIÓN ---
            // Buscamos el perfil de persona del usuario, incluyendo el tipo de sangre.
            $stmt_get_user = $conexion->prepare("SELECT nombre, apellido_paterno, apellido_materno, fecha_nacimiento, sexo, tipo_sangre_id FROM personas_perfil WHERE usuario_id = ?");
            $stmt_get_user->bind_param("i", $creador_id);
            $stmt_get_user->execute();
            $user_data = $stmt_get_user->get_result()->fetch_assoc();

            // Verificamos si se encontró un perfil. Si no, detenemos todo.
            if (!$user_data) {
                throw new Exception("El usuario actual (ID: {$creador_id}) no tiene un perfil de persona asociado. No se pueden autocompletar los datos del donatario.");
            }
            // --- FIN DE LA CORRECCIÓN ---

            // Preparamos la inserción en la tabla 'donatarios', incluyendo el nuevo campo.
            $stmt_insert_don = $conexion->prepare("INSERT INTO donatarios (usuario_id, nombre, apellido_paterno, apellido_materno, fecha_nacimiento, sexo, tipo_sangre_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            // Vinculamos todos los parámetros, incluyendo tipo_sangre_id (asumiendo que es un entero 'i').
            $stmt_insert_don->bind_param("isssssi", $creador_id, $user_data['nombre'], $user_data['apellido_paterno'], $user_data['apellido_materno'], $user_data['fecha_nacimiento'], $user_data['sexo'], $user_data['tipo_sangre_id']);
            
            $stmt_insert_don->execute();
            $donatario_id = $conexion->insert_id;
        }
    } else {
        // La lógica para donatario externo (esta ya funcionaba bien).
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
    }

    // --- PASO 3.2: MANEJO DEL DOCUMENTO ---
    $documento_id = null;
    if (isset($_FILES['documento']) && $_FILES['documento']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/avisos_docs/dispositivo/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $file = $_FILES['documento'];
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_file_name = 'doc_aviso_' . uniqid() . '_' . time() . '.' . $file_extension;
        $upload_path = $upload_dir . $new_file_name;
        $db_path = 'uploads/avisos_docs/dispositivo/' . $new_file_name;
        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            $tipo_doc_id = 6;
            $stmt_insert_doc = $conexion->prepare("INSERT INTO documentos (propietario_id, tipo_documento_id, ruta_archivo) VALUES (?, ?, ?)");
            $stmt_insert_doc->bind_param("iis", $creador_id, $tipo_doc_id, $db_path);
            $stmt_insert_doc->execute();
            $documento_id = $conexion->insert_id;
        } else {
            throw new Exception("Error al mover el archivo subido.");
        }
    } else {
        throw new Exception("El documento de soporte es obligatorio. Código de error: " . ($_FILES['documento']['error'] ?? 'No file'));
    }

    // --- PASO 3.3: CREACIÓN DEL AVISO PRINCIPAL ---
    $estatus_id = 1;
    $stmt_aviso = $conexion->prepare("INSERT INTO avisos (titulo, descripcion, creador_id, organizacion_id, donatario_id, contacto_responsable_id, categoria_id, urgencia_id, estatus_id, fecha_creacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt_aviso->bind_param("ssiiiiiii", $titulo, $descripcion, $creador_id, $organizacion_id, $donatario_id, $creador_id, $categoria_id, $urgencia_id, $estatus_id);
    $stmt_aviso->execute();
    $aviso_id = $conexion->insert_id;

    // --- PASO 3.4: CREACIÓN DE VÍNCULOS ---
    $stmt_link_doc = $conexion->prepare("INSERT INTO avisos_documentos (aviso_id, documento_id) VALUES (?, ?)");
    $stmt_link_doc->bind_param("ii", $aviso_id, $documento_id);
    $stmt_link_doc->execute();
    
    // --- Vincular detalles del MEDICAMENTO con aviso ---
    $stmt_link_medicamento = $conexion->prepare("INSERT INTO solicitudes_medicamentos (aviso_id, nombre_medicamento, dosis, presentacion, cantidad_requerida) VALUES (?, ?, ?, ?, ?)");
    $stmt_link_medicamento->bind_param("isssi", $aviso_id, $nombre_medicamento, $dosis, $presentacion, $cantidad_requerida);
    $stmt_link_medicamento->execute();
    // --- FIN ---

    // =================================================================
    // 4. FINALIZACIÓN DE LA TRANSACCIÓN
    // =================================================================
    $conexion->commit();
    header('Location: ' . BASE_URL . 'aviso_exito.php');
    exit;

} catch (Exception $e) {
    $conexion->rollback();
    error_log("Error en crear_aviso_dispositivo_process.php: " . $e->getMessage());
    header('Location: ' . BASE_URL . 'aviso_error.php'); // Redirigir a la nueva página de error
    exit;
}
?>
