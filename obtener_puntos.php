<?php
// Incluir el archivo de conexión sin modificarlo
include 'conexion_local.php';

// Obtener el parámetro "tipo" de la solicitud GET
$tipo_id = isset($_GET['tipo']) ? intval($_GET['tipo']) : 1; // Por defecto, tipo_id = 1

// Validar que el tipo_id sea válido (1, 2 o 3)
if (!in_array($tipo_id, [1, 2, 3])) {
    header('Content-Type: application/json');
    echo json_encode(array("error" => "Tipo no válido"));
    exit;
}

// Consulta para obtener los puntos de donación según el tipo_id
$query = "SELECT nombre, estado, municipio, latitud, longitud FROM puntos_donacion WHERE tipo_id = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $tipo_id); // "i" indica que el parámetro es un entero
$stmt->execute();
$resultado = $stmt->get_result();

// Verificar si hay resultados
if ($resultado->num_rows > 0) {
    $puntos = array();
    while ($fila = $resultado->fetch_assoc()) {
        $puntos[] = $fila;
    }
    // Devolver los datos en formato JSON
    header('Content-Type: application/json');
    echo json_encode($puntos);
} else {
    // Si no hay datos, devolver un array vacío
    header('Content-Type: application/json');
    echo json_encode(array());
}

// Cerrar la conexión
$stmt->close();
$conexion->close();
?>