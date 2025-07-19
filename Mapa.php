<?php
// =================================================================
// 1. OBTENCIÓN DE DATOS (NUEVA LÓGICA)
// =================================================================
require_once 'config.php';
require_once 'conexion_local.php';
require_once 'auth/ubicaciones_handler.php'; // Incluimos el cerebro de las ubicaciones
session_start();

// Obtenemos todas las organizaciones con sus categorías usando la nueva función
$organizaciones = obtener_organizaciones_con_categorias($conexion);
// Convertimos los datos a JSON para que JavaScript los pueda leer fácilmente
$organizaciones_json = json_encode($organizaciones);

// Obtenemos la lista de categorías para construir los filtros dinámicamente
$categorias = [];
$sql_categorias = "SELECT id, nombre FROM categorias_donacion ORDER BY id";
$resultado_cat = $conexion->query($sql_categorias);
if ($resultado_cat) {
    while($fila = $resultado_cat->fetch_assoc()){
        $categorias[] = $fila;
    }
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>DoSys - Mapa de Puntos de Donación</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="img/logos/DoSys_chico.png">

    <!-- Google Web Fonts, Iconos y Estilos -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:slnt,wght@-10..0,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Librerías de Estilos -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Estilos de Bootstrap y de la Plantilla -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <style>
        #map {
            height: 80vh; 
            width: 100%;
            border-radius: .5rem;
            box-shadow: 0 0 15px rgba(0,0,0,.1);
        }
    </style>
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

        <!-- Header Start -->
        <div class="container-fluid bg-light py-5">
            <div class="container">
                <div>
                    <h1 class='display-5 mb-0'>Mapa de Puntos de Donación</h1>
                    <p class="fs-5 text-muted mb-0">Encuentra y filtra por bancos de sangre, organizaciones altruistas y bancos de medicamentos.</p>
                </div>
            </div>
        </div>
        <!-- Header End -->

        <div class="container-fluid py-5">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-transparent border-bottom-0 p-4">
                                <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filtrar por:</h5>
                            </div>
                            <div class="card-body p-4">
                                <h6>Tipo de Centro</h6>
                                
                                <?php foreach ($categorias as $cat): ?>
                                <div class="form-check">
                                    <input class="form-check-input filter-checkbox" type="checkbox" value="<?php echo $cat['id']; ?>" id="filter_<?php echo $cat['id']; ?>" onchange="aplicarFiltros()">
                                    <label class="form-check-label" for="filter_<?php echo $cat['id']; ?>">
                                        <?php echo htmlspecialchars($cat['nombre']); ?>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                                <hr>
                                <button class="btn btn-outline-secondary btn-sm w-100" onclick="limpiarTodosLosFiltros()">Limpiar Filtros</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer Start -->
        <?php require_once 'templates/footer.php'; ?>
        <!-- Footer End -->
         
        <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
        
        <?php require_once 'templates/scripts.php'; ?>

        <script>
            let map;
            let todosLosPuntos = []; // Tu variable global para guardar todos los datos
            let marcadoresActuales = []; 
            let userLocationMarker;
            let infoWindow; 

            function initMap() {
                const initialPos = { lat: 17.9869, lng: -92.9303 }; // Villahermosa
                
                map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 12, center: initialPos, mapTypeControl: false, streetViewControl: false
                });
                
                infoWindow = new google.maps.InfoWindow();

                // Leemos los datos desde PHP y los guardamos en tu variable global
                todosLosPuntos = <?php echo $organizaciones_json; ?>;
                
                // Mostramos todos los puntos al cargar el mapa por primera vez
                mostrarPuntos(todosLosPuntos);
                
                geolocalizarUsuario();
            }
            
            // Tu función para geolocalizar (se mantiene intacta)
            function geolocalizarUsuario() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const userLocation = { lat: position.coords.latitude, lng: position.coords.longitude };
                            map.setCenter(userLocation);
                            map.setZoom(14); 
                            if (userLocationMarker) userLocationMarker.setMap(null);
                            const svgIcon = `<svg width="48" height="48" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg"><g><circle cx="24" cy="24" r="22" fill="#06A3DA" stroke="#FFFFFF" stroke-width="2"/><g fill="#FFFFFF"><circle cx="24" cy="18" r="7"/><path d="M14 38 C14 30, 34 30, 34 38 Z"/></g></g></svg>`;
                            userLocationMarker = new google.maps.Marker({
                                position: userLocation, map: map, title: "¡Estás aquí!",
                                icon: { url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svgIcon), scaledSize: new google.maps.Size(48, 48), anchor: new google.maps.Point(24, 24) },
                            });
                        }
                    );
                }
            }
            
            // Tu función para agregar marcadores (modificada para usar los nuevos nombres de campos)
            function agregarMarcador(punto) {
                const contenidoInfoWindow = `
                    <div style="font-family: 'DM Sans', sans-serif; max-width: 250px;">
                        <h6 style="margin: 0 0 5px; font-weight: bold;">${punto.nombre_organizacion}</h6>
                        <p style="margin: 0; font-size: 14px;">${punto.calle || ''} ${punto.numero_exterior || ''}, ${punto.colonia || ''}</p>
                    </div>`;
                
                const marcador = new google.maps.Marker({
                    position: { lat: parseFloat(punto.latitud), lng: parseFloat(punto.longitud) },
                    map: map,
                    title: punto.nombre_organizacion,
                });

                marcador.addListener("click", () => {
                    infoWindow.close(); 
                    infoWindow.setContent(contenidoInfoWindow);
                    infoWindow.open(map, marcador);
                });
                marcadoresActuales.push(marcador);
            }

            // =========================================================
            // FUNCIÓN DE FILTRADO (CORREGIDA A TU LÓGICA ORIGINAL)
            // =========================================================
            function aplicarFiltros() {
                const filtrosActivos = [];
                document.querySelectorAll('.filter-checkbox:checked').forEach(checkbox => {
                    filtrosActivos.push(checkbox.value);
                });

                // 1. Filtramos el array de datos
                const puntosFiltrados = (filtrosActivos.length === 0)
                    ? todosLosPuntos // Si no hay filtros, usamos todos los puntos
                    : todosLosPuntos.filter(punto => {
                        const categoriasDelPunto = punto.categorias_ids ? punto.categorias_ids.split(',') : [];
                        // La función .some() devuelve true si al menos una categoría coincide
                        return categoriasDelPunto.some(cat => filtrosActivos.includes(cat));
                    });
                
                // 2. Mostramos solo los puntos que pasaron el filtro
                mostrarPuntos(puntosFiltrados);
            }

            // Tu función para mostrar los puntos (sin cambios)
            function mostrarPuntos(puntos) {
                limpiarMarcadores();
                puntos.forEach(punto => agregarMarcador(punto));
            }

            // Tu función para limpiar marcadores (sin cambios)
            function limpiarMarcadores() {
                marcadoresActuales.forEach(marcador => marcador.setMap(null));
                marcadoresActuales = [];
            }

            // Tu función para limpiar filtros (sin cambios)
            function limpiarTodosLosFiltros() {
                document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
                    checkbox.checked = false;
                });
                aplicarFiltros();
            }
        </script>

</body>
</html>
