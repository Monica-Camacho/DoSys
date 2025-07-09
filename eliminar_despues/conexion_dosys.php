<?php
// Configuración de la conexión a la base de datos
$servidor = "191.96.56.1";        // Servidor de la base de datos (usualmente localhost)
$usuario = "u312858745_root";              // Tu nombre de usuario de MySQL
$clave = "Kakawol1234";                    // Tu contraseña de MySQL
$base_datos = "u312858745_dosys";      // Nombre de la base de datos (¡Actualizado!)

// Crear la conexión usando MySQLi
$conexion = new mysqli($servidor, $usuario, $clave, $base_datos);

// Verificar si la conexión fue exitosa
if ($conexion->connect_error) {
    die("Error al conectar a la base de datos. Por favor, contacta al administrador.");
}

// Opcional: Establecer el juego de caracteres a UTF-8 (recomendado)
if (!$conexion->set_charset("utf8mb4")) {
}

?>