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
        $tipo_usuario_id_sesion = 0;

        // --- LÓGICA PARA REGISTRO DE PERSONA (AHORA COMPLETA) ---
        if ($user_type === 'persona') {
            
            $nombre = $_POST['nombre'];
            $apellido_paterno = $_POST['apellido_paterno'];
            $apellido_materno = $_POST['apellido_materno'];
            $nombre_para_sesion = $nombre;
            $tipo_usuario_id_sesion = 1;

            // 1. Insertar en `usuarios`
            $stmt_user = $conexion->prepare("INSERT INTO usuarios (email, password_hash, tipo_usuario_id, rol_id) VALUES (?, ?, 1, 3)");
            $stmt_user->bind_param("ss", $email, $password_hash);
            $stmt_user->execute();
            $usuario_id = $conexion->insert_id;
            $stmt_user->close();

            // 2. Insertar en `personas_perfil`
            $stmt_perfil = $conexion->prepare("INSERT INTO personas_perfil (usuario_id, nombre, apellido_paterno, apellido_materno) VALUES (?, ?, ?, ?)");
            $stmt_perfil->bind_param("isss", $usuario_id, $nombre, $apellido_paterno, $apellido_materno);
            $stmt_perfil->execute();
            $stmt_perfil->close();
            
            $redirect_url = BASE_URL . "persona_perfil.php";
        } 
        // --- LÓGICA PARA REGISTRO DE EMPRESA (CORREGIDA) ---
        elseif ($user_type === 'empresa') {
            
            $operador_nombre = $_POST['operador_nombre'];
            $operador_ap_paterno = $_POST['operador_apellido_paterno'];
            $operador_ap_materno = $_POST['operador_apellido_materno'];
            $empresa_nombre = $_POST['empresa_nombre_comercial'];
            $nombre_para_sesion = $empresa_nombre;
            $tipo_usuario_id_sesion = 2;

            // 1. Insertar en `usuarios`
            $stmt_user = $conexion->prepare("INSERT INTO usuarios (email, password_hash, tipo_usuario_id, rol_id) VALUES (?, ?, 2, 2)");
            $stmt_user->bind_param("ss", $email, $password_hash);
            $stmt_user->execute();
            $usuario_id = $conexion->insert_id;
            $stmt_user->close();

            // 2. Insertar en `personas_perfil` (operador)
            $stmt_persona = $conexion->prepare("INSERT INTO personas_perfil (usuario_id, nombre, apellido_paterno, apellido_materno) VALUES (?, ?, ?, ?)");
            $stmt_persona->bind_param("isss", $usuario_id, $operador_nombre, $operador_ap_paterno, $operador_ap_materno);
            $stmt_persona->execute();
            $stmt_persona->close();

            // 3. Insertar en `representantes`
            $rep_nombre = $_POST['rep_nombre'];
            $rep_apellido_paterno = $_POST['rep_apellido_paterno'];
            $rep_apellido_materno = $_POST['rep_apellido_materno'];
            $rep_email = $_POST['rep_email'];
            $rep_telefono = $_POST['rep_telefono'];
            $stmt_rep = $conexion->prepare("INSERT INTO representantes (nombre, apellido_paterno, apellido_materno, email, telefono) VALUES (?, ?, ?, ?, ?)");
            $stmt_rep->bind_param("sssss", $rep_nombre, $rep_apellido_paterno, $rep_apellido_materno, $rep_email, $rep_telefono);
            $stmt_rep->execute();
            $representante_id = $conexion->insert_id;
            $stmt_rep->close();

            // 4. Insertar en `empresas_perfil`
            $empresa_razon_social = $_POST['empresa_razon_social'];
            $empresa_rfc = $_POST['empresa_rfc'];
            $stmt_empresa = $conexion->prepare("INSERT INTO empresas_perfil (usuario_id, nombre_comercial, razon_social, rfc, representante_id) VALUES (?, ?, ?, ?, ?)");
            $stmt_empresa->bind_param("isssi", $usuario_id, $empresa_nombre, $empresa_razon_social, $empresa_rfc, $representante_id);
            $stmt_empresa->execute();
            $stmt_empresa->close();

            $redirect_url = BASE_URL . "empresa_dashboard.php";
        }
        
        $conexion->commit();
        
        // Iniciar sesión y redirigir
        $_SESSION['loggedin'] = true;
        $_SESSION['id'] = $usuario_id;
        $_SESSION['email'] = $email;
        $_SESSION['nombre_usuario'] = $nombre_para_sesion;
        $_SESSION['tipo_usuario_id'] = $tipo_usuario_id_sesion;
        
        header("Location: " . $redirect_url);
        exit();

    } catch (mysqli_sql_exception $e) {
        $conexion->rollback();
        // Te recuerdo que la siguiente línea es para depuración. ¡No olvides quitarla!
        die("Error en el registro: No se pudo guardar la información. <br><b>Detalle del error:</b> " . $e->getMessage());
    }
}
?>