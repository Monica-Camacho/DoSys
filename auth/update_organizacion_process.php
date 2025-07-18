<?php
// 1. INICIALIZACIÓN Y SEGURIDAD
// =================================================================
require_once '../config.php';
require_once '../conexion_local.php'; // Usamos tu variable de conexión $conexion
session_start();

// Doble verificación: que el usuario esté logueado y que sea Administrador
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ' . BASE_URL . 'login.php');
    exit;
}

// Verificamos el rol del usuario directamente en la BD para máxima seguridad
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

// Si no es Administrador, lo expulsamos.
if ($rol_usuario !== 'Administrador') {
    header('Location: ' . BASE_URL . 'organizacion_perfil.php?status=unauthorized');
    exit;
}


// 2. RECEPCIÓN DE DATOS DEL FORMULARIO (POST)
// =================================================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Datos de la organización
    $organizacion_id = $_POST['organizacion_id']; // ID oculto del formulario
    $nombre_organizacion = $_POST['nombre_organizacion'];
    $cluni = $_POST['cluni'];
    $mision = $_POST['mision'];

    // Datos de la dirección
    $calle = $_POST['calle'];
    $numero_exterior = $_POST['numero_exterior'];
    $numero_interior = $_POST['numero_interior'];
    $colonia = $_POST['colonia'];
    $codigo_postal = $_POST['codigo_postal'];
    $municipio = $_POST['municipio'];
    $estado = $_POST['estado'];
    $latitud = $_POST['latitud'];
    $longitud = $_POST['longitud'];

    // Datos del representante
    $rep_nombre = $_POST['rep_nombre'];
    $rep_apellido_p = $_POST['rep_apellido_p'];
    $rep_apellido_m = $_POST['rep_apellido_m'];
    $rep_email = $_POST['rep_email'];
    $rep_telefono = $_POST['rep_telefono'];

    // 3. ACTUALIZACIÓN EN BASE DE DATOS (CON TRANSACCIÓN)
    // =================================================================
    
    // Iniciamos una transacción
    $conexion->begin_transaction();
    $error = false;

    try {
        // --- Primero, obtenemos los IDs de dirección y representante de la organización ---
        $stmt_ids = $conexion->prepare("SELECT direccion_id, representante_id FROM organizaciones_perfil WHERE id = ?");
        $stmt_ids->bind_param("i", $organizacion_id);
        $stmt_ids->execute();
        $res_ids = $stmt_ids->get_result();
        $ids = $res_ids->fetch_assoc();
        $direccion_id = $ids['direccion_id'];
        $representante_id = $ids['representante_id'];
        $stmt_ids->close();

        // --- UPDATE 1: Tabla 'organizaciones_perfil' ---
        $sql1 = "UPDATE organizaciones_perfil SET nombre_organizacion = ?, cluni = ?, mision = ? WHERE id = ?";
        $stmt1 = $conexion->prepare($sql1);
        $stmt1->bind_param("sssi", $nombre_organizacion, $cluni, $mision, $organizacion_id);
        if (!$stmt1->execute()) $error = true;
        $stmt1->close();

        // --- UPDATE 2: Tabla 'direcciones' ---
        if (!$error && $direccion_id) {
            $sql2 = "UPDATE direcciones SET calle = ?, numero_exterior = ?, numero_interior = ?, colonia = ?, codigo_postal = ?, municipio = ?, estado = ?, latitud = ?, longitud = ? WHERE id = ?";
            $stmt2 = $conexion->prepare($sql2);
            $stmt2->bind_param("sssssssssi", $calle, $numero_exterior, $numero_interior, $colonia, $codigo_postal, $municipio, $estado, $latitud, $longitud, $direccion_id);
            if (!$stmt2->execute()) $error = true;
            $stmt2->close();
        }

        // --- UPDATE 3: Tabla 'representantes' ---
        if (!$error && $representante_id) {
            $sql3 = "UPDATE representantes SET nombre = ?, apellido_paterno = ?, apellido_materno = ?, email = ?, telefono = ? WHERE id = ?";
            $stmt3 = $conexion->prepare($sql3);
            $stmt3->bind_param("sssssi", $rep_nombre, $rep_apellido_p, $rep_apellido_m, $rep_email, $rep_telefono, $representante_id);
            if (!$stmt3->execute()) $error = true;
            $stmt3->close();
        }

        // --- Finalizamos la transacción ---
        if ($error) {
            $conexion->rollback(); // Si algo falló, revertimos todos los cambios
            header('Location: ' . BASE_URL . 'organizacion_perfil.php?status=error');
        } else {
            $conexion->commit(); // Si todo fue exitoso, guardamos los cambios
            header('Location: ' . BASE_URL . 'organizacion_perfil.php?status=success');
        }

    } catch (Exception $e) {
        $conexion->rollback();
        // Para depuración: error_log($e->getMessage());
        header('Location: ' . BASE_URL . 'organizacion_perfil.php?status=exception');
    }

    $conexion->close();
    exit;

} else {
    // Si alguien intenta acceder a este archivo directamente
    header('Location: ' . BASE_URL . 'organizacion_perfil.php');
    exit;
}
?>