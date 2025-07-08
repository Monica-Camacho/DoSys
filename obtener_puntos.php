<?php
// Encabezado para indicar que la respuesta es JSON
header('Content-Type: application/json; charset=utf-8');

// Incluir el archivo de conexión que estás usando
// Asegúrate que el nombre del archivo es correcto.
include 'conexion_local.php'; 

// Array para almacenar los resultados
$puntos = []; 

// Verificar si la conexión se estableció correctamente
if (!isset($conexion) || $conexion->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al conectar con la base de datos.']);
    exit();
}

// --- LÓGICA DE FILTRADO ---

// Consulta SQL base
$sql = "SELECT id, nombre, estado, municipio, direccion, maps, latitud, longitud, tipo_id FROM puntos_donacion WHERE latitud IS NOT NULL AND longitud IS NOT NULL";

// Verificar si se pasó una categoría en la URL
if (isset($_GET['categoria']) && !empty($_GET['categoria'])) {
    
    $categoria = $_GET['categoria'];
    $tipo_id = 0;

    // Mapeo de categorías a IDs
    if ($categoria == 'sangre') $tipo_id = 1; 
    elseif ($categoria == 'medicamentos') $tipo_id = 2;
    elseif ($categoria == 'dispositivos') $tipo_id = 3;
    
    if ($tipo_id > 0) {
        // Añadir el filtro directamente a la consulta
        $sql .= " AND tipo_id = " . intval($tipo_id); // intval para seguridad
    }
}

$resultado = $conexion->query($sql);

if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $puntos[] = $fila; 
    }
    $resultado->free(); 
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error al ejecutar la consulta: ' . $conexion->error]);
    $conexion->close();
    exit();
}

$conexion->close();

// Devolver el array de puntos codificado como JSON
echo json_encode($puntos);

?>
