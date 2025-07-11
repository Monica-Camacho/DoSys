<?php
require_once 'config.php'; // Incluye la configuración y la URL base.
// Inicia la sesión.
session_start();

// Muestra una alerta si hay un error en el inicio de sesión.
if (isset($_GET['error']) && $_GET['error'] == 1) {
    echo "<script>alert('Correo electrónico o contraseña incorrectos. Por favor, inténtalo de nuevo.');</script>";
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <script src="https://cdn.userway.org/widget.js" data-account="C07GrJafQK"></script>
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
                <p class="fs-5 text-muted mb-0">Encuentra y filtra hospitales, bancos de sangre y centros de acopio.</p>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Map Content Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Filter Sidebar -->
                <div class="col-lg-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent border-bottom-0 p-4">
                            <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filtrar por:</h5>
                        </div>
                        <div class="card-body p-4">
                            <h6>Tipo de Centro</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="filterSangre" onchange="aplicarFiltros()">
                                <label class="form-check-label" for="filterSangre">Bancos de Sangre</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="2" id="filterMedicamentos" onchange="aplicarFiltros()">
                                <label class="form-check-label" for="filterMedicamentos">Medicamentos</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="3" id="filterDispositivos" onchange="aplicarFiltros()">
                                <label class="form-check-label" for="filterDispositivos">Dispositivos</label>
                            </div>
                            <hr>
                            <button class="btn btn-outline-secondary btn-sm w-100" onclick="limpiarTodosLosFiltros()">Limpiar Filtros</button>
                        </div>
                    </div>
                </div>
                <!-- Map Column -->
                <div class="col-lg-9">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Map Content End -->
        
        <!-- Footer Start -->
        <?php require_once 'templates/footer.php'; ?>
        <!-- Footer End -->
         
        <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
        
        <?php require_once 'templates/scripts.php'; ?>
          
        
    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>

    <!-- Script del Mapa (Modificado) -->
    <script>
        let map;
        let todosLosPuntos = []; 
        let marcadoresActuales = []; 
        let userLocationMarker;
        let infoWindow; 

        function initMap() {
            const initialPos = { lat: 19.4326, lng: -99.1332 };
            
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 12,
                center: initialPos,
                mapTypeControl: false,
                streetViewControl: false
            });
            
            infoWindow = new google.maps.InfoWindow();

            map.addListener('click', function() {
                infoWindow.close();
            });

            geolocalizarUsuario();

            fetch('obtener_puntos.php')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        throw new Error('Error en backend: ' + data.error);
                    }
                    todosLosPuntos = data;
                    mostrarPuntos(todosLosPuntos);
                })
                .catch(error => {
                    console.error("Error al obtener los puntos:", error);
                });
        }
        
        // ===== FUNCIÓN MODIFICADA =====
        function geolocalizarUsuario() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const userLocation = { lat: position.coords.latitude, lng: position.coords.longitude };
                        map.setCenter(userLocation);
                        map.setZoom(14); 
                        
                        if (userLocationMarker) userLocationMarker.setMap(null);

                        // Se define el icono SVG de una persona dentro de un círculo
                        const svgIcon = `
                        <svg width="48" height="48" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <g>
                                <circle cx="24" cy="24" r="22" fill="#06A3DA" stroke="#FFFFFF" stroke-width="2"/>
                                <g fill="#FFFFFF">
                                    <circle cx="24" cy="18" r="7"/>
                                    <path d="M14 38 C14 30, 34 30, 34 38 Z"/>
                                </g>
                            </g>
                        </svg>`;

                        userLocationMarker = new google.maps.Marker({
                            position: userLocation, 
                            map: map, 
                            title: "¡Estás aquí!",
                            // Se usa el SVG como un Data URI
                            icon: {
                                url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svgIcon),
                                scaledSize: new google.maps.Size(48, 48),
                                anchor: new google.maps.Point(24, 24) // Centra el icono
                            },
                        });
                    },
                    () => { 
                        console.log("Depuración: El usuario denegó el permiso de geolocalización.");
                    }
                );
            }
        }
        
        function agregarMarcador(punto) {
            const iconMap = {
                '1': 'img/icon/sangre.png',
                '2': 'img/icon/medicamento.png',
                '3': 'img/icon/dispositivo.png'
            };
            
            const iconPath = iconMap[punto.tipo_id] || 'img/icon/heart.png';

            const contenidoInfoWindow = `
                <div style="font-family: 'DM Sans', sans-serif; max-width: 250px;">
                    <h6 style="margin: 0 0 5px; font-weight: bold;">${punto.nombre}</h6>
                    <p style="margin: 0; font-size: 14px;">${punto.direccion || 'Dirección no disponible'}</p>
                </div>
            `;
            
            const marcador = new google.maps.Marker({
                position: { lat: parseFloat(punto.latitud), lng: parseFloat(punto.longitud) },
                map: map,
                title: punto.nombre,
                icon: { 
                    url: iconPath, 
                    scaledSize: new google.maps.Size(40, 40) 
                }
            });

            marcador.addListener("click", () => {
                infoWindow.close(); 
                infoWindow.setContent(contenidoInfoWindow);
                infoWindow.open(map, marcador);
            });
            marcadoresActuales.push(marcador);
        }

        function aplicarFiltros() {
            const filtrosActivos = [];
            if (document.getElementById('filterSangre').checked) filtrosActivos.push(1);
            if (document.getElementById('filterMedicamentos').checked) filtrosActivos.push(2);
            if (document.getElementById('filterDispositivos').checked) filtrosActivos.push(3);

            const puntosFiltrados = (filtrosActivos.length === 0)
                ? todosLosPuntos
                : todosLosPuntos.filter(punto => filtrosActivos.includes(parseInt(punto.tipo_id)));
            
            mostrarPuntos(puntosFiltrados);
        }

        function mostrarPuntos(puntos) {
            limpiarMarcadores();
            puntos.forEach(punto => agregarMarcador(punto));
        }

        function limpiarMarcadores() {
            marcadoresActuales.forEach(marcador => marcador.setMap(null));
            marcadoresActuales = [];
        }

        function limpiarTodosLosFiltros() {
            document.getElementById('filterSangre').checked = false;
            document.getElementById('filterMedicamentos').checked = false;
            document.getElementById('filterDispositivos').checked = false;
            aplicarFiltros();
        }
    </script>

    <!-- Cargar la API de Google Maps -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAWXq_cevVYbU88p2xYuVUMOWpHctcDlE8&callback=initMap"></script>

</body>
</html>
