<?php
// Incluimos los archivos de configuración y conexión.
require_once '../config.php';
require_once '../conexion_local.php'; // Usamos la conexión a la base de datos local.

// --- INICIO DE LA LÓGICA DE REGISTRO ---

// 1. Verificamos que los datos lleguen por el método POST.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- Datos Comunes a todos los registros ---
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $user_type_name = $_POST['user_type']; // 'persona', 'empresa', 'organizacion'

    // --- Validación de Datos Comunes ---

    // a) Verificar que las contraseñas coincidan.
    if ($password !== $password_confirm) {
        die("Error: Las contraseñas no coinciden. Por favor, vuelve atrás e inténtalo de nuevo.");
    }

    // b) Verificar que el email no exista ya en la base de datos.
    $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("Error: El correo electrónico ya está registrado. Por favor, utiliza otro.");
    }
    $stmt->close();

    // c) Hashear la contraseña para almacenarla de forma segura.
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // --- Lógica Específica por Tipo de Usuario ---
    
    // Mapeo del nombre del tipo de usuario a su ID en la BD.
    $user_type_map = ['persona' => 1, 'empresa' => 2, 'organizacion' => 3];
    $tipo_usuario_id = $user_type_map[$user_type_name];
    
    // Iniciar una transacción para asegurar que todas las inserciones se completen.
    $conexion->begin_transaction();

    try {
        // --- 2. Insertar en la tabla `usuarios` ---
        $rol_id = 3; // Rol por defecto: Visualizador. Cambiar si es necesario.
        $estado = 'Activo'; // O 'Pendiente' si requiere validación de email.

        $sql_user = "INSERT INTO usuarios (email, password_hash, tipo_usuario_id, rol_id, estado) VALUES (?, ?, ?, ?, ?)";
        $stmt_user = $conexion->prepare($sql_user);
        $stmt_user->bind_param("ssiis", $email, $password_hash, $tipo_usuario_id, $rol_id, $estado);
        $stmt_user->execute();
        
        // Obtenemos el ID del usuario recién creado.
        $usuario_id = $conexion->insert_id;
        $stmt_user->close();

        // --- 3. Insertar en la tabla de perfil correspondiente ---
        
        if ($user_type_name === 'persona') {
            $nombre = $_POST['nombre'];
            $apellidos = $_POST['apellido_paterno'] . ' ' . $_POST['apellido_materno'];
            $fecha_nacimiento = $_POST['fecha_nacimiento'];
            $telefono = $_POST['telefono'];

            $sql_perfil = "INSERT INTO personas_perfil (usuario_id, nombre, apellidos, fecha_nacimiento, telefono) VALUES (?, ?, ?, ?, ?)";
            $stmt_perfil = $conexion->prepare($sql_perfil);
            $stmt_perfil->bind_param("issss", $usuario_id, $nombre, $apellidos, $fecha_nacimiento, $telefono);
            $stmt_perfil->execute();
            $stmt_perfil->close();

        } elseif ($user_type_name === 'empresa') {
            $nombre_comercial = $_POST['company_name'];
            $rfc = $_POST['rfc'];
            $representante_nombre_completo = $_POST['representative_name'];
            // Para la BD normalizada, necesitaríamos separar nombre y apellido del representante.
            // Por ahora, lo guardamos en un solo campo para simplificar.
            
            // Aquí iría la lógica para insertar en `empresas_perfil` y `representantes`.
            // Por ahora, lo dejamos pendiente para no complicar el primer paso.

        } elseif ($user_type_name === 'organizacion') {
            $nombre_organizacion = $_POST['org_name'];
            $cluni = $_POST['cluni'];
            $representante_nombre_completo = $_POST['representative_name'];

            // Aquí iría la lógica para insertar en `organizaciones_perfil` y `representantes`.
            // Y también para manejar la subida del archivo `validation_document`.
        }

        // Si todo fue bien, confirmamos la transacción.
        $conexion->commit();
        
        // Redirigimos al usuario al login con un mensaje de éxito.
        header("Location: " . BASE_URL . "login.php?registro=exitoso");
        exit();

    } catch (Exception $e) {
        // Si algo falla, revertimos la transacción.
        $conexion->rollback();
        die("Error en el registro: " . $e->getMessage());
    }

    $conexion->close();
}
?>