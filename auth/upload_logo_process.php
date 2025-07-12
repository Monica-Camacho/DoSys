<?php
// auth/upload_logo_process.php

require_once '../config.php';
require_once '../conexion_local.php';
session_start();

// Preparamos la respuesta por defecto
$response = ['success' => false, 'message' => 'Un error inesperado ocurrió.'];

// Seguridad: Verificar que el usuario esté logueado y sea una empresa
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipo_usuario_id'] != 2) {
    header('HTTP/1.1 403 Forbidden');
    $response['message'] = 'Acceso denegado.';
    echo json_encode($response);
    exit;
}

$usuario_id = $_SESSION['id'];

// Verificamos que se haya subido un archivo sin errores
if (isset($_FILES['logoFile']) && $_FILES['logoFile']['error'] == 0) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024; // 5 MB

    $file_type = $_FILES['logoFile']['type'];
    $file_size = $_FILES['logoFile']['size'];

    // Validaciones
    if (!in_array($file_type, $allowed_types)) {
        $response['message'] = 'Error: Solo se permiten archivos JPG, PNG o GIF.';
    } elseif ($file_size > $max_size) {
        $response['message'] = 'Error: El archivo no debe superar los 5 MB.';
    } else {
        // --- INICIO DE LA LÓGICA DE SUBIDA Y ACTUALIZACIÓN ---

        // Preparamos la ruta y el nombre del archivo
        $upload_dir = '../uploads/logos/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_extension = pathinfo($_FILES['logoFile']['name'], PATHINFO_EXTENSION);
        $new_filename = 'logo_' . $usuario_id . '_' . uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $new_filename;
        $db_path = 'uploads/logos/' . $new_filename; // Ruta relativa para la BD

        // Iniciamos la transacción para asegurar la integridad de los datos
        $conexion->begin_transaction();

        try {
            // 1. Insertar el nuevo documento
            $tipo_documento_id = 2; // ID para "Logo de Empresa"
            $sql_insert = "INSERT INTO documentos (propietario_id, tipo_documento_id, ruta_archivo, nombre_original) VALUES (?, ?, ?, ?)";
            $stmt_insert = $conexion->prepare($sql_insert);
            $original_name = $_FILES['logoFile']['name'];
            $stmt_insert->bind_param("iiss", $usuario_id, $tipo_documento_id, $db_path, $original_name);
            $stmt_insert->execute();

            // Obtenemos el ID del documento que acabamos de insertar
            $nuevo_documento_id = $stmt_insert->insert_id;
            $stmt_insert->close();

            // 2. **EL PASO CRUCIAL QUE FALTABA**
            // Actualizamos el perfil de la empresa para enlazar el nuevo logo.
            $sql_update = "UPDATE empresas_perfil ep
                           JOIN usuarios_x_empresas uxe ON ep.id = uxe.empresa_id
                           SET ep.logo_documento_id = ?
                           WHERE uxe.usuario_id = ?";
            $stmt_update = $conexion->prepare($sql_update);
            $stmt_update->bind_param("ii", $nuevo_documento_id, $usuario_id);
            $stmt_update->execute();
            $stmt_update->close();

            // 3. Mover el archivo físico al servidor
            if (move_uploaded_file($_FILES['logoFile']['tmp_name'], $upload_path)) {
                // Si todo salió bien, confirmamos la transacción
                $conexion->commit();
                $response['success'] = true;
                $response['message'] = '¡Logo actualizado con éxito!';
                $response['new_logo_url'] = BASE_URL . $db_path;
            } else {
                throw new Exception('Error al mover el archivo subido.');
            }
        } catch (Exception $e) {
            // Si algo falla, revertimos todos los cambios en la BD
            $conexion->rollback();
            $response['message'] = 'Error en el proceso: ' . $e->getMessage();
            // Si el archivo se movió pero la BD falló, sería bueno eliminarlo
            if (file_exists($upload_path)) {
                unlink($upload_path);
            }
        }
    }
} else {
    $response['message'] = 'No se recibió ningún archivo o hubo un error en la subida.';
}

$conexion->close();
header('Content-Type: application/json');
echo json_encode($response);
?>