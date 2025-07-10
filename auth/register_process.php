<?php
// Incluimos los archivos de configuración y conexión.
// La ruta usa '../' porque estamos subiendo un nivel desde la carpeta 'auth'.
require_once '../config.php';
require_once '../conexion_local.php';

// Iniciamos la sesión para poder crear las variables del usuario.
session_start();

// 1. Verificamos que los datos lleguen por el método POST.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- Recolección de Datos del Formulario ---
    // Credenciales
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    
    // Datos Personales
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $apellidos = trim($apellido_paterno . ' ' . $apellido_materno);
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $sexo = $_POST['sexo'];
    $telefono = $_POST['telefono'];

    // --- Validación de Datos ---

    if ($password !== $password_confirm) {
        die("Error: Las contraseñas no coinciden. Por favor, vuelve atrás e inténtalo de nuevo.");
    }

    $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("Error: El correo electrónico ya está registrado. Por favor, utiliza otro.");
    }
    $stmt->close();

    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // --- Inserción en la Base de Datos (Transacción) ---
    $conexion->begin_transaction();

    try {
        // 2. Insertar en la tabla `usuarios`
        $tipo_usuario_id = 1; // 1 = Persona
        $rol_id = 3; // 3 = Visualizador (rol por defecto para personas)
        $estado = 'Activo';

        $sql_user = "INSERT INTO usuarios (email, password_hash, tipo_usuario_id, rol_id, estado) VALUES (?, ?, ?, ?, ?)";
        $stmt_user = $conexion->prepare($sql_user);
        $stmt_user->bind_param("ssiis", $email, $password_hash, $tipo_usuario_id, $rol_id, $estado);
        $stmt_user->execute();
        
        $usuario_id = $conexion->insert_id;
        $stmt_user->close();

        // 3. Insertar en la tabla `personas_perfil`
        $sql_perfil = "INSERT INTO personas_perfil (usuario_id, nombre, apellidos, fecha_nacimiento, telefono) VALUES (?, ?, ?, ?, ?)";
        $stmt_perfil = $conexion->prepare($sql_perfil);
        $stmt_perfil->bind_param("issss", $usuario_id, $nombre, $apellidos, $fecha_nacimiento, $telefono);
        $stmt_perfil->execute();
        $stmt_perfil->close();

        $conexion->commit();
        
        // 4. Iniciar sesión automáticamente
        $_SESSION['loggedin'] = true;
        $_SESSION['id'] = $usuario_id;
        $_SESSION['email'] = $email;
        $_SESSION['nombre_usuario'] = $nombre;
        $_SESSION['tipo_usuario_id'] = $tipo_usuario_id;
        $_SESSION['rol_id'] = $rol_id;
        
        // =============================================
        //           MODIFICACIÓN IMPORTANTE
        // =============================================
        // 5. Redirigir al perfil del usuario usando solo BASE_URL
        header("Location: " . BASE_URL . "persona_perfil.php");
        exit();

    } catch (mysqli_sql_exception $e) {
        $conexion->rollback();
        die("Error en el registro: No se pudo guardar la información. Por favor, intenta de nuevo más tarde. Error: " . $e->getMessage());
    }

    $conexion->close();
} else {
    // Si no es una solicitud POST, redirigir a la página de selección.
    header("Location: " . BASE_URL . "r_seleccionar_tipo.php");
    exit();
}
?>