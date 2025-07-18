<?php
// 1. Incluimos archivos de configuración e iniciamos sesión
require_once '../config.php';
require_once '../conexion_local.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
session_start();

// 2. Verificamos que el usuario esté logueado
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['id'])) {
    header('Location: ' . BASE_URL . 'login.php');
    exit;
}

$usuario_id = $_SESSION['id'];

// 3. Verificamos que se haya subido un archivo y el tipo de documento
if (isset($_FILES['document_file']) && $_FILES['document_file']['error'] === UPLOAD_ERR_OK && isset($_POST['document_type_code'])) {

    $file = $_FILES['document_file'];
    $document_type_code = $_POST['document_type_code'];

    // 4. Validamos el archivo (tipo y tamaño)
    $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
    $max_size = 5 * 1024 * 1024; // 5 MB

    if (!in_array($file['type'], $allowed_types)) {
        die("Error: Tipo de archivo no permitido. Solo se aceptan PDF, JPG y PNG.");
    }

    if ($file['size'] > $max_size) {
        die("Error: El archivo es demasiado grande. El máximo permitido es 5 MB.");
    }

    try {
        // 5. Obtenemos el ID del tipo de documento desde la base de datos
        $stmt_type = $conexion->prepare("SELECT id FROM tipos_documento WHERE codigo = ?");
        $stmt_type->bind_param("s", $document_type_code);
        $stmt_type->execute();
        $resultado_type = $stmt_type->get_result();
        if ($resultado_type->num_rows === 0) {
            throw new Exception("El tipo de documento especificado no es válido.");
        }
        $tipo_documento_id = $resultado_type->fetch_assoc()['id'];
        $stmt_type->close();

        // 6. Creamos un nombre de archivo único y seguro
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_filename = 'doc_' . $usuario_id . '_' . uniqid() . '.' . $file_extension;

        // 7. Definimos la carpeta de destino y la creamos si no existe
        // Usaremos una carpeta genérica para todos los documentos de validación
        $upload_dir = '../uploads/documents_validation/user/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $destination_path = $upload_dir . $new_filename;

        // 8. Movemos el archivo a su destino final
        if (move_uploaded_file($file['tmp_name'], $destination_path)) {
            
            $db_path = 'uploads/documents_validation/user/' . $new_filename;

            $conexion->begin_transaction();
            
            // 9. Borramos el documento anterior del mismo tipo si existe
            $stmt_delete = $conexion->prepare("DELETE FROM documentos WHERE propietario_id = ? AND tipo_documento_id = ?");
            $stmt_delete->bind_param("ii", $usuario_id, $tipo_documento_id);
            $stmt_delete->execute();
            $stmt_delete->close();

            // 10. Insertamos el nuevo registro en la tabla `documentos`
            $stmt_insert = $conexion->prepare(
                "INSERT INTO documentos (propietario_id, tipo_documento_id, ruta_archivo, nombre_original, tipo_mime) VALUES (?, ?, ?, ?, ?)"
            );
            $stmt_insert->bind_param("iisss", $usuario_id, $tipo_documento_id, $db_path, $file['name'], $file['type']);
            
            if ($stmt_insert->execute()) {
                $conexion->commit();
                // Redirigimos de vuelta al perfil con un mensaje de éxito
                header("Location: " . BASE_URL . "persona_perfil.php?upload_doc=success");
                exit();
            } else {
                throw new Exception("No se pudo guardar la información en la base de datos.");
            }

        } else {
            throw new Exception("No se pudo mover el archivo subido al servidor.");
        }

    } catch (Exception $e) {
        if ($conexion->in_transaction) {
            $conexion->rollback();
        }
        // Si falla, borramos el archivo que acabamos de subir para no dejar basura
        if (isset($destination_path) && file_exists($destination_path)) {
            unlink($destination_path);
        }
        die("Error al procesar el documento: " . $e->getMessage());
    }

} else {
    // Redirigimos si no se subió un archivo o hubo un error
    header("Location: " . BASE_URL . "persona_perfil.php?upload_doc=error");
    exit();
}
?>
