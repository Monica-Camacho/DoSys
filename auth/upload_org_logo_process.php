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
if (isset($_FILES['logoFile']) && $_FILES['logoFile']['error'] === UPLOAD_ERR_OK) {
    
    $file = $_FILES['logoFile'];
    $file_name = $file['name'];
    $file_size = $file['size'];
    $file_tmp = $file['tmp_name'];
    $file_type = mime_content_type($file_tmp); // Verificamos el tipo MIME real

    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_file_size = 5 * 1024 * 1024; // 5 MB

    // Validar tipo de archivo
    if (!in_array($file_type, $allowed_types)) {
        $response['message'] = 'Error: Tipo de archivo no permitido. Sube solo JPG, PNG o GIF.';
        echo json_encode($response);
        exit;
    }

    // Validar tamaño del archivo
    if ($file_size > $max_file_size) {
        $response['message'] = 'Error: El archivo es demasiado grande. El tamaño máximo es de 5 MB.';
        echo json_encode($response);
        exit;
    }

    // 4. PROCESAMIENTO Y ACTUALIZACIÓN EN BD
    // =================================================================
    
    // Generar un nombre de archivo único para evitar colisiones
    $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
    $new_file_name = 'logo_org_' . uniqid() . '_' . time() . '.' . $file_extension;
    $upload_path = '../uploads/logos/' . $new_file_name;
    $db_path = 'uploads/logos/' . $new_file_name; // Ruta que se guardará en la BD

    // Mover el archivo al directorio de destino
    if (move_uploaded_file($file_tmp, $upload_path)) {
        
        $conexion->begin_transaction();
        
        try {
            // Primero, obtenemos el ID de la organización asociada al usuario admin
            $stmt_org_id = $conexion->prepare("SELECT organizacion_id FROM usuarios_x_organizaciones WHERE usuario_id = ?");
            $stmt_org_id->bind_param("i", $usuario_id);
            $stmt_org_id->execute();
            $res_org_id = $stmt_org_id->get_result();
            if ($res_org_id->num_rows === 0) {
                 throw new Exception('El usuario no está asociado a ninguna organización.');
            }
            $org_data = $res_org_id->fetch_assoc();
            $organizacion_id = $org_data['organizacion_id'];
            $stmt_org_id->close();


            // Insertar el nuevo documento en la tabla 'documentos'
            $tipo_doc_id = 1; // 1 = Logo (según tu tabla tipos_documento)
            $sql_insert_doc = "INSERT INTO documentos (propietario_id, tipo_documento_id, ruta_archivo, fecha_carga) VALUES (?, ?, ?, NOW())";
            $stmt_insert = $conexion->prepare($sql_insert_doc);
            $stmt_insert->bind_param("iis", $usuario_id, $tipo_doc_id, $db_path);
            $stmt_insert->execute();
            $new_doc_id = $conexion->insert_id; // Obtenemos el ID del nuevo documento
            $stmt_insert->close();

            // Actualizar la tabla 'organizaciones_perfil' con el ID del nuevo logo
            $sql_update_org = "UPDATE organizaciones_perfil SET logo_documento_id = ? WHERE id = ?";
            $stmt_update = $conexion->prepare($sql_update_org);
            $stmt_update->bind_param("ii", $new_doc_id, $organizacion_id);
            $stmt_update->execute();
            $stmt_update->close();

            $conexion->commit();

            // Respuesta de éxito
            $response['success'] = true;
            $response['message'] = '¡Logo actualizado con éxito!';
            $response['new_logo_url'] = BASE_URL . $db_path;

        } catch (Exception $e) {
            $conexion->rollback();
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
?>