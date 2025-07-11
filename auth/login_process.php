<?php
// ==================================================================
// PARA DEPURACIÓN: Muestra errores detallados.
// ¡Recuerda quitar estas 3 líneas antes de pasar a producción!
// ==================================================================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicia la sesión para poder crear las variables de sesión.
session_start();

// Incluir el archivo de conexión a la base de datos.
// Asegúrate de que la ruta sea correcta desde la carpeta 'auth'.
require_once '../conexion_local.php';

// Verificar si se recibieron datos por el método POST.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Preparar la consulta para evitar inyecciones SQL.
    $sql = "SELECT id, email, password_hash, tipo_usuario_id, rol_id FROM usuarios WHERE email = ?";
    
    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        // Verificar si se encontró un usuario con ese email.
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $email_db, $hashed_password, $tipo_usuario_id, $rol_id);
            
            if ($stmt->fetch()) {
                // Verificar que la contraseña coincida con el hash.
                if (password_verify($password, $hashed_password)) {
                    
                    // --- SECCIÓN CLAVE PARA GUARDAR DATOS EN LA SESIÓN ---
                    $_SESSION['loggedin'] = true;
                    $_SESSION['id'] = $id;
                    $_SESSION['email'] = $email_db;
                    $_SESSION['tipo_usuario_id'] = $tipo_usuario_id;
                    $_SESSION['rol_id'] = $rol_id;
                    
                    // ===========================================================
                    // NUEVO: OBTENER Y GUARDAR EL NOMBRE DEL USUARIO EN LA SESIÓN
                    // ===========================================================
                    $nombre_usuario = 'Usuario'; // Valor por defecto
                    $sql_nombre = "SELECT nombre, apellido_paterno FROM personas_perfil WHERE usuario_id = ?";

                    if ($stmt_nombre = $conexion->prepare($sql_nombre)) {
                        $stmt_nombre->bind_param("i", $id);
                        $stmt_nombre->execute();
                        $stmt_nombre->bind_result($nombre, $apellido_paterno);

                        if ($stmt_nombre->fetch()) {
                            // Unimos el nombre y el apellido para guardarlo en la sesión
                            $nombre_usuario = $nombre . " " . $apellido_paterno;
                        } else {
                            // Si no se encuentra un perfil de persona (podría ser un SuperAdmin)
                            // O si la empresa/org no tiene un perfil de persona asociado directamente
                            // se usará un nombre genérico.
                            if ($tipo_usuario_id == 4) { // SuperAdmin
                                $nombre_usuario = 'Super Administrador';
                            } else {
                                // Para depuración: si no encuentra perfil, lo puedes ver en los logs
                                error_log("Alerta: No se encontró un perfil de persona para el usuario_id: " . $id);
                            }
                        }
                        $stmt_nombre->close();
                    }
                    
                    // Guardamos el nombre encontrado en la sesión.
                    $_SESSION['nombre_usuario'] = $nombre_usuario;
                    
                    // Redirigir según el tipo de usuario.
                    switch ($tipo_usuario_id) {
                        case 1: header("location: ../persona_dashboard.php"); break;
                        case 2: header("location: ../empresa_dashboard.php"); break;
                        case 3: header("location: ../organizacion_dashboard.php"); break;
                        default: header("location: ../index.php"); break;
                    }
                    exit; // Importante para detener la ejecución.

                } else {
                    // Contraseña incorrecta.
                    header("location: ../login.php?error=1");
                }
            }
        } else {
            // Usuario (email) no encontrado.
            header("location: ../login.php?error=1");
        }
        $stmt->close();
    }
    $conexion->close();
} else {
    // Si alguien intenta acceder al archivo directamente, lo redirigimos.
    header("location: ../login.php");
}
?>