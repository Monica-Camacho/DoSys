<?php
require_once '../config.php';
require_once '../conexion_local.php';
session_start();

// Ruta en el sistema de archivos para guardar las imágenes.
// __DIR__ . '/../' apunta al directorio raíz de tu proyecto.
$file_system_base_dir = __DIR__ . '/../uploads/beneficios/';

// Ruta web para guardar en la base de datos.
$web_base_path = 'uploads/beneficios/';

// Asegurarse de que el directorio exista.
if (!is_dir($file_system_base_dir)) {
    mkdir($file_system_base_dir, 0777, true);
}

// 1. VERIFICACIONES DE SEGURIDAD
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['id'])) {
    header('Location: ../index.php');
    exit();
}
$admin_id = $_SESSION['id'];

// Obtener la empresa_id y el rol del usuario logueado
$sql_permisos = "SELECT ue.empresa_id, u.rol_id FROM usuarios_x_empresas ue JOIN usuarios u ON ue.usuario_id = u.id WHERE ue.usuario_id = ?";
$stmt_permisos = $conexion->prepare($sql_permisos);
$stmt_permisos->bind_param("i", $admin_id);
$stmt_permisos->execute();
$resultado_permisos = $stmt_permisos->get_result();
if ($resultado_permisos->num_rows === 0) {
    $_SESSION['error_message'] = "No tienes permiso para realizar esta acción.";
    header('Location: ../empresa_beneficios.php');
    exit();
}
$permisos = $resultado_permisos->fetch_assoc();
$empresa_id = $permisos['empresa_id'];
$rol_admin = $permisos['rol_id'];
$stmt_permisos->close();

// Solo los administradores (rol_id = 1) pueden gestionar
if ($rol_admin != 1) {
    $_SESSION['error_message'] = "No tienes permiso para gestionar beneficios.";
    header('Location: ../empresa_beneficios.php');
    exit();
}

$action = $_POST['action'] ?? '';
$benefit_id = filter_input(INPUT_POST, 'benefit_id', FILTER_VALIDATE_INT);

$conexion->begin_transaction();

try {
    if ($action === 'add') {
        $titulo = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $tipo_apoyo_id = filter_input(INPUT_POST, 'tipo_apoyo_id', FILTER_VALIDATE_INT);
        $fecha_expiracion = !empty($_POST['fecha_expiracion']) ? $_POST['fecha_expiracion'] : NULL;
        $activo = isset($_POST['activo']) ? 1 : 0;

        if (empty($titulo) || empty($descripcion) || !$tipo_apoyo_id) {
            throw new Exception("Todos los campos obligatorios deben ser completados.");
        }

        $imagen_documento_id = NULL;
        if (isset($_FILES['imagen_beneficio']) && $_FILES['imagen_beneficio']['error'] == UPLOAD_ERR_OK) {
            $file_info = $_FILES['imagen_beneficio'];
            $file_ext = strtolower(pathinfo($file_info['name'], PATHINFO_EXTENSION));

            $new_file_name = uniqid('img_benefit_') . '.' . $file_ext;
            $file_system_path = $file_system_base_dir . $new_file_name;
            $web_path = $web_base_path . $new_file_name;

            if (move_uploaded_file($file_info['tmp_name'], $file_system_path)) {
                $stmt_get_tipo = $conexion->prepare("SELECT id FROM tipos_documento WHERE codigo = 'IMAGEN_BENEFICIO'");
                $stmt_get_tipo->execute();
                $tipo_documento_id_img_benefit = $stmt_get_tipo->get_result()->fetch_assoc()['id'];
                $stmt_get_tipo->close();
                if (!$tipo_documento_id_img_benefit) throw new Exception("Tipo de documento 'IMAGEN_BENEFICIO' no encontrado.");

                $sql_insert_doc = "INSERT INTO documentos (propietario_id, tipo_documento_id, ruta_archivo, nombre_original, tipo_mime) VALUES (?, ?, ?, ?, ?)";
                $stmt_insert_doc = $conexion->prepare($sql_insert_doc);
                // --- CORRECCIÓN CLAVE: Usar $admin_id en lugar de $empresa_id como propietario ---
                $stmt_insert_doc->bind_param("iisss", $admin_id, $tipo_documento_id_img_benefit, $web_path, $file_info['name'], $file_info['type']);
                $stmt_insert_doc->execute();
                $imagen_documento_id = $conexion->insert_id;
                $stmt_insert_doc->close();
            } else {
                throw new Exception("Error al subir la imagen.");
            }
        }

        $sql_insert_benefit = "INSERT INTO empresas_apoyos (empresa_id, tipo_apoyo_id, titulo, descripcion, fecha_expiracion, activo, imagen_documento_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert_benefit = $conexion->prepare($sql_insert_benefit);
        $stmt_insert_benefit->bind_param("iisssii", $empresa_id, $tipo_apoyo_id, $titulo, $descripcion, $fecha_expiracion, $activo, $imagen_documento_id);
        $stmt_insert_benefit->execute();

        $_SESSION['success_message'] = "Beneficio añadido con éxito.";

    } elseif ($action === 'edit') {
        if (!$benefit_id) throw new Exception("ID de beneficio no proporcionado.");
        
        // ... (obtener datos del POST como en 'add') ...
        $titulo = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $tipo_apoyo_id = filter_input(INPUT_POST, 'tipo_apoyo_id', FILTER_VALIDATE_INT);
        $fecha_expiracion = !empty($_POST['fecha_expiracion']) ? $_POST['fecha_expiracion'] : NULL;
        $activo = isset($_POST['activo']) ? 1 : 0;

        $new_imagen_documento_id = NULL;

        // Primero obtenemos el ID del documento actual para saber si existe
        $stmt_get_current_doc = $conexion->prepare("SELECT imagen_documento_id FROM empresas_apoyos WHERE id = ? AND empresa_id = ?");
        $stmt_get_current_doc->bind_param("ii", $benefit_id, $empresa_id);
        $stmt_get_current_doc->execute();
        $current_imagen_documento_id = $stmt_get_current_doc->get_result()->fetch_assoc()['imagen_documento_id'] ?? NULL;
        $stmt_get_current_doc->close();
        
        $new_imagen_documento_id = $current_imagen_documento_id;

        if (isset($_FILES['imagen_beneficio']) && $_FILES['imagen_beneficio']['error'] == UPLOAD_ERR_OK) {
            // Si se sube una imagen nueva, se elimina la anterior (si existe)
            if ($current_imagen_documento_id) {
                $stmt_get_old_path = $conexion->prepare("SELECT ruta_archivo FROM documentos WHERE id = ?");
                $stmt_get_old_path->bind_param("i", $current_imagen_documento_id);
                $stmt_get_old_path->execute();
                $old_web_path = $stmt_get_old_path->get_result()->fetch_assoc()['ruta_archivo'] ?? null;
                $stmt_get_old_path->close();

                if ($old_web_path) {
                    $old_file_system_path = __DIR__ . '/../' . $old_web_path;
                    if (file_exists($old_file_system_path)) unlink($old_file_system_path);
                }
                
                $stmt_delete_doc = $conexion->prepare("DELETE FROM documentos WHERE id = ?");
                $stmt_delete_doc->bind_param("i", $current_imagen_documento_id);
                $stmt_delete_doc->execute();
                $stmt_delete_doc->close();
            }
            
            // Y se sube la nueva imagen (lógica similar a 'add')
            // ... (validaciones de archivo) ...
            $file_info = $_FILES['imagen_beneficio'];
            $new_file_name = uniqid('img_benefit_') . '.' . strtolower(pathinfo($file_info['name'], PATHINFO_EXTENSION));
            $file_system_path = $file_system_base_dir . $new_file_name;
            $web_path = $web_base_path . $new_file_name;

            if(move_uploaded_file($file_info['tmp_name'], $file_system_path)) {
                $stmt_get_tipo = $conexion->prepare("SELECT id FROM tipos_documento WHERE codigo = 'IMAGEN_BENEFICIO'");
                $stmt_get_tipo->execute();
                $tipo_documento_id_img_benefit = $stmt_get_tipo->get_result()->fetch_assoc()['id'];
                $stmt_get_tipo->close();

                $sql_insert_doc = "INSERT INTO documentos (propietario_id, tipo_documento_id, ruta_archivo, nombre_original, tipo_mime) VALUES (?, ?, ?, ?, ?)";
                $stmt_insert_doc = $conexion->prepare($sql_insert_doc);
                // --- CORRECCIÓN CLAVE: Usar $admin_id también al editar ---
                $stmt_insert_doc->bind_param("iisss", $admin_id, $tipo_documento_id_img_benefit, $web_path, $file_info['name'], $file_info['type']);
                $stmt_insert_doc->execute();
                $new_imagen_documento_id = $conexion->insert_id;
                $stmt_insert_doc->close();
            }
        }
        
        $sql_update_benefit = "UPDATE empresas_apoyos SET tipo_apoyo_id = ?, titulo = ?, descripcion = ?, fecha_expiracion = ?, activo = ?, imagen_documento_id = ? WHERE id = ? AND empresa_id = ?";
        $stmt_update_benefit = $conexion->prepare($sql_update_benefit);
        $stmt_update_benefit->bind_param("isssiiii", $tipo_apoyo_id, $titulo, $descripcion, $fecha_expiracion, $activo, $new_imagen_documento_id, $benefit_id, $empresa_id);
        $stmt_update_benefit->execute();
        
        $_SESSION['success_message'] = "Beneficio actualizado con éxito.";
        
    } elseif ($action === 'delete') {
        if (!$benefit_id) throw new Exception("ID de beneficio no proporcionado.");

        // Obtenemos el ID del documento para borrarlo antes de eliminar el beneficio
        $stmt_get_doc_id = $conexion->prepare("SELECT imagen_documento_id FROM empresas_apoyos WHERE id = ? AND empresa_id = ?");
        $stmt_get_doc_id->bind_param("ii", $benefit_id, $empresa_id);
        $stmt_get_doc_id->execute();
        $doc_id_to_delete = $stmt_get_doc_id->get_result()->fetch_assoc()['imagen_documento_id'] ?? null;
        $stmt_get_doc_id->close();

        if ($doc_id_to_delete) {
            $stmt_get_path = $conexion->prepare("SELECT ruta_archivo FROM documentos WHERE id = ?");
            $stmt_get_path->bind_param("i", $doc_id_to_delete);
            $stmt_get_path->execute();
            $path_to_delete = $stmt_get_path->get_result()->fetch_assoc()['ruta_archivo'] ?? null;
            $stmt_get_path->close();

            if ($path_to_delete) {
                $file_system_path_to_delete = __DIR__ . '/../' . $path_to_delete;
                if (file_exists($file_system_path_to_delete)) unlink($file_system_path_to_delete);
            }

            $stmt_delete_doc = $conexion->prepare("DELETE FROM documentos WHERE id = ?");
            $stmt_delete_doc->bind_param("i", $doc_id_to_delete);
            $stmt_delete_doc->execute();
            $stmt_delete_doc->close();
        }
        
        // Finalmente eliminamos el beneficio. La FK se borrará en cascada si está configurada,
        // pero es más seguro borrar el documento explícitamente como arriba.
        $stmt_delete_benefit = $conexion->prepare("DELETE FROM empresas_apoyos WHERE id = ? AND empresa_id = ?");
        $stmt_delete_benefit->bind_param("ii", $benefit_id, $empresa_id);
        $stmt_delete_benefit->execute();

        $_SESSION['success_message'] = "Beneficio eliminado con éxito.";
    }

    $conexion->commit();
    header('Location: ../empresa_beneficios.php');

} catch (Exception $e) {
    $conexion->rollback();
    // No mostrar $e->getMessage() directamente en producción por seguridad.
    error_log("Error en gestionar_beneficios_empresa.php: " . $e->getMessage()); 
    $_SESSION['error_message'] = "Ocurrió un error en la operación. Por favor, inténtelo de nuevo.";
    header('Location: ../empresa_beneficios.php');
} finally {
    if ($conexion) $conexion->close();
    exit();
}
?>