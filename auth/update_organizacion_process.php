<?php
// 1. INICIALIZACIÓN Y SEGURIDAD
// =================================================================
require_once '../config.php';
require_once '../conexion_local.php';
session_start();

// Doble verificación de seguridad (logueado y rol de Administrador)
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ' . BASE_URL . 'login.php');
    exit;
}

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
    header('Location: ' . BASE_URL . 'organizacion_perfil.php?status=unauthorized');
    exit;
}

// 2. RECEPCIÓN DE DATOS DEL FORMULARIO (POST)
// =================================================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Datos de la organización
    $organizacion_id = $_POST['organizacion_id'];
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

    // --- ¡NUEVO! RECEPCIÓN DE CATEGORÍAS ---
    $categorias_seleccionadas = $_POST['categorias'] ?? [];


    // 3. ACTUALIZACIÓN EN BASE DE DATOS (CON TRANSACCIÓN)
    // =================================================================
    
    $conexion->begin_transaction();
    $error = false;

    try {
        // Obtenemos los IDs existentes
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

        // --- LÓGICA PARA DIRECCIÓN (INSERT/UPDATE) ---
        if (!$error) {
            if ($direccion_id) {
                $stmt_dir = $conexion->prepare("UPDATE direcciones SET calle = ?, numero_exterior = ?, numero_interior = ?, colonia = ?, codigo_postal = ?, municipio = ?, estado = ?, latitud = ?, longitud = ? WHERE id = ?");
                $stmt_dir->bind_param("sssssssssi", $calle, $numero_exterior, $numero_interior, $colonia, $codigo_postal, $municipio, $estado, $latitud, $longitud, $direccion_id);
                if (!$stmt_dir->execute()) $error = true;
                $stmt_dir->close();
            } else {
                $stmt_dir = $conexion->prepare("INSERT INTO direcciones (calle, numero_exterior, numero_interior, colonia, codigo_postal, municipio, estado, latitud, longitud) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt_dir->bind_param("sssssssss", $calle, $numero_exterior, $numero_interior, $colonia, $codigo_postal, $municipio, $estado, $latitud, $longitud);
                if (!$stmt_dir->execute()) {
                    $error = true;
                } else {
                    // Obtenemos el ID de la nueva dirección creada
                    $new_direccion_id = $conexion->insert_id;
                    $stmt_dir->close();

                    // Y ahora vinculamos este nuevo ID a la organización
                    $stmt_link = $conexion->prepare("UPDATE organizaciones_perfil SET direccion_id = ? WHERE id = ?");
                    $stmt_link->bind_param("ii", $new_direccion_id, $organizacion_id);
                    if (!$stmt_link->execute()) $error = true;
                    $stmt_link->close();
                }
            }
        }
        
        // --- UPDATE 3: Tabla 'representantes' ---
        if (!$error && $representante_id) {
            $sql3 = "UPDATE representantes SET nombre = ?, apellido_paterno = ?, apellido_materno = ?, email = ?, telefono = ? WHERE id = ?";
            $stmt3 = $conexion->prepare($sql3);
            $stmt3->bind_param("sssssi", $rep_nombre, $rep_apellido_p, $rep_apellido_m, $rep_email, $rep_telefono, $representante_id);
            if (!$stmt3->execute()) $error = true;
            $stmt3->close();
        }

        // ==============================================================
        // === INICIO DE LA NUEVA LÓGICA PARA GUARDAR CATEGORÍAS ===
        // ==============================================================
        if (!$error) {
            // 1. Borramos las categorías existentes para esta organización
            $stmt_delete = $conexion->prepare("DELETE FROM organizaciones_x_categorias WHERE organizacion_id = ?");
            $stmt_delete->bind_param("i", $organizacion_id);
            if (!$stmt_delete->execute()) $error = true;
            $stmt_delete->close();
            
            // 2. Insertamos las nuevas categorías seleccionadas
            if (!$error && !empty($categorias_seleccionadas)) {
                $sql_insert_cat = "INSERT INTO organizaciones_x_categorias (organizacion_id, categoria_id) VALUES (?, ?)";
                $stmt_insert_cat = $conexion->prepare($sql_insert_cat);
                foreach ($categorias_seleccionadas as $categoria_id) {
                    $stmt_insert_cat->bind_param("ii", $organizacion_id, $categoria_id);
                    if (!$stmt_insert_cat->execute()) {
                        $error = true;
                        break; // Salir del bucle si hay un error
                    }
                }
                $stmt_insert_cat->close();
            }
        }
        // ==============================================================
        // === FIN DE LA NUEVA LÓGICA PARA GUARDAR CATEGORÍAS ===
        // ==============================================================


        // --- Finalizamos la transacción ---
        if ($error) {
            $conexion->rollback();
            header('Location: ' . BASE_URL . 'organizacion_perfil.php?status=error');
        } else {
            $conexion->commit();
            header('Location: ' . BASE_URL . 'organizacion_perfil.php?status=success');
        }

    } catch (Exception $e) {
        $conexion->rollback();
        header('Location: ' . BASE_URL . 'organizacion_perfil.php?status=exception');
    }

    $conexion->close();
    exit;

} else {
    header('Location: ' . BASE_URL . 'organizacion_perfil.php');
    exit;
}
?>