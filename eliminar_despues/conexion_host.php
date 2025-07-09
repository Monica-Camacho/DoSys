<?php
$servidor = "191.96.56.1"; // Cambiar si se usa un servidor remoto
$usuario = "u312858745_DoSysTeamcito"; // Usuario de la base de datos
$clave = "Dosys1234"; // Contraseña de la base de datos
$base_datos = "u312858745_dosis"; // Nombre de tu base de datos

$conexion = new mysqli($servidor, $usuario, $clave, $base_datos);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>

