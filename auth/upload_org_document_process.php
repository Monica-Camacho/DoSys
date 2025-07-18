<?php
// Especificamos que la respuesta será en formato JSON
header('Content-Type: application/json');

// 1. INICIALIZACIÓN Y SEGURIDAD
// =================================================================
require_once '../config.php';
require_once '../conexion_local.php'; // Tu script de conexión a la BD ($conexion)
session_start();

// Array para la respuesta JSON
$response = ['success' => false, 'message' => 'Ocurrió un error inesperado.'];

// Verificación de seguridad básica
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Acceso denegado. Método no permitido.';
    echo json_encode($response);
    exit;
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    $response['message'] = 'Acceso denegado. Debes iniciar sesión.';
    echo json_encode($response);
    exit;
}

// 2. VERIFICACIÓN DE ROL DE ADMINISTRADOR
// =================================================================
$usuario_id = $_SESSION['id'];
$rol_usuario = '';
$stmt_rol = $conexion->prepare("SELECT r.nombre FROM usuarios u JOIN roles r ON u.rol_id = r.id WHERE u.id = ?");
$stmt_rol->bind_param("i", $usuario_id);
$stmt_rol->execute();
$resultado_rol = $stmt_rol->get_result();
if ($fila_rol = $resultado_rol->fetch_assoc()) {
    $rol_usuario = $fila_rol['nombre'];
}
$stmt_rol->close();

if ($rol_usuario !== 'Administrador') {
    $response['message'] = 'Acceso no autorizado. No tienes permisos para realizar esta acción.';
    echo json_encode($response);
    exit;
}

// 3. VALIDACIÓN DEL ARCHIVO SUBIDO
// =================================================================
// El nombre del input es 'org_document_file', como lo definimos en el modal
if (isset($_FILES['org_document_file']) && $_FILES['org_document_file']['error'] === UPLOAD_ERR_OK) {
    
    $file = $_FILES['org_document_file'];
    $file_name = $file['name'];
    $file_size = $file['size'];
    $file_tmp = $file['tmp_name'];
    $file_type = mime_content_type($file_tmp);

    // Permitimos PDF y los tipos de imagen más comunes
    $allowed_types = ['application/pdf', 'image/jpeg', 'image/png'];
    $max_file_size = 5 * 1024 * 1024; // 5 MB

    if (!in_array($file_type, $allowed_types)) {
        $response['message'] = 'Error: Tipo de archivo no permitido. Sube solo PDF, JPG o PNG.';
        echo json_encode($response);
        exit;
    }

    if ($file_size > $max_file_size) {
        $response['message'] = 'Error: El archivo es demasiado grande. El tamaño máximo es de 5 MB.';
        echo json_encode($response);
        exit;
    }

    // 4. PROCESAMIENTO Y ACTUALIZACIÓN EN BD
    // =================================================================
    
    // Generar un nombre de archivo único
    $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
    $new_file_name = 'doc_org_' . uniqid() . '_' . time() . '.' . $file_extension;
    // Creamos un directorio específico para estos documentos
    $upload_path = '../uploads/documents_validation/' . $new_file_name; 
    $db_path = 'uploads/documents_validation/' . $new_file_name;

    // Mover el archivo al directorio de destino
    if (move_uploaded_file($file_tmp, $upload_path)) {
        
        try {
            // A diferencia del logo, aquí solo necesitamos registrar el documento en la tabla 'documentos'.
            // No se actualiza ninguna columna en 'organizaciones_perfil'.
            
            // tipo_documento_id = 5 para "Documento de Organización"
            $tipo_doc_id = 5; 
            
            $sql_insert_doc = "INSERT INTO documentos (propietario_id, tipo_documento_id, ruta_archivo, fecha_carga) VALUES (?, ?, ?, NOW())";
            $stmt_insert = $conexion->prepare($sql_insert_doc);
            $stmt_insert->bind_param("iis", $usuario_id, $tipo_doc_id, $db_path);
            
            if ($stmt_insert->execute()) {
                // Respuesta de éxito
                $response['success'] = true;
                $response['message'] = '¡Documento subido con éxito!';
            } else {
                $response['message'] = 'Error al registrar el documento en la base de datos.';
            }
            $stmt_insert->close();

        } catch (Exception $e) {
            $response['message'] = 'Error en la base de datos: ' . $e->getMessage();
        }

    } else {
        $response['message'] = 'Error al mover el archivo al servidor.';
    }

} else {
    $response['message'] = 'No se ha subido ningún archivo o ha ocurrido un error en la subida.';
}

// 5. ENVIAR RESPUESTA JSON FINAL
// =================================================================
echo json_encode($response);
$conexion->close();
?>