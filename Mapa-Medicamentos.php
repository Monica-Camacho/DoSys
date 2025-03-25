<!DOCTYPE html>
<html lang="es">
  <head>
  <script src="https://cdn.userway.org/widget.js" data-account="C07GrJafQK"></script>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Solicitar Donación</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="assets/img/logos/DoSys_chico.png"
      type="image/x-icon"
    />

    <style>
      .wrapper {
        background-color: #007bff; /* Color azul (primary) */
      }

      .container {
        background-color: #007bff; /* Color azul (primary) */
      }
      .page-inner {
        background-color: #007bff; /* Color azul (primary) */
      }
      .body {
        background-color: #007bff; /* Color azul (primary) */
      }
    </style>    

    <!-- Fonts and icons -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="assets/css/demo.css" />
  </head>
  <body>
    <div class="wrapper">
      <div class="container">
        <div class="page-inner">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <div class="card-title">Asociaciones Altruistas y Centros de Recoleccion en tu zona</div>
                </div>
                <div class="card-body">
                  <!-- Contenedor para el mapa -->
                  <div id="map" style="height: 450px; width: 100%; border: 0;"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Core JS Files -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Chart JS -->
    <script src="assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- jQuery Vector Maps -->
    <script src="assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="assets/js/plugin/jsvectormap/world.js"></script>

    <!-- Sweet Alert -->
    <script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="assets/js/kaiadmin.min.js"></script>

    <!-- Script del Mapa -->
    <script>
      let map; // Variable global para el mapa
      let marcadores = []; // Array para almacenar los marcadores

      function initMap() {
        // Crear el mapa
        map = new google.maps.Map(document.getElementById("map"), {
          zoom: 12,
          center: { lat: 19.432608, lng: -99.133209 }, // Centro inicial (CDMX)
        });

        // Obtener la ubicación del usuario
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(
            function (position) {
              const userLocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude,
              };
              map.setCenter(userLocation);

              // Marcador de la ubicación del usuario
              new google.maps.Marker({
                position: userLocation,
                map: map,
                title: "Tu ubicación",
                icon: {
                  url: "https://maps.google.com/mapfiles/kml/shapes/man.png",
                  scaledSize: new google.maps.Size(40, 40),
                },
              });
            },
            function (error) {
              if (error.code === error.PERMISSION_DENIED) {
                alert("Permiso de geolocalización denegado. Usando ubicación por defecto.");
              } else {
                alert("No se pudo obtener tu ubicación. Usando ubicación por defecto.");
              }
              // Usar una ubicación por defecto si la geolocalización falla
              map.setCenter({ lat: 19.432608, lng: -99.133209 });
            }
          );
        } else {
          alert("La geolocalización no está soportada por tu navegador. Usando ubicación por defecto.");
          map.setCenter({ lat: 19.432608, lng: -99.133209 });
        }

        // Obtener los puntos de donación con tipo_id = 1
        obtenerPuntosDonacion(2);
      }

      // Función para obtener los puntos de donación
      function obtenerPuntosDonacion(tipo_id) {
        fetch(`obtener_puntos.php?tipo=${tipo_id}`) // Envía el tipo_id como parámetro
          .then((response) => response.json())
          .then((data) => {
            if (data.length > 0) {
              // Mostrar marcadores en el mapa
              data.forEach((punto) => {
                agregarMarcador(punto);
              });
            } else {
              console.log(`No hay puntos de donación con tipo_id = ${tipo_id}.`);
            }
          })
          .catch((error) => console.error("Error al obtener los puntos:", error));
      }

      // Función para agregar un marcador al mapa
      function agregarMarcador(punto) {
        // Crear el contenido del infoWindow
        const contenidoInfoWindow = `
          <div style="font-family: Arial, sans-serif; padding: 10px;">
            <h3 style="margin: 0; font-size: 16px;">${punto.nombre}</h3>
            <p style="margin: 5px 0; font-size: 14px;"><strong>Estado:</strong> ${punto.estado}</p>
            <p style="margin: 5px 0; font-size: 14px;"><strong>Municipio:</strong> ${punto.municipio}</p>
            <button 
              style="background-color: #4CAF50; color: white; border: none; padding: 8px 16px; text-align: center; text-decoration: none; display: inline-block; font-size: 14px; margin-top: 10px; cursor: pointer;"
              onclick="seleccionarLugar('${punto.nombre}', ${punto.latitud}, ${punto.longitud})"
            >
              Seleccionar
            </button>
          </div>
        `;

        // Crear el infoWindow
        const infoWindow = new google.maps.InfoWindow({
          content: contenidoInfoWindow,
        });

        // Crear el marcador
        const marcador = new google.maps.Marker({
          position: {
            lat: parseFloat(punto.latitud),
            lng: parseFloat(punto.longitud),
          },
          map: map,
          title: punto.nombre,
          icon: {
            url: "assets/img/icon/medicamento.png", // Ruta de la imagen personalizada
            scaledSize: new google.maps.Size(40, 40), // Tamaño del ícono
          },
        });

        // Mostrar el infoWindow al hacer clic en el marcador
        marcador.addListener("click", () => {
          // Cerrar cualquier infoWindow abierto previamente
          if (infoWindow) {
            infoWindow.close();
          }
          // Abrir el infoWindow en el marcador actual
          infoWindow.open(map, marcador);
        });

        marcadores.push(marcador); // Guardar el marcador en el array
      }

      // Función para seleccionar un lugar
      function seleccionarLugar(nombre, latitud, longitud) {
        // Almacenar los datos en localStorage
        localStorage.setItem("nombreLugar", nombre);
        localStorage.setItem("latitud", latitud);
        localStorage.setItem("longitud", longitud);

        // Redirigir a la página de confirmación
        window.location.href = "Con-Solicitar-Medicamentos.html";
      }
    </script>

    <!-- Cargar la API de Google Maps -->
    <script
      async
      defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASN5vvit35yMwGrde7tn4dUgsSVElbpzU&callback=initMap"
    ></script>
  </body>
</html>