<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>DoSys - Solicitar Sangre</title>
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
    <link href="css/M-Sangre.css" rel="stylesheet">
</head>
<body>

    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Cargando...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Header Start -->
    <div class="container-fluid bg-breadcrumb" style="padding: 15px 0;">
        <div class="container text-center" style="max-width: 1000px;">
        </div>
    </div>
    <!-- Header End -->

    <!-- Mapa y Formulario -->
    <div class="map-form-container">
        <div id="map-container">
            <div id="map"></div>
        </div>
        <div class="sidebar">
        <!-- Contenedor centrado con logo y botón -->
        <div class="d-flex justify-content-center align-items-center gap-3 mb-3">
            <a href="Donaciones.html" class="navbar-brand p-0">
                <img src="img/logos/DoSys_largo_fondoTransparente.png" alt="DoSys_Logo" style="max-height: 80px;">
            </a>
            <a href="Donaciones.html" class="btn btn-primary btn-lg py-2 px-4">Regresar</a>
        </div>

            <!-- Formulario -->
            <form id="formulario">
                <!-- Sección 1: Datos Personales -->
                <div class="seccion" id="seccion1">
                    <h4 class="text-primary">Datos Personales</h4>
                    <div class="form-container">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="nombre" placeholder="Nombre" required />
                            <label for="nombre">Nombre</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="apellidoP" placeholder="Apellido Paterno" required />
                            <label for="apellidoP">Apellido Paterno</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="apellidoM" placeholder="Apellido Materno" required />
                            <label for="apellidoM">Apellido Materno</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" id="fechaNacimiento" required />
                            <label for="fechaNacimiento">Fecha de Nacimiento</label>
                        </div>

                        <h4 class="text-primary">Género</h4>
                        <div class="form-floating mb-3">
                            <select class="form-select border-1" id="genero" name="genero" required>
                                <option value="">Selecciona tu género</option>
                                <option value="hombre">Hombre</option>
                                <option value="mujer">Mujer</option>
                            </select>
                            <label for="genero">Género</label>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary w-100 py-3" onclick="mostrarSeccion(2)">Siguiente</button>
                </div>

                <!-- Sección 2: Solicitud de Donación -->
                <div class="seccion" id="seccion2" style="display: none;">
                    <h4 class="text-primary">Solicitud de Donación</h4>
                    <div class="form-container">
                        <div class="form-floating mb-3">
                            <select class="form-select border-1" id="catalogo-donacion" name="catalogo_donacion" required>
                                <option value="">Selecciona el tipo de donación</option>
                                <option value="1">Sangre</option>
                                <option value="2">Plasma</option>
                                <option value="3">Plaquetas</option>
                                <option value="4">Otro</option>
                            </select>
                            <label for="catalogo-donacion">Tipo de Donación</label>
                        </div>

                        <div class="form-floating mb-3">
                            <select class="form-select border-1" id="puntos-donacion" name="puntos_donacion" required>
                                <option value="">Selecciona el centro de donación</option>
                                <option value="1">Hospital A</option>
                                <option value="2">Hospital B</option>
                                <option value="3">Hospital C</option>
                                <option value="4">Otro</option>
                            </select>
                            <label for="puntos-donacion">Centro de Donación</label>
                        </div>

                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="descripcion" placeholder="Descripción detallada de la necesidad" required></textarea>
                            <label for="descripcion">Descripción</label>
                        </div>

                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="especificaciones" placeholder="Especificaciones adicionales"></textarea>
                            <label for="especificaciones">Especificaciones</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="cantidad" placeholder="Cantidad solicitada" required />
                            <label for="cantidad">Cantidad Solicitada</label>
                        </div>

                        <div class="form-floating mb-3">
                            <select class="form-select border-1" id="unidad-medida" name="unidad_medida" required>
                                <option value="">Selecciona la unidad de medida</option>
                                <option value="ml">Mililitros</option>
                                <option value="unidad">Unidad</option>
                                <option value="caja">Caja</option>
                            </select>
                            <label for="unidad-medida">Unidad de Medida</label>
                        </div>

                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="receta" placeholder="Receta médica (si aplica)"></textarea>
                            <label for="receta">Receta Médica</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3">Enviar Solicitud</button>
                </div>
            </form>

            <!-- Modal para Confirmación de Solicitud -->
            <div class="modal fade" id="modalConfirmacion" tabindex="-1" aria-labelledby="modalConfirmacionLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white"> <!-- Cambio de color en el encabezado -->
                            <h5 class="modal-title" id="modalConfirmacionLabel">Solicitud Registrada</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body text-center"> <!-- Texto centrado -->
                            <div class="mb-3"> <!-- Icono de confirmación -->
                                <i class="fas fa-check-circle fa-4x text-success"></i> <!-- Icono de FontAwesome -->
                            </div>
                            <h4 class="text-primary">¡Gracias por tu solicitud!</h4>
                            <p class="lead">Tu solicitud ha sido registrada exitosamente y está en proceso de revisión.</p>
                            <p>Te notificaremos cuando haya actualizaciones.</p>
                        </div>
                        <div class="modal-footer justify-content-center"> <!-- Pie de modal centrado -->
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
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
                            url: 'img/icon/sangre.png', // Ruta de la imagen personalizada
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



function mostrarSeccion(seccion) {
    // Ocultar todas las secciones
    const secciones = document.querySelectorAll('.seccion');
    secciones.forEach(sec => {
        sec.style.display = 'none'; // Ocultar todas las secciones
    });

    // Mostrar la sección seleccionada
    const seccionActiva = document.getElementById('seccion' + seccion);
    if (seccionActiva) {
        seccionActiva.style.display = 'block'; // Mostrar la sección activa
    }
}

// Mostrar la primera sección al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    mostrarSeccion(1);
});

document.getElementById('formulario').addEventListener('submit', function (e) {
    e.preventDefault(); // Evita que el formulario se envíe de inmediato

    // Aquí puedes agregar lógica para enviar los datos del formulario (por ejemplo, con fetch)
    // Ejemplo de envío de datos con fetch:
    /*
    const formData = new FormData(this);
    fetch('tu_endpoint_aqui', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Respuesta del servidor:', data);
    })
    .catch(error => {
        console.error('Error al enviar la solicitud:', error);
    });
    */

    // Muestra el modal de confirmación
    const modalConfirmacion = new bootstrap.Modal(document.getElementById('modalConfirmacion'));
    modalConfirmacion.show();

    // Recargar la página después de que el modal se cierre
    document.getElementById('modalConfirmacion').addEventListener('hidden.bs.modal', function () {
        window.location.reload(); // Recarga la página
    });
});
    </script>

    <script async defer 
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASN5vvit35yMwGrde7tn4dUgsSVElbpzU&callback=initMap">
    </script>
</body>
</html>