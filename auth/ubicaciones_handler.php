<?php
/**
 * Obtiene un listado de organizaciones ACTIVAS, sus datos de ubicación y las categorías que manejan.
 *
 * @param mysqli $conexion El objeto de la conexión a la base de datos.
 * @return array Un array con los datos de las organizaciones encontradas.
 */
function obtener_organizaciones_con_categorias($conexion) {
    
    $organizaciones = [];

    $sql = "SELECT 
                op.id, 
                op.nombre_organizacion,
                d.calle, d.numero_exterior, d.colonia, d.municipio,
                d.latitud, d.longitud,
                GROUP_CONCAT(oxc.categoria_id) AS categorias_ids
            FROM 
                organizaciones_perfil op
            /* --- CAMBIO CLAVE: Se usa LEFT JOIN para no descartar organizaciones sin dirección --- */
            LEFT JOIN 
                direcciones d ON op.direccion_id = d.id
            LEFT JOIN 
                organizaciones_x_categorias oxc ON op.id = oxc.organizacion_id
            WHERE 
                op.estado = 'Activa' 
                AND d.latitud IS NOT NULL 
                AND d.longitud IS NOT NULL
            GROUP BY
                op.id, op.nombre_organizacion, d.calle, d.numero_exterior, d.colonia, d.municipio, d.latitud, d.longitud";

    $resultado = $conexion->query($sql);
    
    if ($resultado) {
        while ($fila = $resultado->fetch_assoc()) {
            $organizaciones[] = $fila;
        }
    }
    
    return $organizaciones;
}
?>