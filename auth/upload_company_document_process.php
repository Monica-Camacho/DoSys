<?php
// auth/upload_company_document_process.php

require_once '../config.php';
require_once '../conexion_local.php';
session_start();

// Seguridad: Verificar que el usuario esté logueado y sea una empresa
if (!isset($_SESSION['loggedin']) || $_SESSION['tipo_usuario_id'] != 2) {
    header('HTTP/1.1 403 Forbidden');
    exit(json_encode(['success' => false, 'message' => 'Acceso denegado.']));
}

$usuario_id = $_SESSION['id'];
$response = ['success' => false, 'message' => ''];

if (isset($_FILES['company_document_file']) && $_FILES['company_document_file']['error'] == 0) {
    $allowed_types = ['application/pdf', 'image/jpeg', 'image/png'];
    $max_size = 5 * 1024 * 1024; // 5 MB

    $file_type = $_FILES['company_document_file']['type'];
    $file_size = $_FILES['company_document_file']['size'];

    // 1. Validaciones
    if (!in_array($file_type, $allowed_types)) {
        $response['message'] = 'Error: Solo se permiten archivos PDF, JPG o PNG.';
    } elseif ($file_size > $max_size) {
        $response['message'] = 'Error: El archivo no debe superar los 5 MB.';
    } else {
        // 2. Procesar y guardar el archivo
        $upload_dir = '../uploads/company_validation/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_extension = pathinfo($_FILES['company_document_file']['name'], PATHINFO_EXTENSION);
        $new_filename = 'comp_valid_' . $usuario_id . '_' . uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $new_filename;

        if (move_uploaded_file($_FILES['company_document_file']['tmp_name'], $upload_path)) {
            // 3. Guardar la ruta en la base de datos
            $db_path = 'uploads/company_validation/' . $new_filename;
            $tipo_documento_id = 4; // ID para "DOC_VALIDACION_EMP" de tu tabla 'tipos_documento'

            $sql = "INSERT INTO documentos (propietario_id, tipo_documento_id, ruta_archivo, nombre_original) VALUES (?, ?, ?, ?)";
            if ($stmt = $conexion->prepare($sql)) {
                $stmt->bind_param("iiss", $usuario_id, $tipo_documento_id, $db_path, $_FILES['company_document_file']['name']);
                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'Documento subido con éxito.';
                    $response['new_document_url'] = BASE_URL . $db_path;
                } else {
                    $response['message'] = 'Error al guardar en la base de datos.';
                }
                $stmt->close();
            } else {
                $response['message'] = 'Error al preparar la consulta.';
            }
        } else {
            $response['message'] = 'Error al mover el archivo.';
        }
    }
} else {
    $response['message'] = 'No se recibió ningún archivo.';
}

$conexion->close();
header('Content-Type: application/json');
echo json_encode($response);
?>