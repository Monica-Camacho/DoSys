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

// Verificamos el rol del usuario en la base de datos para máxima seguridad
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
    header('Location: ' . BASE_URL . 'empresa_perfil.php?status=unauthorized');
    exit;
}

// 2. RECEPCIÓN DE DATOS DEL FORMULARIO (POST)
// =================================================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Datos de la empresa
    $empresa_id = $_POST['empresa_id'];
    $nombre_comercial = $_POST['nombre_comercial'];
    $razon_social = $_POST['razon_social'];
    $rfc = $_POST['rfc'];
    $telefono_empresa = $_POST['telefono_empresa'];
    $descripcion = $_POST['descripcion'];

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
    
    $conexion->begin_transaction();
    $error = false;

    try {
        // Obtenemos los IDs de dirección y representante
        $stmt_ids = $conexion->prepare("SELECT direccion_comercial_id, representante_id FROM empresas_perfil WHERE id = ?");
        $stmt_ids->bind_param("i", $empresa_id);
        $stmt_ids->execute();
        $res_ids = $stmt_ids->get_result();
        $ids = $res_ids->fetch_assoc();
        $direccion_id = $ids['direccion_comercial_id'];
        $representante_id = $ids['representante_id'];
        $stmt_ids->close();

        // --- UPDATE 1: Tabla 'empresas_perfil' ---
        $sql1 = "UPDATE empresas_perfil SET nombre_comercial = ?, razon_social = ?, rfc = ?, telefono_empresa = ?, descripcion = ? WHERE id = ?";
        $stmt1 = $conexion->prepare($sql1);
        $stmt1->bind_param("sssssi", $nombre_comercial, $razon_social, $rfc, $telefono_empresa, $descripcion, $empresa_id);
        if (!$stmt1->execute()) $error = true;
        $stmt1->close();

        // =========================================================
        // === INICIO DE LA LÓGICA PARA DIRECCIÓN (INSERT/UPDATE) ===
        // =========================================================
        if (!$error) {
            if ($direccion_id) {
                // Si la empresa YA TIENE una dirección, la ACTUALIZAMOS.
                $stmt_dir = $conexion->prepare(
                    "UPDATE direcciones SET calle = ?, numero_exterior = ?, numero_interior = ?, colonia = ?, codigo_postal = ?, municipio = ?, estado = ?, latitud = ?, longitud = ? WHERE id = ?"
                );
                $stmt_dir->bind_param("sssssssssi", $calle, $numero_exterior, $numero_interior, $colonia, $codigo_postal, $municipio, $estado, $latitud, $longitud, $direccion_id);
                if (!$stmt_dir->execute()) $error = true;
                $stmt_dir->close();
            } else {
                // Si la empresa NO TIENE una dirección, INSERTAMOS una nueva.
                $stmt_dir = $conexion->prepare(
                    "INSERT INTO direcciones (calle, numero_exterior, numero_interior, colonia, codigo_postal, municipio, estado, latitud, longitud) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
                );
                $stmt_dir->bind_param("sssssssss", $calle, $numero_exterior, $numero_interior, $colonia, $codigo_postal, $municipio, $estado, $latitud, $longitud);
                if (!$stmt_dir->execute()) {
                    $error = true;
                } else {
                    // Obtenemos el ID de la nueva dirección
                    $new_direccion_id = $conexion->insert_id;
                    $stmt_dir->close();

                    // Y vinculamos este nuevo ID a la empresa
                    $stmt_link = $conexion->prepare("UPDATE empresas_perfil SET direccion_comercial_id = ? WHERE id = ?");
                    $stmt_link->bind_param("ii", $new_direccion_id, $empresa_id);
                    if (!$stmt_link->execute()) $error = true;
                    $stmt_link->close();
                }
            }
        }
        // =========================================================
        // === FIN DE LA LÓGICA PARA DIRECCIÓN ===
        // =========================================================

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
            $conexion->rollback();
            header('Location: ' . BASE_URL . 'empresa_perfil.php?status=error');
        } else {
            $conexion->commit();
            header('Location: ' . BASE_URL . 'empresa_perfil.php?status=success');
        }

    } catch (Exception $e) {
        $conexion->rollback();
        header('Location: ' . BASE_URL . 'empresa_perfil.php?status=exception');
    }

    $conexion->close();
    exit;

} else {
    header('Location: ' . BASE_URL . 'empresa_perfil.php');
    exit;
}
?>