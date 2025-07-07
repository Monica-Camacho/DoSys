<?php
// No mostraremos errores de PHP directamente para no corromper el JSON.
ini_set('display_errors', 0);
error_reporting(0);

// Establecer la cabecera para devolver contenido en formato JSON.
header('Content-Type: application/json');

// Incluir el archivo de conexión
include 'conexion_host.php';

// --- VERIFICACIÓN DE CONEXIÓN ---
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión a la base de datos.']);
    exit();
}

// --- Lógica para filtrar los puntos de donación ---

// Consulta SQL base.
$sql = "SELECT nombre, latitud, longitud, direccion, maps AS telefono FROM puntos_donacion"; // Se asume que 'maps' contiene el teléfono o dato de contacto

$params = [];
$types = '';

// Verifica si se ha proporcionado una categoría y la mapea a un ID.
if (isset($_GET['categoria']) && !empty($_GET['categoria'])) {
    
    $categoria = $_GET['categoria'];
    $tipo_id = 0;

    // Mapeo de categorías a IDs (ajusta estos IDs según tu tabla `tipos`)
    if ($categoria == 'sangre') {
        $tipo_id = 1; 
    } elseif ($categoria == 'medicamentos') {
        $tipo_id = 2;
    } elseif ($categoria == 'dispositivos') {
        $tipo_id = 3;
    }
    
    // Si se encontró un ID válido, se añade el filtro a la consulta.
    if ($tipo_id > 0) {
        $sql .= " WHERE tipo_id = ?";
        $types .= 'i'; // 'i' para un parámetro de tipo entero (integer)
        $params[] = $tipo_id;
    }
}

// Preparar y ejecutar la consulta de forma segura
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al preparar la consulta SQL.']);
    $conn->close();
    exit();
}

// Si hay parámetros, los vinculamos a la consulta
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$puntos = array();

if ($result) {
    while($row = $result->fetch_assoc()) {
        $puntos[] = $row;
    }
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error al ejecutar la consulta.']);
    $stmt->close();
    $conn->close();
    exit();
}

$stmt->close();
$conn->close();

echo json_encode($puntos);

?>
