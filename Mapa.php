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
            height: 80vh; /* Aumentamos la altura para que ocupe más espacio vertical */
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
    <div class="container-fluid topbar px-0 px-lg-4 bg-light py-2 d-none d-lg-block">
        <div class="container">
            <div class="row gx-0 align-items-center">
                <div class="col-lg-8 text-center text-lg-start mb-lg-0">
                    <div class="d-flex flex-wrap">
                        <div class="border-end border-primary pe-3">
                            <a href="mapa.php" class="text-muted small"><i class="fas fa-map-marker-alt text-primary me-2"></i>Lugares de donación</a>
                        </div>
                        <div class="ps-3">
                            <a href="mailto:contacto@dosys.mx" class="text-muted small"><i class="fas fa-envelope text-primary me-2"></i>contacto@dosys.mx</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-center text-lg-end">
                    <div class="d-flex justify-content-end">
                        <div class="d-flex border-end border-primary pe-3">
                            <a class="btn p-0 text-primary me-3" href="https://www.instagram.com/dosys_official/" target="_blank"><i class="fab fa-instagram"></i></a>
                            <a class="btn p-0 text-primary me-0" href="https://www.tiktok.com/@dosys.official" target="_blank"><i class="fab fa-tiktok"></i></a>
                        </div>
                        <div class="dropdown ms-3">
                            <a href="#" class="dropdown-toggle text-dark" data-bs-toggle="dropdown"><small><i class="fas fa-globe-europe text-primary me-2"></i> Idioma</small></a>
                            <div class="dropdown-menu rounded">
                                <a href="#" class="dropdown-item">Español</a>
                                <a href="mantenimiento.html" class="dropdown-item">Inglés</a>
                                <a href="mantenimiento.html" class="dropdown-item">Nahuatl</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->    

    <!-- Navbar Start -->
    <div class="container-fluid nav-bar px-0 px-lg-4 py-lg-0">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light"> 
                <a href="index.html" class="navbar-brand p-0">
                    <img src="img/logos/DoSys_largo_fondoTransparente.png" alt="DoSys_Logo" class="img-fluid">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav mx-auto">
                        <a href="index.html" class="nav-item nav-link">Inicio</a>
                        <a href="avisos.html" class="nav-item nav-link">Avisos de Donación</a>
                        <a href="mapa.php" class="nav-item nav-link active">Mapa</a>
                        <a href="estadisticas.html" class="nav-item nav-link">Estadísticas</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link" data-bs-toggle="dropdown">
                                <span class="dropdown-toggle">Conócenos</span>
                            </a>
                            <div class="dropdown-menu">
                                <a href="C-Sobre_Nosotros.html" class="dropdown-item">Sobre Nosotros</a>
                                <a href="C-Nuestro_Equipo.html" class="dropdown-item">Nuestro Equipo</a>
                                <a href="C-Logros.html" class="dropdown-item">Logros</a>
                                <a href="R-Empresas_Aliadas.php" class="dropdown-item">Empresas Aliadas</a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center ms-lg-4">
                        <a href="login.php" class="btn btn-primary rounded-pill py-2 px-4 me-2 text-nowrap">Iniciar Sesión</a>
                        <a href="r_seleccionar_tipo.html" class="btn btn-outline-primary rounded-pill py-2 px-4">Regístrate</a>
                   </div>
                </div>
            </nav>
        </div>
    </div>
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
                                <label class="form-check-label" for="filterSangre">
                                    Bancos de Sangre
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="2" id="filterMedicamentos" onchange="aplicarFiltros()">
                                <label class="form-check-label" for="filterMedicamentos">
                                    Medicamentos
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="3" id="filterDispositivos" onchange="aplicarFiltros()">
                                <label class="form-check-label" for="filterDispositivos">
                                    Dispositivos
                                </label>
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
    <div class="container-fluid bg-dark text-light footer pt-5 wow fadeIn" data-wow-delay="0.2s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="footer-item">
                        <h4 class="text-white mb-4">Información Legal</h4>
                        <a class="btn-link" href="#"><i class="fas fa-angle-right me-2"></i> Términos y Condiciones</a>
                        <a class="btn-link" href="#"><i class="fas fa-angle-right me-2"></i> Política de Privacidad</a>
                        <a class="btn-link" href="#"><i class="fas fa-angle-right me-2"></i> Aviso Legal</a>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="footer-item">
                        <h4 class="text-white mb-4">Enlaces Rápidos</h4>
                        <a class="btn-link" href="index.html"><i class="fas fa-angle-right me-2"></i> Inicio</a>
                        <a class="btn-link" href="avisos.html"><i class="fas fa-angle-right me-2"></i> Avisos de Donación</a>
                        <a class="btn-link" href="mapa.php"><i class="fas fa-angle-right me-2"></i> Mapa</a>
                        <a class="btn-link" href="C-Sobre_Nosotros.html"><i class="fas fa-angle-right me-2"></i> Sobre Nosotros</a>
                        <a class="btn-link" href="C-Nuestro_Equipo.html"><i class="fas fa-angle-right me-2"></i> Nuestro Equipo</a>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="footer-item">
                        <h4 class="text-white mb-4">Contáctanos</h4>
                        <p class="mb-3">¿Tienes alguna duda o necesitas ayuda? No dudes en contactarnos a través de los siguientes medios:</p>
                        <p class="mb-2"><i class="fas fa-envelope me-2 text-white"></i> <a href="mailto:contacto@dosys.mx" class="text-white">contacto@dosys.mx</a></p>
                        <p class="mb-2"><i class="fab fa-whatsapp me-2 text-white"></i> Asesor: 99-33-59-09-31</p>
                        <p class="mb-2"><i class="fab fa-whatsapp me-2 text-white"></i> Líder de Equipo: 99-31-54-67-94</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>   

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>

    <!-- Script del Mapa -->
    <script>
        let map;
        let todosLosPuntos = []; 
        let marcadoresActuales = []; 

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 5,
                center: { lat: 23.6345, lng: -102.5528 },
            });

            fetch('obtener_puntos.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error del servidor: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    // --- INICIO DE LA CORRECCIÓN ---
                    // 1. Verificar si la respuesta contiene un error del backend
                    if (data.error) {
                        throw new Error('Error en los datos del backend: ' + data.error);
                    }
                    // 2. Verificar si la respuesta es un array antes de usar forEach
                    if (!Array.isArray(data)) {
                        console.error("La respuesta del backend no es una lista (array):", data);
                        throw new Error("Formato de datos inesperado del servidor.");
                    }
                    // --- FIN DE LA CORRECCIÓN ---

                    todosLosPuntos = data;
                    mostrarPuntos(todosLosPuntos); 
                })
                .catch(error => {
                    console.error("Error inicial al obtener los puntos:", error);
                    document.getElementById("map").innerHTML = '<div class="alert alert-danger">No se pudieron cargar los puntos.</div>';
                });
        }
        
        function aplicarFiltros() {
            const filtrosActivos = [];
            if (document.getElementById('filterSangre').checked) filtrosActivos.push(1);
            if (document.getElementById('filterMedicamentos').checked) filtrosActivos.push(2);
            if (document.getElementById('filterDispositivos').checked) filtrosActivos.push(3);

            if (filtrosActivos.length === 0) {
                mostrarPuntos(todosLosPuntos); 
            } else {
                const puntosFiltrados = todosLosPuntos.filter(punto => filtrosActivos.includes(parseInt(punto.tipo_id)));
                mostrarPuntos(puntosFiltrados);
            }
        }

        function mostrarPuntos(puntos) {
            limpiarMarcadores();
            // Verificamos de nuevo que 'puntos' sea un array antes de recorrerlo
            if (Array.isArray(puntos)) {
                puntos.forEach(punto => agregarMarcador(punto));
            }
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

        function agregarMarcador(punto) {
            const contenidoInfoWindow = `
            <div style="font-family: Arial, sans-serif; padding: 10px;">
                <h3 style="margin: 0; font-size: 16px;">${punto.nombre}</h3>
                <p style="margin: 5px 0; font-size: 14px;"><strong>Dirección:</strong> ${punto.direccion || 'No disponible'}</p>
                <a href="${punto.maps}" target="_blank">Ver en Google Maps</a>
            </div>
            `;

            const infoWindow = new google.maps.InfoWindow({ content: contenidoInfoWindow });

            const marcador = new google.maps.Marker({
                position: { lat: parseFloat(punto.latitud), lng: parseFloat(punto.longitud) },
                map: map,
                title: punto.nombre,
                icon: {
                    url: "img/icon/heart.png",
                    scaledSize: new google.maps.Size(40, 40),
                }
            });

            marcador.addListener("click", () => {
                infoWindow.open(map, marcador);
            });

            marcadoresActuales.push(marcador);
        }
    </script>

    <!-- Cargar la API de Google Maps -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAWXq_cevVYbU88p2xYuVUMOWpHctcDlE8&callback=initMap"></script>

</body>

</html>
