<?php
header('Content-Type: application/json');

// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "", "dosys");

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consultar hospitales
$sql = "SELECT nombre, lat, lng FROM lugar_sangre WHERE tipo = 'hospital'";

$resultado = $conexion->query($sql);

$hospitales = [];

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $hospitales[] = $fila;
    }
}

// Devolver datos en formato JSON
echo json_encode($hospitales);

$conexion->close();
?>
