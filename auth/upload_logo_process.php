<?php
// auth/upload_logo_process.php

require_once '../config.php';
require_once '../conexion_local.php';
session_start();

// Seguridad: Verificar que el usuario esté logueado y sea una empresa
if (!isset($_SESSION['loggedin']) || $_SESSION['tipo_usuario_id'] != 2) {
    header('HTTP/1.1 403 Forbidden');
    exit('Acceso denegado.');
}

$usuario_id = $_SESSION['id'];
$response = ['success' => false, 'message' => ''];

if (isset($_FILES['logoFile']) && $_FILES['logoFile']['error'] == 0) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024; // 5 MB

    $file_type = $_FILES['logoFile']['type'];
    $file_size = $_FILES['logoFile']['size'];

    // 1. Validar tipo de archivo
    if (!in_array($file_type, $allowed_types)) {
        $response['message'] = 'Error: Solo se permiten archivos JPG, PNG o GIF.';
    } 
    // 2. Validar tamaño del archivo
    elseif ($file_size > $max_size) {
        $response['message'] = 'Error: El archivo no debe superar los 5 MB.';
    } else {
        // 3. Procesar y guardar el archivo
        $upload_dir = '../uploads/logos/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_extension = pathinfo($_FILES['logoFile']['name'], PATHINFO_EXTENSION);
        $unique_id = uniqid(); // Genera un ID único
        $new_filename = 'logo_' . $usuario_id . '_' . $unique_id . '.' . $file_extension;
        $upload_path = $upload_dir . $new_filename;

        if (move_uploaded_file($_FILES['logoFile']['tmp_name'], $upload_path)) {
            // 4. Guardar la ruta en la base de datos
            $db_path = 'uploads/logos/' . $new_filename;
            $tipo_documento_id = 2; // ID para "Logo de Empresa" en tu tabla 'tipos_documento'

            $sql = "INSERT INTO documentos (propietario_id, tipo_documento_id, ruta_archivo, nombre_archivo) VALUES (?, ?, ?, ?)";
            if ($stmt = $conexion->prepare($sql)) {
                $stmt->bind_param("iiss", $usuario_id, $tipo_documento_id, $db_path, $new_filename);
                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'Logo subido con éxito.';
                    $response['new_logo_url'] = BASE_URL . $db_path; // Devuelve la URL completa
                } else {
                    $response['message'] = 'Error al guardar la información en la base de datos.';
                }
                $stmt->close();
            } else {
                $response['message'] = 'Error al preparar la consulta.';
            }
        } else {
            $response['message'] = 'Error al mover el archivo subido.';
        }
    }
} else {
    $response['message'] = 'No se recibió ningún archivo o hubo un error en la subida.';
}

$conexion->close();
header('Content-Type: application/json');
echo json_encode($response);
?>