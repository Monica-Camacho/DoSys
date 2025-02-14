<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>DoSys - Mapa y Formulario</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

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
    <link href="css/M-Dispositivos.css" rel="stylesheet">
</head>
<body>

    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Cargando...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Mapa y Formulario -->
    <div class="map-form-container">
        <div id="map-container">
            <div id="map"></div>
        </div>
        <div class="sidebar">
        <!-- Contenedor centrado con logo y botón -->
        <div class="d-flex flex-wrap flex-md-nowrap justify-content-center align-items-center gap-3 mb-3 text-center">
            <a href="Donaciones.html" class="navbar-brand p-0">
                <img src="img/logos/DoSys_largo_fondoTransparente.png" alt="DoSys_Logo" class="img-fluid" style="max-height: 80px;">
            </a>
            <a href="Donaciones.html" class="btn btn-primary btn-lg py-2 px-4">Regresar</a>
        </div>


            <h2 class="text-center">Solicitudes de Donación</h2>

            <!-- Filtro de búsqueda -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" id="search-articulo" class="form-control" placeholder="Buscar por Artículo Solicitado">
                </div>
                <div class="col-md-4">
                    <input type="text" id="search-estado" class="form-control" placeholder="Buscar por Estado">
                </div>
                <div class="col-md-4">
                    <input type="text" id="search-municipio" class="form-control" placeholder="Buscar por Municipio">
                </div>
            </div>

            <?php
// Incluir la conexión a la base de datos
include('conexion_local.php'); // Asegúrate de que la ruta sea correcta

// Consulta SQL
$sql = "SELECT sd.id, cd.nombre AS catalogo_donacion, sd.cantidad, sd.unidad_medida, pd.nombre AS puntos_donacion
        FROM solicitud_donacion sd
        JOIN catalogo_donacion cd ON sd.catalogo_donacion_id = cd.id
        JOIN puntos_donacion pd ON sd.puntos_donacion_id = pd.id
        WHERE sd.estado = 'activa'";

// Ejecutar la consulta
$resultado = $conexion->query($sql);

?>

<!-- Aquí comienza la parte del HTML -->
<div class="table-container">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Artículo Solicitado</th>
                <th>Cantidad</th>
                <th>Unidad de Medida</th>
                <th>Punto de Donación</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Comprobar si hay resultados
            if ($resultado->num_rows > 0) {
                // Mostrar las filas de la tabla
                while ($donacion = $resultado->fetch_assoc()) {
                    echo "<tr>
                            <td>{$donacion['catalogo_donacion']}</td>
                            <td>{$donacion['cantidad']}</td>
                            <td>{$donacion['unidad_medida']}</td>
                            <td>{$donacion['puntos_donacion']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No hay solicitudes de donación activas.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php
// Cerrar la conexión
$conexion->close();
?>



    </div>
</div>

    <!-- Modal -->
    <div class="modal fade" id="donacionModal" tabindex="-1" aria-labelledby="donacionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="donacionModalLabel">Detalles de la Donación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Artículo Solicitado:</strong> <span id="modal-articulo"></span></p>
                    <p><strong>Cantidad:</strong> <span id="modal-cantidad"></span></p>
                    <p><strong>Unidad de Medida:</strong> <span id="modal-unidad"></span></p>
                    <p><strong>Estado:</strong> <span id="modal-estado"></span></p>
                    <p><strong>Municipio:</strong> <span id="modal-municipio"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

    <!-- Script del Mapa -->
    <script>
        let map; // Variable global para el mapa
        let marcadores = []; // Array para almacenar los marcadores

        function initMap() {
            // Crear el mapa
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: { lat: 19.432608, lng: -99.133209 } // Centro inicial (CDMX)
            });

            // Obtener la ubicación del usuario
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const userLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    map.setCenter(userLocation);

                    // Marcador de la ubicación del usuario
                    new google.maps.Marker({
                        position: userLocation,
                        map: map,
                        title: "Tu ubicación",
                        icon: {
                            url: "https://maps.google.com/mapfiles/kml/shapes/man.png",
                            scaledSize: new google.maps.Size(40, 40)
                        }
                    });
                }, function() {
                    alert("No se pudo obtener tu ubicación.");
                });
            } else {
                alert("La geolocalización no está soportada por tu navegador.");
            }

            // Obtener los puntos de donación con tipo_id = 1
            obtenerPuntosDonacion(1);
        }

        // Función para obtener los puntos de donación
        function obtenerPuntosDonacion(tipo_id) {
            fetch(`obtener_puntos.php?tipo=${tipo_id}`) // Envía el tipo_id como parámetro
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        // Mostrar marcadores en el mapa
                        data.forEach(punto => {
                            // Crear el contenido del infoWindow
                            const contenidoInfoWindow = `
                                <div style="font-family: Arial, sans-serif; padding: 10px;">
                                    <h3 style="margin: 0; font-size: 16px;">${punto.nombre}</h3>
                                    <p style="margin: 5px 0; font-size: 14px;"><strong>Estado:</strong> ${punto.estado}</p>
                                    <p style="margin: 5px 0; font-size: 14px;"><strong>Municipio:</strong> ${punto.municipio}</p>
                                </div>
                            `;

                            // Crear el infoWindow
                            const infoWindow = new google.maps.InfoWindow({
                                content: contenidoInfoWindow
                            });

                            // Crear el marcador
                            const marcador = new google.maps.Marker({
                                position: { lat: parseFloat(punto.latitud), lng: parseFloat(punto.longitud) },
                                map: map,
                                title: punto.nombre,
                                icon: {
                                    url: 'img/icon/silla.png', // Ruta de la imagen personalizada
                                    scaledSize: new google.maps.Size(40, 40) // Tamaño del ícono
                                }
                            });

                            // Mostrar el infoWindow al hacer clic en el marcador
                            marcador.addListener('click', () => {
                                // Cerrar cualquier infoWindow abierto previamente
                                if (infoWindow) {
                                    infoWindow.close();
                                }
                                // Abrir el infoWindow en el marcador actual
                                infoWindow.open(map, marcador);
                            });

                            marcadores.push(marcador); // Guardar el marcador en el array
                        });
                    } else {
                        console.log(`No hay puntos de donación con tipo_id = ${tipo_id}.`);
                    }
                })
                .catch(error => console.error('Error al obtener los puntos:', error));
        }

        $(document).ready(function() {
            $('#search-articulo, #search-estado, #search-municipio').on('keyup', function() {
                let articulo = $('#search-articulo').val().toLowerCase();
                let estado = $('#search-estado').val().toLowerCase();
                let municipio = $('#search-municipio').val().toLowerCase();
                
                $('#donacion-table-body tr').filter(function() {
                    $(this).toggle($(this).find('td:nth-child(1)').text().toLowerCase().includes(articulo) &&
                                   $(this).find('td:nth-child(4)').text().toLowerCase().includes(estado) &&
                                   $(this).find('td:nth-child(5)').text().toLowerCase().includes(municipio));
                });
            });

            $('#donacionModal').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);
                let articulo = button.data('articulo');
                let cantidad = button.data('cantidad');
                let unidad = button.data('unidad');
                let estado = button.data('estado');
                let municipio = button.data('municipio');
                
                $('#modal-articulo').text(articulo);
                $('#modal-cantidad').text(cantidad);
                $('#modal-unidad').text(unidad);
                $('#modal-estado').text(estado);
                $('#modal-municipio').text(municipio);
            });
        });
    </script>

    <script async defer 
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASN5vvit35yMwGrde7tn4dUgsSVElbpzU&callback=initMap">
    </script>
</body>
</html>