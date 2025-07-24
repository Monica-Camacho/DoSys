<?php
require_once '../config.php';
require_once '../conexion_local.php';
session_start();

// Directorio base para guardar las imágenes de beneficios en el sistema de archivos
// Se asume que la raíz del proyecto es C:\xampp\htdocs\DoSys\
// y que las imágenes se guardarán en C:\xampp\htdocs\DoSys\uploads\beneficios\
// La ruta absoluta es más robusta para el sistema de archivos.
// CORRECCIÓN: Ajuste de la ruta para el sistema de archivos.
// __DIR__ es C:\xampp\htdocs\DoSys\auth\
// '/../' sube a C:\xampp\htdocs\DoSys\
// 'uploads/beneficios/' entra en la carpeta de destino.
$file_system_base_dir = __DIR__ . '/../uploads/beneficios/';

// Asegurarse de que el directorio exista y tenga permisos de escritura
if (!is_dir($file_system_base_dir)) {
    mkdir($file_system_base_dir, 0777, true); // Crear el directorio si no existe
}

// 1. VERIFICACIONES DE SEGURIDAD INICIAL
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['id'])) {
    header('Location: ../index.php');
    exit();
}
$admin_id = $_SESSION['id'];

// Obtener la empresa_id y el rol del usuario logueado
$sql_permisos = "SELECT ue.empresa_id, u.rol_id
                 FROM usuarios_x_empresas ue
                 JOIN usuarios u ON ue.usuario_id = u.id
                 WHERE ue.usuario_id = ?";
$stmt_permisos = $conexion->prepare($sql_permisos);
$stmt_permisos->bind_param("i", $admin_id);
$stmt_permisos->execute();
$resultado_permisos = $stmt_permisos->get_result();

if ($resultado_permisos->num_rows === 0) {
    $_SESSION['error_message'] = "No tienes permiso para realizar esta acción o no estás asociado a una empresa.";
    header('Location: ../empresa_beneficios.php'); // Redirigir a la gestión de beneficios
    exit();
}

$permisos = $resultado_permisos->fetch_assoc();
$empresa_id = $permisos['empresa_id'];
$rol_admin = $permisos['rol_id'];
$stmt_permisos->close();

// Solo los administradores (rol_id = 1) de la empresa pueden gestionar beneficios
if ($rol_admin != 1) {
    $_SESSION['error_message'] = "No tienes permiso para gestionar beneficios de la empresa.";
    header('Location: ../empresa_beneficios.php');
    exit();
}

$action = $_POST['action'] ?? ''; // 'add', 'edit', 'delete'
$benefit_id = filter_input(INPUT_POST, 'benefit_id', FILTER_VALIDATE_INT);

$conexion->begin_transaction();

try {
    if ($action === 'add') {
        $titulo = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $tipo_apoyo_id = filter_input(INPUT_POST, 'tipo_apoyo_id', FILTER_VALIDATE_INT);
        $fecha_expiracion = $_POST['fecha_expiracion'] ?? NULL;
        $activo = isset($_POST['activo']) ? 1 : 0;

        if (empty($titulo) || empty($descripcion) || !$tipo_apoyo_id) {
            $_SESSION['error_message'] = "Todos los campos obligatorios deben ser completados.";
            header('Location: ../empresa_beneficios.php');
            exit();
        }

        $imagen_documento_id = NULL;
        // Procesa la subida de la imagen si se ha seleccionado una
        if (isset($_FILES['imagen_beneficio']) && $_FILES['imagen_beneficio']['error'] == UPLOAD_ERR_OK) {
            $file_name = $_FILES['imagen_beneficio']['name'];
            $file_tmp_name = $_FILES['imagen_beneficio']['tmp_name'];
            $file_size = $_FILES['imagen_beneficio']['size'];
            $file_type = $_FILES['imagen_beneficio']['type'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $allowed_extensions = ['jpg', 'jpeg', 'png'];
            if (!in_array($file_ext, $allowed_extensions)) {
                $_SESSION['error_message'] = "Solo se permiten imágenes JPG y PNG.";
                header('Location: ../empresa_beneficios.php');
                exit();
            }
            if ($file_size > 2 * 1024 * 1024) { // 2MB
                $_SESSION['error_message'] = "La imagen no debe exceder los 2MB.";
                header('Location: ../empresa_beneficios.php');
                exit();
            }

            $new_file_name = uniqid('img_benefit_') . '.' . $file_ext;
            // Ruta completa en el sistema de archivos del servidor
            $file_system_path = $file_system_base_dir . $new_file_name;
            // Ruta web-accesible para almacenar en la base de datos
            // CORRECCIÓN: La ruta web debe ser relativa a la raíz del dominio web,
            // que en tu caso incluye 'DoSys/'.
            $web_accessible_path = 'DoSys/uploads/beneficios/' . $new_file_name; 

            if (move_uploaded_file($file_tmp_name, $file_system_path)) {
                // Obtener el ID del tipo de documento 'IMAGEN_BENEFICIO'
                $tipo_documento_id_img_benefit = 0;
                $sql_get_tipo_doc_id = "SELECT id FROM tipos_documento WHERE codigo = 'IMAGEN_BENEFICIO'";
                $result_tipo_doc_id = $conexion->query($sql_get_tipo_doc_id);
                if ($row = $result_tipo_doc_id->fetch_assoc()) {
                    $tipo_documento_id_img_benefit = $row['id'];
                } else {
                    throw new Exception("Tipo de documento 'IMAGEN_BENEFICIO' no encontrado. Asegúrate de ejecutar el SQL de inserción.");
                }

                // Insertar en la tabla 'documentos' con la ruta web-accesible
                $sql_insert_doc = "INSERT INTO documentos (propietario_id, tipo_documento_id, ruta_archivo, nombre_original, tipo_mime) VALUES (?, ?, ?, ?, ?)";
                $stmt_insert_doc = $conexion->prepare($sql_insert_doc);
                $stmt_insert_doc->bind_param("iisss", $empresa_id, $tipo_documento_id_img_benefit, $web_accessible_path, $file_name, $file_type);
                $stmt_insert_doc->execute();
                $imagen_documento_id = $conexion->insert_id;
                $stmt_insert_doc->close();
            } else {
                throw new Exception("Error al subir la imagen.");
            }
        }

        // Insertar en la tabla 'empresas_apoyos'
        $sql_insert_benefit = "INSERT INTO empresas_apoyos (empresa_id, tipo_apoyo_id, titulo, descripcion, fecha_expiracion, activo, imagen_documento_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert_benefit = $conexion->prepare($sql_insert_benefit);
        $stmt_insert_benefit->bind_param("iisssii", $empresa_id, $tipo_apoyo_id, $titulo, $descripcion, $fecha_expiracion, $activo, $imagen_documento_id);
        $stmt_insert_benefit->execute();
        $stmt_insert_benefit->close();

        $conexion->commit();
        $_SESSION['success_message'] = "Beneficio añadido con éxito.";
        header('Location: ../empresa_beneficios.php');

    } elseif ($action === 'edit') {
        if (!$benefit_id) {
            throw new Exception("ID de beneficio no proporcionado para edición.");
        }

        $titulo = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $tipo_apoyo_id = filter_input(INPUT_POST, 'tipo_apoyo_id', FILTER_VALIDATE_INT);
        $fecha_expiracion = $_POST['fecha_expiracion'] ?? NULL;
        $activo = isset($_POST['activo']) ? 1 : 0;

        if (empty($titulo) || empty($descripcion) || !$tipo_apoyo_id) {
            $_SESSION['error_message'] = "Todos los campos obligatorios deben ser completados.";
            header('Location: ../empresa_beneficios.php');
            exit();
        }

        // Obtener la imagen_documento_id actual del beneficio y su ruta de archivo en la base de datos
        $current_imagen_documento_id = NULL;
        $current_web_accessible_path = NULL; // Ruta que está en la BD
        $sql_get_current_image = "SELECT ea.imagen_documento_id, d.ruta_archivo FROM empresas_apoyos ea LEFT JOIN documentos d ON ea.imagen_documento_id = d.id WHERE ea.id = ? AND ea.empresa_id = ?";
        $stmt_get_current_image = $conexion->prepare($sql_get_current_image);
        $stmt_get_current_image->bind_param("ii", $benefit_id, $empresa_id);
        $stmt_get_current_image->execute();
        $result_current_image = $stmt_get_current_image->get_result();
        if ($row = $result_current_image->fetch_assoc()) {
            $current_imagen_documento_id = $row['imagen_documento_id'];
            $current_web_accessible_path = $row['ruta_archivo'];
        }
        $stmt_get_current_image->close();

        $new_imagen_documento_id = $current_imagen_documento_id;

        // Procesa la nueva imagen si se ha subido una
        if (isset($_FILES['imagen_beneficio']) && $_FILES['imagen_beneficio']['error'] == UPLOAD_ERR_OK) {
            $file_name = $_FILES['imagen_beneficio']['name'];
            $file_tmp_name = $_FILES['imagen_beneficio']['tmp_name'];
            $file_size = $_FILES['imagen_beneficio']['size'];
            $file_type = $_FILES['imagen_beneficio']['type'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $allowed_extensions = ['jpg', 'jpeg', 'png'];
            if (!in_array($file_ext, $allowed_extensions)) {
                $_SESSION['error_message'] = "Solo se permiten imágenes JPG y PNG.";
                header('Location: ../empresa_beneficios.php');
                exit();
            }
            if ($file_size > 2 * 1024 * 1024) { // 2MB
                $_SESSION['error_message'] = "La imagen no debe exceder los 2MB.";
                header('Location: ../empresa_beneficios.php');
                exit();
            }

            $new_file_name = uniqid('img_benefit_') . '.' . $file_ext;
            $file_system_path = $file_system_base_dir . $new_file_name;
            $web_accessible_path = 'DoSys/uploads/beneficios/' . $new_file_name; // CORRECCIÓN: Asegura que la ruta web sea correcta

            if (move_uploaded_file($file_tmp_name, $file_system_path)) {
                // Si ya existía una imagen, eliminar el archivo antiguo y el registro de documento
                if ($current_imagen_documento_id) {
                    // Eliminar archivo físico antiguo (convertir ruta web a ruta de sistema de archivos)
                    // CORRECCIÓN: Ajuste de la ruta para el sistema de archivos al eliminar.
                    // __DIR__ es C:\xampp\htdocs\DoSys\auth\
                    // '/../' sube a C:\xampp\htdocs\DoSys\
                    // $current_web_accessible_path ya contiene 'DoSys/uploads/beneficios/...'
                    $old_file_system_path = __DIR__ . '/../' . $current_web_accessible_path;
                    if ($old_file_system_path && file_exists($old_file_system_path)) {
                        unlink($old_file_system_path);
                    }
                    // Eliminar registro de la tabla documentos
                    $sql_delete_old_doc = "DELETE FROM documentos WHERE id = ?";
                    $stmt_delete_old_doc = $conexion->prepare($sql_delete_old_doc);
                    $stmt_delete_old_doc->bind_param("i", $current_imagen_documento_id);
                    $stmt_delete_old_doc->execute();
                    $stmt_delete_old_doc->close();
                }

                // Insertar nueva imagen en la tabla 'documentos'
                $sql_insert_doc = "INSERT INTO documentos (propietario_id, tipo_documento_id, ruta_archivo, nombre_original, tipo_mime) VALUES (?, ?, ?, ?, ?)";
                $stmt_insert_doc = $conexion->prepare($sql_insert_doc);
                $tipo_documento_id_img_benefit = 0;
                $sql_get_tipo_doc_id = "SELECT id FROM tipos_documento WHERE codigo = 'IMAGEN_BENEFICIO'";
                $result_tipo_doc_id = $conexion->query($sql_get_tipo_doc_id);
                if ($row = $result_tipo_doc_id->fetch_assoc()) {
                    $tipo_documento_id_img_benefit = $row['id'];
                } else {
                    throw new Exception("Tipo de documento 'IMAGEN_BENEFICIO' no encontrado.");
                }

                $stmt_insert_doc->bind_param("iisss", $empresa_id, $tipo_documento_id_img_benefit, $web_accessible_path, $file_name, $file_type);
                $stmt_insert_doc->execute();
                $new_imagen_documento_id = $conexion->insert_id;
                $stmt_insert_doc->close();
            } else {
                throw new Exception("Error al subir la nueva imagen.");
            }
        }

        // Actualizar en la tabla 'empresas_apoyos'
        $sql_update_benefit = "UPDATE empresas_apoyos SET tipo_apoyo_id = ?, titulo = ?, descripcion = ?, fecha_expiracion = ?, activo = ?, imagen_documento_id = ? WHERE id = ? AND empresa_id = ?";
        $stmt_update_benefit = $conexion->prepare($sql_update_benefit);
        $stmt_update_benefit->bind_param("isssiiii", $tipo_apoyo_id, $titulo, $descripcion, $fecha_expiracion, $activo, $new_imagen_documento_id, $benefit_id, $empresa_id);
        $stmt_update_benefit->execute();
        $stmt_update_benefit->close();

        $conexion->commit();
        $_SESSION['success_message'] = "Beneficio actualizado con éxito.";
        header('Location: ../empresa_beneficios.php');

    } elseif ($action === 'delete') {
        if (!$benefit_id) {
            throw new Exception("ID de beneficio no proporcionado para eliminación.");
        }

        // Obtener la imagen_documento_id y ruta de la imagen para eliminar el archivo físico
        $sql_get_image_to_delete = "SELECT ea.imagen_documento_id, d.ruta_archivo FROM empresas_apoyos ea LEFT JOIN documentos d ON ea.imagen_documento_id = d.id WHERE ea.id = ? AND ea.empresa_id = ?";
        $stmt_get_image_to_delete = $conexion->prepare($sql_get_image_to_delete);
        $stmt_get_image_to_delete->bind_param("ii", $benefit_id, $empresa_id);
        $stmt_get_image_to_delete->execute();
        $result_image_to_delete = $stmt_get_image_to_delete->get_result();
        $image_to_delete_data = $result_image_to_delete->fetch_assoc();
        $stmt_get_image_to_delete->close();

        // Eliminar de la tabla 'empresas_apoyos'
        $sql_delete_benefit = "DELETE FROM empresas_apoyos WHERE id = ? AND empresa_id = ?";
        $stmt_delete_benefit = $conexion->prepare($sql_delete_benefit);
        $stmt_delete_benefit->bind_param("ii", $benefit_id, $empresa_id);
        $stmt_delete_benefit->execute();
        $stmt_delete_benefit->close();

        // Si el beneficio tenía una imagen, eliminar el archivo físico y el registro de documento
        if ($image_to_delete_data && $image_to_delete_data['imagen_documento_id']) {
            // Convertir la ruta web-accesible a una ruta de sistema de archivos para poder eliminarla
            // CORRECCIÓN: Ajuste de la ruta para el sistema de archivos al eliminar.
            $file_to_delete_path = __DIR__ . '/../' . $image_to_delete_data['ruta_archivo'];
            
            if ($file_to_delete_path && file_exists($file_to_delete_path)) {
                unlink($file_to_delete_path);
            }
            $sql_delete_doc = "DELETE FROM documentos WHERE id = ?";
            $stmt_delete_doc = $conexion->prepare($sql_delete_doc);
            $stmt_delete_doc->bind_param("i", $image_to_delete_data['imagen_documento_id']);
            $stmt_delete_doc->execute();
            $stmt_delete_doc->close();
        }

        $conexion->commit();
        $_SESSION['success_message'] = "Beneficio eliminado con éxito.";
        header('Location: ../empresa_beneficios.php');

    } else {
        $_SESSION['error_message'] = "Acción no válida.";
        header('Location: ../empresa_beneficios.php');
    }
} catch (Exception $e) {
    $conexion->rollback();
    $_SESSION['error_message'] = "Ocurrió un error: " . $e->getMessage();
    header('Location: ../empresa_beneficios.php');
} finally {
    if ($conexion) $conexion->close();
    exit();
}
?>
