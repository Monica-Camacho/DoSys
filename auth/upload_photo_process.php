<?php
// 1. Incluimos archivos de configuración e iniciamos sesión
require_once '../config.php';
require_once '../conexion_local.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
session_start();

// 2. Verificamos que el usuario esté logueado
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['id'])) {
    // Si no está logueado, lo sacamos.
    header('Location: ' . BASE_URL . 'login.php');
    exit;
}

$usuario_id = $_SESSION['id'];

// 3. Verificamos que se haya subido un archivo y sin errores
if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {

    $file = $_FILES['profile_photo'];

    // 4. Validamos el archivo (tipo y tamaño)
    $allowed_types = ['image/jpeg', 'image/png'];
    $max_size = 5 * 1024 * 1024; // 5 MB

    if (!in_array($file['type'], $allowed_types)) {
        die("Error: Tipo de archivo no permitido. Solo se aceptan imágenes JPG y PNG.");
    }

    if ($file['size'] > $max_size) {
        die("Error: El archivo es demasiado grande. El máximo permitido es 5 MB.");
    }

    // 5. Creamos un nombre de archivo único y seguro
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_filename = 'user_' . $usuario_id . '_' . uniqid() . '.' . $file_extension;

    // 6. Definimos la carpeta de destino y la creamos si no existe
    $upload_dir = '../uploads/profile_pictures/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    $destination_path = $upload_dir . $new_filename;

    // 7. Movemos el archivo a su destino final
    if (move_uploaded_file($file['tmp_name'], $destination_path)) {
        
        // La ruta que guardaremos en la BD (relativa a la raíz del proyecto)
        $db_path = 'uploads/profile_pictures/' . $new_filename;

        try {
            $conexion->begin_transaction();

            // 8. Borramos la foto de perfil anterior si existe
            // Buscamos el ID del tipo de documento para 'FOTO_PERFIL_PERSONA'
            $tipo_doc_id = 1; // Asumiendo que el ID 1 corresponde a FOTO_PERFIL_PERSONA
            
            $stmt_delete = $conexion->prepare("DELETE FROM documentos WHERE propietario_id = ? AND tipo_documento_id = ?");
            $stmt_delete->bind_param("ii", $usuario_id, $tipo_doc_id);
            $stmt_delete->execute();
            $stmt_delete->close();
            // Nota: Esto no borra el archivo físico del servidor, solo la referencia en la BD.
            // Borrar el archivo físico se puede implementar después para optimizar.

            // 9. Insertamos el nuevo registro en la tabla `documentos`
            $stmt_insert = $conexion->prepare(
                "INSERT INTO documentos (propietario_id, tipo_documento_id, ruta_archivo, nombre_original, tipo_mime) VALUES (?, ?, ?, ?, ?)"
            );
            $stmt_insert->bind_param("iisss", $usuario_id, $tipo_doc_id, $db_path, $file['name'], $file['type']);
            
            if ($stmt_insert->execute()) {
                $conexion->commit();
                // Redirigimos de vuelta al perfil con un mensaje de éxito
                header("Location: " . BASE_URL . "persona_perfil.php?upload=success");
                exit();
            } else {
                throw new Exception("No se pudo guardar la información en la base de datos.");
            }

        } catch (Exception $e) {
            $conexion->rollback();
            // Si falla, borramos la foto que acabamos de subir para no dejar basura
            if (file_exists($destination_path)) {
                unlink($destination_path);
            }
            die("Error al procesar la imagen: " . $e->getMessage());
        }

    } else {
        die("Error: No se pudo mover el archivo subido al servidor.");
    }
} else {
    // Redirigimos si no se subió un archivo o hubo un error
    header("Location: " . BASE_URL . "persona_perfil.php?upload=error");
    exit();
}
?>
