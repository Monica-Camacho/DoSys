<?php
require_once '../config.php';
require_once '../conexion_local.php';
session_start();

// 1. VERIFICACIONES DE SEGURIDAD INICIAL
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['id'])) {
    header('Location: ../index.php');
    exit();
}
$admin_id = $_SESSION['id'];

// 2. OBTENER PERMISOS DEL ADMINISTRADOR LOGUEADO
$sql_permisos = "SELECT ue.empresa_id, u.rol_id
                 FROM usuarios_x_empresas ue
                 JOIN usuarios u ON ue.usuario_id = u.id
                 WHERE ue.usuario_id = ?";
$stmt_permisos = $conexion->prepare($sql_permisos);
$stmt_permisos->bind_param("i", $admin_id);
$stmt_permisos->execute();
$resultado_permisos = $stmt_permisos->get_result();

if ($resultado_permisos->num_rows === 0) {
    // Si no está en una empresa, no puede crear usuarios para una.
    $_SESSION['error_message'] = "No tienes permiso para realizar esta acción o no estás asociado a una empresa.";
    header('Location: ../empresa_usuarios.php');
    exit();
}

// Se obtienen los datos una sola vez para evitar errores
$permisos = $resultado_permisos->fetch_assoc();
$empresa_id = $permisos['empresa_id'];
$rol_admin = $permisos['rol_id'];
$stmt_permisos->close();

// Se verifica el rol después de obtener los datos
if ($rol_admin != 1) { // Solo rol 1 (Administrador) puede crear usuarios
    $_SESSION['error_message'] = "No tienes permiso para crear usuarios en la empresa.";
    header('Location: ../empresa_usuarios.php');
    exit();
}

// 3. OBTENER Y VALIDAR DATOS DEL FORMULARIO
$nombre = trim($_POST['nombre'] ?? '');
$apellido_paterno = trim($_POST['apellido_paterno'] ?? '');
$apellido_materno = trim($_POST['apellido_materno'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$rol_id = filter_input(INPUT_POST, 'rol_id', FILTER_VALIDATE_INT);

// Validaciones básicas
if (empty($nombre) || empty($apellido_paterno) || empty($email) || empty($password) || !$rol_id) {
    $_SESSION['error_message'] = "Todos los campos obligatorios deben ser completados.";
    header('Location: ../empresa_usuario_crear.php');
    exit();
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error_message'] = "El formato del correo electrónico no es válido.";
    header('Location: ../empresa_usuario_crear.php');
    exit();
}
if (strlen($password) < 8) {
    $_SESSION['error_message'] = "La contraseña debe tener al menos 8 caracteres.";
    header('Location: ../empresa_usuario_crear.php');
    exit();
}

// Verificación de email existente en cualquier tipo de usuario (personas, empresas, organizaciones)
$stmt_email = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt_email->bind_param("s", $email);
$stmt_email->execute();
if ($stmt_email->get_result()->num_rows > 0) {
    $_SESSION['error_message'] = "El correo electrónico '$email' ya está registrado en el sistema. Por favor, utiliza otro correo.";
    header('Location: ../empresa_usuario_crear.php');
    exit();
}
$stmt_email->close();

// 4. USAR UNA TRANSACCIÓN PARA INSERTAR LOS DATOS DE FORMA SEGURA
$conexion->begin_transaction();

try {
    // Hashear la contraseña
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $tipo_usuario_id = 2; // 2 = Empresa (para usuarios de empresa)
    $estado_inicial = 'Activo'; // O 'Pendiente' si se requiere activación

    // Paso A: Insertar en la tabla 'usuarios'
    $sql_usuario = "INSERT INTO usuarios (email, password_hash, tipo_usuario_id, rol_id, estado) VALUES (?, ?, ?, ?, ?)";
    $stmt_usuario = $conexion->prepare($sql_usuario);
    $stmt_usuario->bind_param("ssiis", $email, $password_hash, $tipo_usuario_id, $rol_id, $estado_inicial);
    $stmt_usuario->execute();
    
    // Obtener el ID del nuevo usuario
    $nuevo_usuario_id = $conexion->insert_id;
    
    // Paso B: Insertar en la tabla 'personas_perfil' (asumiendo que los usuarios de empresa también tienen un perfil de persona)
    $sql_perfil = "INSERT INTO personas_perfil (usuario_id, nombre, apellido_paterno, apellido_materno) VALUES (?, ?, ?, ?)";
    $stmt_perfil = $conexion->prepare($sql_perfil);
    $stmt_perfil->bind_param("isss", $nuevo_usuario_id, $nombre, $apellido_paterno, $apellido_materno);
    $stmt_perfil->execute();
    
    // Paso C: Insertar en la tabla 'usuarios_x_empresas' para vincularlo a la empresa
    $sql_uxe = "INSERT INTO usuarios_x_empresas (usuario_id, empresa_id) VALUES (?, ?)";
    $stmt_uxe = $conexion->prepare($sql_uxe);
    $stmt_uxe->bind_param("ii", $nuevo_usuario_id, $empresa_id);
    $stmt_uxe->execute();

    // Si todo fue bien, confirmar los cambios
    $conexion->commit();
    $_SESSION['success_message'] = "Usuario de empresa creado y añadido al equipo con éxito.";
    header('Location: ../empresa_usuarios.php');

} catch (mysqli_sql_exception $exception) {
    // Si algo falla, revertir todos los cambios
    $conexion->rollback();
    // Para depuración: error_log($exception->getMessage());
    $_SESSION['error_message'] = "Ocurrió un error al crear el usuario de empresa. Por favor, inténtelo de nuevo.";
    header('Location: ../empresa_usuario_crear.php');
} finally {
    if (isset($stmt_usuario)) $stmt_usuario->close();
    if (isset($stmt_perfil)) $stmt_perfil->close();
    if (isset($stmt_uxe)) $stmt_uxe->close();
    $conexion->close();
    exit();
}
?>
