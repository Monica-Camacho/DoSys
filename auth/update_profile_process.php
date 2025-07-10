<?php
// 1. Incluimos archivos de configuración e iniciamos sesión
require_once '../config.php';
require_once '../conexion_local.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
session_start();

// 2. Verificamos que el usuario esté logueado y que los datos lleguen por POST
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['id']) || $_SERVER["REQUEST_METHOD"] != "POST") {
    header('Location: ' . BASE_URL . 'login.php');
    exit;
}

$usuario_id = $_SESSION['id'];

// 3. Recolectamos todos los datos del formulario
// Datos Personales
$nombre = $_POST['nombre'] ?? null;
$apellido_paterno = $_POST['apellido_paterno'] ?? null;
$apellido_materno = $_POST['apellido_materno'] ?? null;
$fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
$sexo = $_POST['sexo'] ?? null;
$telefono = $_POST['telefono'] ?? null;

// Datos Médicos
$tipo_sangre_id = $_POST['tipo_sangre_id'] ?? null;
// Nota: La edición de alergias y enfermedades requiere una lógica más compleja (no incluida aquí).

// Datos de Dirección
$calle = $_POST['dir_calle'] ?? null;
$numero_exterior = $_POST['dir_num_ext'] ?? null;
$numero_interior = $_POST['dir_num_int'] ?? null;
$colonia = $_POST['dir_colonia'] ?? null;
$codigo_postal = $_POST['dir_cp'] ?? null;
$municipio = $_POST['dir_municipio'] ?? null;
$estado = $_POST['dir_estado'] ?? null;
$latitud = $_POST['latitud'] ?? null;
$longitud = $_POST['longitud'] ?? null;

// Datos Médicos (ahora se reciben como un string JSON de Tagify)
$tipo_sangre_id = $_POST['tipo_sangre_id'] ?? null;
$enfermedades_json = $_POST['enfermedades'] ?? '[]';
$alergias_json = $_POST['alergias'] ?? '[]';

// Datos de Seguridad
$current_password = $_POST['current_password'] ?? null;
$new_password = $_POST['new_password'] ?? null;

// 4. Iniciamos una transacción para asegurar la integridad de los datos
$conexion->begin_transaction();

try {
    // --- ACTUALIZAR TABLA `personas_perfil` ---
    $stmt_perfil = $conexion->prepare(
        "UPDATE personas_perfil SET 
            nombre = ?, apellido_paterno = ?, apellido_materno = ?, 
            fecha_nacimiento = ?, sexo = ?, telefono = ?, tipo_sangre_id = ?
        WHERE usuario_id = ?"
    );
    // Asignamos NULL si el valor está vacío, para los campos opcionales
    $fn_null = empty($fecha_nacimiento) ? null : $fecha_nacimiento;
    $sexo_null = empty($sexo) ? null : $sexo;
    $tel_null = empty($telefono) ? null : $telefono;
    $sangre_null = empty($tipo_sangre_id) ? null : $tipo_sangre_id;
    $stmt_perfil->bind_param("ssssssii", $nombre, $apellido_paterno, $apellido_materno, $fn_null, $sexo_null, $tel_null, $sangre_null, $usuario_id);
    $stmt_perfil->execute();
    $stmt_perfil->close();

    // --- ACTUALIZAR O INSERTAR DIRECCIÓN ---
    // Primero, verificamos si el usuario ya tiene una dirección asignada
    $stmt_check_dir = $conexion->prepare("SELECT direccion_id FROM personas_perfil WHERE usuario_id = ?");
    $stmt_check_dir->bind_param("i", $usuario_id);
    $stmt_check_dir->execute();
    $resultado_dir = $stmt_check_dir->get_result();
    $direccion_id = $resultado_dir->fetch_assoc()['direccion_id'];
    $stmt_check_dir->close();

    if ($direccion_id) {
        // Si ya tiene una dirección, la ACTUALIZAMOS
        $stmt_update_dir = $conexion->prepare(
            "UPDATE direcciones SET 
                calle = ?, numero_exterior = ?, numero_interior = ?, colonia = ?, 
                codigo_postal = ?, municipio = ?, estado = ?, latitud = ?, longitud = ?
            WHERE id = ?"
        );
        $stmt_update_dir->bind_param("sssssssssi", $calle, $numero_exterior, $numero_interior, $colonia, $codigo_postal, $municipio, $estado, $latitud, $longitud, $direccion_id);
        $stmt_update_dir->execute();
        $stmt_update_dir->close();
    } else {
        // Si no tiene una dirección, INSERTAMOS una nueva y actualizamos el perfil
        $stmt_insert_dir = $conexion->prepare(
            "INSERT INTO direcciones (calle, numero_exterior, numero_interior, colonia, codigo_postal, municipio, estado, latitud, longitud) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt_insert_dir->bind_param("sssssssss", $calle, $numero_exterior, $numero_interior, $colonia, $codigo_postal, $municipio, $estado, $latitud, $longitud);
        $stmt_insert_dir->execute();
        $new_direccion_id = $conexion->insert_id;
        $stmt_insert_dir->close();

        // Actualizamos personas_perfil con el ID de la nueva dirección
        $stmt_link_dir = $conexion->prepare("UPDATE personas_perfil SET direccion_id = ? WHERE usuario_id = ?");
        $stmt_link_dir->bind_param("ii", $new_direccion_id, $usuario_id);
        $stmt_link_dir->execute();
        $stmt_link_dir->close();
    }

        // --- FUNCIÓN REUTILIZABLE PARA MANEJAR TAGS (ALERGIAS/ENFERMEDADES) ---
    function manejar_tags($conexion, $usuario_id, $json_tags, $tabla_principal, $tabla_union, $columna_union) {
        // Decodificar el JSON que envía Tagify
        $tags = json_decode($json_tags, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Si no es un JSON válido, asumimos que es una lista separada por comas
            $tags = array_map(function($item) {
                return ['value' => trim($item)];
            }, explode(',', $json_tags));
        }

        // 1. Borrar todas las asociaciones existentes para este usuario
        $stmt_delete = $conexion->prepare("DELETE FROM $tabla_union WHERE usuario_id = ?");
        $stmt_delete->bind_param("i", $usuario_id);
        $stmt_delete->execute();
        $stmt_delete->close();

        // 2. Procesar cada nueva etiqueta
        foreach ($tags as $tag) {
            $nombre_tag = trim($tag['value']);
            if (empty($nombre_tag)) continue;

            // 2a. Verificar si la enfermedad/alergia ya existe en el catálogo
            $stmt_find = $conexion->prepare("SELECT id FROM $tabla_principal WHERE nombre = ?");
            $stmt_find->bind_param("s", $nombre_tag);
            $stmt_find->execute();
            $resultado = $stmt_find->get_result();
            $tag_id = null;

            if ($resultado->num_rows > 0) {
                $tag_id = $resultado->fetch_assoc()['id'];
            } else {
                // 2b. Si no existe, la insertamos en el catálogo
                $stmt_insert_tag = $conexion->prepare("INSERT INTO $tabla_principal (nombre) VALUES (?)");
                $stmt_insert_tag->bind_param("s", $nombre_tag);
                $stmt_insert_tag->execute();
                $tag_id = $conexion->insert_id;
                $stmt_insert_tag->close();
            }
            $stmt_find->close();

            // 2c. Crear la nueva asociación en la tabla de unión
            if ($tag_id) {
                $stmt_link = $conexion->prepare("INSERT INTO $tabla_union (usuario_id, $columna_union) VALUES (?, ?)");
                $stmt_link->bind_param("ii", $usuario_id, $tag_id);
                $stmt_link->execute();
                $stmt_link->close();
            }
        }
    }

    // Llamamos a la función para las enfermedades
    manejar_tags($conexion, $usuario_id, $enfermedades_json, 'enfermedades', 'personas_x_enfermedades', 'enfermedad_id');
    
    // Llamamos a la función para las alergias
    manejar_tags($conexion, $usuario_id, $alergias_json, 'alergias', 'personas_x_alergias', 'alergia_id');

    // --- ACTUALIZAR CONTRASEÑA (si se proporcionó una nueva) ---
    if (!empty($new_password) && !empty($current_password)) {
        // (Aquí iría la lógica para verificar la contraseña actual antes de cambiarla)
        // Por ahora, solo actualizamos si se llenó el campo.
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt_pass = $conexion->prepare("UPDATE usuarios SET password_hash = ? WHERE id = ?");
        $stmt_pass->bind_param("si", $new_password_hash, $usuario_id);
        $stmt_pass->execute();
        $stmt_pass->close();
    }

    // Si todo fue bien, confirmamos los cambios
    $conexion->commit();

    // Redirigimos de vuelta al perfil con un mensaje de éxito
    header("Location: " . BASE_URL . "persona_perfil.php?update=success");
    exit();

} catch (Exception $e) {
    // Si algo falla, revertimos todos los cambios
    $conexion->rollback();
    die("Error al actualizar el perfil: " . $e->getMessage());
}
?>
