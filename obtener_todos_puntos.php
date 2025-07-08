<?php
// Incluir la conexión a la base de datos
include('conexion_host.php'); // Asegúrate de que la ruta sea correcta

// Consulta SQL para obtener TODOS los puntos de donación con sus coordenadas
$sql = "SELECT pd.nombre, pd.estado, pd.municipio, pd.latitud, pd.longitud
        FROM puntos_donacion pd"; // Eliminamos el WHERE que filtraba por tipo_id

// Ejecutar la consulta
$resultado = $conexion->query($sql);

// Comprobar si hay resultados
if ($resultado->num_rows > 0) {
    // Crear un array para almacenar los puntos de donación
    $puntos = [];

    // Recorrer los resultados y agregarlos al array
    while ($fila = $resultado->fetch_assoc()) {
        $puntos[] = $fila;
    }

    // Devolver los datos en formato JSON
    header('Content-Type: application/json');
    echo json_encode($puntos);
} else {
    // Si no hay resultados, devolver un array vacío
    header('Content-Type: application/json');
    echo json_encode([]);
}

// Cerrar la conexión
$conexion->close();
?>