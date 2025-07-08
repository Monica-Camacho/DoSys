<?php
// Encabezado para indicar que la respuesta es JSON
header('Content-Type: application/json; charset=utf-8');

// Incluir el archivo de conexión
// Asegúrate que la ruta sea correcta desde obtener_puntos.php
include 'conexion_dosys.php';

$puntos = []; // Array para almacenar los resultados

// Verificar si la conexión se estableció correctamente
if (!isset($conexion) || $conexion->connect_error) {
    error_log("Error de conexión a BD en obtener_puntos.php: " . ($conexion->connect_error ?? 'No se pudo conectar'));
    // Devolver un JSON que indique el error
    echo json_encode(['error' => 'Error al conectar con la base de datos.', 'data' => []]);
    exit();
}

// Consulta SQL para obtener los datos necesarios de la tabla ubicaciones
// Seleccionamos las columnas que usa el JavaScript de Mapa.php
// Filtramos para obtener solo los que tienen coordenadas válidas
$sql = "SELECT
            Nombre AS nombre,      -- Renombramos a minúscula para coincidir con el JS
            Estado AS estado,      -- Renombramos a minúscula
            Municipio AS municipio,  -- Renombramos a minúscula
            Latitud AS latitud,    -- Renombramos a minúscula
            Longitud AS longitud   -- Renombramos a minúscula
        FROM
            ubicaciones           -- Consultando tu tabla 'ubicaciones'
        WHERE
            Latitud IS NOT NULL AND Longitud IS NOT NULL";

$resultado = $conexion->query($sql);

if ($resultado) {
    // Si la consulta fue exitosa, recorremos los resultados
    while ($fila = $resultado->fetch_assoc()) {
        // Asegurarnos que latitud y longitud sean números flotantes
        $fila['latitud'] = (float) $fila['latitud'];
        $fila['longitud'] = (float) $fila['longitud'];
        $puntos[] = $fila; // Agregamos la fila al array de puntos
    }
    $resultado->free(); // Liberar memoria del resultado
} else {
    // Si hubo un error en la consulta SQL
    error_log("Error en la consulta SQL en obtener_puntos.php: " . $conexion->error);
    // Devolvemos un JSON que indique el error
     echo json_encode(['error' => 'Error al consultar las ubicaciones: ' . $conexion->error, 'data' => []]);
     $conexion->close();
     exit();
}

$conexion->close(); // Cerrar la conexión a la base de datos

// Devolver el array de puntos codificado como JSON
// Usamos JSON_NUMERIC_CHECK para intentar convertir números en strings a números reales en JSON
echo json_encode($puntos, JSON_NUMERIC_CHECK);

?>