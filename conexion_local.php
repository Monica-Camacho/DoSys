<?php
$servidor = "localhost"; // Cambiar si se usa un servidor remoto
$usuario = "root"; // Usuario de la base de datos
$clave = ""; // Contraseña de la base de datos
$base_datos = "dosys"; // Nombre de tu base de datos

$conexion = new mysqli($servidor, $usuario, $clave, $base_datos);

if ($conexion->connect_error) {
    die("Error al conectar a la base de datos. Por favor, contacta al administrador.");
}
?>

