<?php
$servidor = "localhost"; // Cambiar si se usa un servidor remoto
$usuario = "root"; // Usuario de la base de datos
$clave = ""; // Contraseña de la base de datos
$base_datos = "dosys"; // Nombre de tu base de datos

$conexion = new mysqli($servidor, $usuario, $clave, $base_datos);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>

