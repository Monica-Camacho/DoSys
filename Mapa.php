<!DOCTYPE html>
<html lang="es">
  <head>
  <script src="https://cdn.userway.org/widget.js" data-account="C07GrJafQK"></script>

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Puntos Donación</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="assets/img/logos/DoSys_chico.png"
      type="image/x-icon"
    />
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

              <div class="card-header d-flex justify-content-between align-items-center">
  
                <div class="card-title mb-0">Puntos de Donación en tu zona</div>

                <div class="btn-group dropdown">
                  <button
                    class="btn btn-primary dropdown-toggle"
                    type="button"
                    data-bs-toggle="dropdown"
                  >
                    Idioma
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li>
                      <a class="dropdown-item" href="mapa.php">Español</a>
                      <a class="dropdown-item" href="Mantenimiento.php">Náhuatl</a>
                      <a class="dropdown-item" href="Ingles.php">Inglés</a>
                    </li>
                  </ul>
                </div>

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
    // Crear el mapa con zoom para ver todo México
    map = new google.maps.Map(document.getElementById("map"), {
      zoom: 5,  // Cambiado de 12 a 5 para ver todo el país
      center: { lat: 23.6345, lng: -102.5528 }, // Centro geográfico de México
    });

    // Obtener la ubicación del usuario (pero no cambiar el centro/zoom)
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        function (position) {
          const userLocation = {
            lat: position.coords.latitude,
            lng: position.coords.longitude,
          };
          
          // Solo agregar marcador de ubicación sin cambiar la vista
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
          console.log("Geolocalización no disponible:", error);
          // No mostramos alertas para no molestar al usuario
        }
      );
    }

    obtenerTodosLosPuntos();
  }

  // Función para obtener TODOS los puntos de donación
  function obtenerTodosLosPuntos() {
    fetch('obtener_puntos.php')
      .then((response) => response.json())
      .then((data) => {
        if (data.length > 0) {
          data.forEach((punto) => {
            agregarMarcador(punto);
          });
        } else {
          console.log('No hay puntos de donación disponibles.');
          swal({
            title: "No hay centros disponibles",
            text: "No se encontraron puntos de donación en la base de datos.",
            icon: "info"
          });
        }
      })
      .catch((error) => {
        console.error("Error al obtener los puntos:", error);
        swal({
          title: "Error",
          text: "No se pudieron cargar los puntos de donación.",
          icon: "error"
        });
      });
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
          Conocer más
        </button>
      </div>
    `;

    // Crear el infoWindow
    const infoWindow = new google.maps.InfoWindow({
      content: contenidoInfoWindow,
    });

    // Crear el marcador con tu imagen personalizada
    const marcador = new google.maps.Marker({
      position: {
        lat: parseFloat(punto.latitud),
        lng: parseFloat(punto.longitud),
      },
      map: map,
      title: punto.nombre,
      icon: {
        url: "assets/img/icon/heart.png", // Asegúrate que esta ruta es correcta
        scaledSize: new google.maps.Size(40, 40),
        anchor: new google.maps.Point(20, 20)
      }
    });

    // Mostrar el infoWindow al hacer clic en el marcador
    marcador.addListener("click", () => {
      if (infoWindow) {
        infoWindow.close();
      }
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
    window.location.href = "Tipo-Donacion.html";
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