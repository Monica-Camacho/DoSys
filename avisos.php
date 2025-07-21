<?php
require_once 'config.php';
// --- INICIO DE LA MODIFICACIÓN: Añadir conexión y consulta ---
require_once 'conexion_local.php';
session_start();

// Array para guardar los avisos que vienen de la BD
$avisos = [];

// Esta es la consulta "inteligente" que une todas las tablas
$sql = "SELECT 
            a.id AS aviso_id,
            a.titulo,
            a.descripcion,
            a.categoria_id,
            
            -- Datos de la ubicación a través de la organización
            d.municipio,
            d.estado,
            
            -- Usamos COALESCE para obtener la cantidad requerida de la tabla correcta
            COALESCE(ss.unidades_requeridas, sm.cantidad_requerida, sd.cantidad_requerida) AS cantidad_requerida
            
        FROM 
            avisos a
        JOIN 
            organizaciones_perfil op ON a.organizacion_id = op.id
        LEFT JOIN 
            direcciones d ON op.direccion_id = d.id
        LEFT JOIN 
            solicitudes_sangre ss ON a.id = ss.aviso_id AND a.categoria_id = 1
        LEFT JOIN 
            solicitudes_medicamentos sm ON a.id = sm.aviso_id AND a.categoria_id = 2
        LEFT JOIN 
            solicitudes_dispositivos sd ON a.id = sd.aviso_id AND a.categoria_id = 3
        WHERE
            a.estatus_id = 2 -- Solo mostramos los avisos 'Activos'
        ORDER BY 
            a.fecha_creacion DESC";

$resultado = $conexion->query($sql);
if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $avisos[] = $fila;
    }
}
$conexion->close();
// --- FIN DE LA MODIFICACIÓN ---
?>

<!DOCTYPE html>
<html lang="es">

    <head>
        <script src="https://cdn.userway.org/widget.js" data-account="C07GrJafQK"></script>
        <meta charset="utf-8">
        <title>DoSys - Avisos de Donación</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="img/logos/DoSys_chico.png">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:slnt,wght@-10..0,100..900&display=swap" rel="stylesheet">

        <!-- Icon Font Stylesheet -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link rel="stylesheet" href="lib/animate/animate.min.css"/>
        <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
        <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">


        <!-- Customized Bootstrap Stylesheet -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="css/style.css" rel="stylesheet">

    </head>

    <body>

        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Cargando...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Topbar Start -->
        <?php require_once 'templates/topbar.php'; ?>
        <!-- Topbar End -->  
         
        <!-- Navbar Start -->
        <?php require_once 'templates/navbar.php'; ?>
        <!-- Navbar End -->

    <!-- Notices Start -->
    <div class="container-fluid py-5 bg-light">
        <div class="container">
            
            <div class="d-lg-flex justify-content-between align-items-center mb-5">
                <div class="text-center text-lg-start">
                    <h1 class="display-5">Avisos de Donación</h1>
                    <p class="fs-5 text-muted mb-0">Explora las solicitudes activas y encuentra una causa a la que puedas apoyar.</p>
                </div>
                <div class="text-center mt-4 mt-lg-0">
                    <a href="segmentos.php" class="btn btn-success rounded-pill py-2 px-4"><i class="fas fa-plus me-2"></i>Solicitar Donación</a>
                </div>
            </div>

            <!-- Advanced Filters -->
            <div class="row mb-5">
                <div class="col-12">
                    <form class="row g-3 align-items-center bg-white p-3 rounded shadow-sm">
                        <div class="col-lg-5 col-md-12">
                            <input type="text" class="form-control" placeholder="Buscar por palabra clave...">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <select class="form-select">
                                <option selected>Tipo de donación...</option>
                                <option value="all">Todos</option>
                                <option value="sangre">Sangre</option>
                                <option value="medicamentos">Medicamentos</option>
                                <option value="dispositivos">Dispositivos</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                             <select class="form-select">
                                <option selected>Ubicación...</option>
                                <option value="centro">Centro</option>
                                <option value="cardenas">Cárdenas</option>
                                <option value="comalcalco">Comalcalco</option>
                                <option value="paraiso">Paraíso</option>
                            </select>
                        </div>
                        <div class="col-lg-1 col-md-12 text-end">
                            <button type="submit" class="btn btn-primary w-100">Buscar</button>
                        </div>
                    </form>
                </div>
            </div>

<div class="row g-4">
    <?php
    // Primero, definimos un mapa de iconos para las categorías
    $iconos_categoria = [
        1 => '<i class="fas fa-tint fa-2x text-danger"></i>',    // Sangre
        2 => '<i class="fas fa-pills fa-2x text-primary"></i>', // Medicamentos
        3 => '<i class="fas fa-wheelchair fa-2x text-warning"></i>' // Dispositivos
    ];

    // Verificamos si hay avisos para mostrar
    if (!empty($avisos)):
        // Iniciamos un bucle que creará una tarjeta por cada aviso
        foreach ($avisos as $aviso):
    ?>

    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body d-flex flex-column p-4">
                
                <div class="position-absolute top-0 end-0 p-3">
                    <?php echo $iconos_categoria[$aviso['categoria_id']] ?? '<i class="fas fa-heart fa-2x text-muted"></i>'; ?>
                </div>
                
                <h5 class="card-title mt-5"><?php echo htmlspecialchars($aviso['titulo']); ?></h5>
                
                <p class="card-text text-muted small mb-3">
                    <i class="fas fa-map-marker-alt me-2"></i><?php echo htmlspecialchars($aviso['municipio'] . ', ' . $aviso['estado']); ?>
                </p>
                
                <p class="card-text"><?php echo htmlspecialchars(substr($aviso['descripcion'], 0, 100)) . '...'; ?></p>
                
                <div class="mt-auto pt-3">
                    <div class="progress mb-2" style="height: 10px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    
                    <p class="text-muted small">0 de <?php echo htmlspecialchars($aviso['cantidad_requerida']); ?> unidades recolectadas</p>
                    
                    <a href="avisos_detalles.php?id=<?php echo $aviso['aviso_id']; ?>" class="btn btn-primary rounded-pill w-100">Ver Detalles</a>
                </div>
            </div>
        </div>
    </div>

    <?php
        endforeach;
    else:
    ?>
    
    <div class="col-12">
        <div class="alert alert-info text-center" role="alert">
            <h4 class="alert-heading">¡Todo al día!</h4>
            <p>Por el momento no hay solicitudes de donación activas. ¡Gracias por tu interés en ayudar!</p>
        </div>
    </div>

    <?php
    endif;
    ?>
</div>





        </div>
    </div>
    <!-- Notices End -->
        
        <!-- Footer Start -->
        <?php require_once 'templates/footer.php'; ?>
        <!-- Footer End -->
         
        <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
        
        <?php require_once 'templates/scripts.php'; ?>
    
</body>

</html>
