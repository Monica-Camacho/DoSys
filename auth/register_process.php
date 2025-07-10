<?php
// Incluimos archivos y configuramos errores
require_once '../config.php';
require_once '../conexion_local.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_type = $_POST['user_type'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if ($password !== $password_confirm) die("Error: Las contraseñas no coinciden.");

    try {
        // Verificar que el email no exista
        $stmt_check = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $stmt_check->store_result();
        if ($stmt_check->num_rows > 0) die("Error: El correo ya está registrado.");
        $stmt_check->close();

        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $conexion->begin_transaction();
        
        $nombre_para_sesion = '';
        $redirect_url = '';

        // --- LÓGICA PARA REGISTRO DE PERSONA (SIMPLIFICADA) ---
        if ($user_type === 'persona') {
            
            $nombre = $_POST['nombre'];
            $apellido_paterno = $_POST['apellido_paterno'];
            $apellido_materno = $_POST['apellido_materno'];
            $nombre_para_sesion = $nombre;

            // 1. Insertar en `usuarios`
            $stmt_user = $conexion->prepare("INSERT INTO usuarios (email, password_hash, tipo_usuario_id, rol_id) VALUES (?, ?, 1, 3)");
            $stmt_user->bind_param("ss", $email, $password_hash);
            $stmt_user->execute();
            $usuario_id = $conexion->insert_id;
            $stmt_user->close();

            // 2. Insertar en `personas_perfil` solo los campos esenciales
            $stmt_perfil = $conexion->prepare("INSERT INTO personas_perfil (usuario_id, nombre, apellido_paterno, apellido_materno) VALUES (?, ?, ?, ?)");
            $stmt_perfil->bind_param("isss", $usuario_id, $nombre, $apellido_paterno, $apellido_materno);
            $stmt_perfil->execute();
            $stmt_perfil->close();
            
            // Redirigimos al perfil para que lo complete.
            $redirect_url = BASE_URL . "persona_perfil.php";
        } 
        // --- LÓGICA PARA EMPRESA Y ORGANIZACIÓN (A FUTURO) ---
        elseif ($user_type === 'empresa') {
            // Aquí irá la lógica simplificada para empresa
        }
        
        $conexion->commit();
        
        // Iniciar sesión y redirigir
        $_SESSION['loggedin'] = true;
        $_SESSION['id'] = $usuario_id;
        $_SESSION['email'] = $email;
        $_SESSION['nombre_usuario'] = $nombre_para_sesion;
        $_SESSION['tipo_usuario_id'] = 1; // Asumimos persona por ahora
        
        header("Location: " . $redirect_url);
        exit();

    } catch (mysqli_sql_exception $e) {
        $conexion->rollback();
        // Te recuerdo que la siguiente línea es para depuración. ¡No olvides quitarla!
        die("Error en el registro: No se pudo guardar la información. <br><b>Detalle del error:</b> " . $e->getMessage());
    }
}
?>