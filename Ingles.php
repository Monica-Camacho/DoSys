<!DOCTYPE html>
<html lang="en">
  <head>
  <script src="https://cdn.userway.org/widget.js" data-account="C07GrJafQK"></script>

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Donation Points</title>
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
  
                <div class="card-title mb-0">Donation Points in Your Area</div>

                <div class="btn-group dropdown">
                  <button
                    class="btn btn-primary dropdown-toggle"
                    type="button"
                    data-bs-toggle="dropdown"
                  >
                    Language
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li>
                      <a class="dropdown-item" href="mapa.php">Spanish</a>
                      <a class="dropdown-item" href="Mantenimiento.php">Nahuatl</a>
                      <a class="dropdown-item" href="Ingles.php">English</a>
                    </li>
                  </ul>
                </div>

              </div>

                <div class="card-body">
                  <!-- Map container -->
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


<!-- Map Script -->
<script>
  let map; // Global variable for the map
  let markers = []; // Array to store markers

  function initMap() {
    // Create the map with zoom to view all of Mexico
    map = new google.maps.Map(document.getElementById("map"), {
      zoom: 5,  // Changed from 12 to 5 to view the whole country
      center: { lat: 23.6345, lng: -102.5528 }, // Geographic center of Mexico
    });

    // Get user location (but don't change center/zoom)
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        function (position) {
          const userLocation = {
            lat: position.coords.latitude,
            lng: position.coords.longitude,
          };
          
          // Only add location marker without changing the view
          new google.maps.Marker({
            position: userLocation,
            map: map,
            title: "Your location",
            icon: {
              url: "https://maps.google.com/mapfiles/kml/shapes/man.png",
              scaledSize: new google.maps.Size(40, 40),
            },
          });
        },
        function (error) {
          console.log("Geolocation not available:", error);
          // We don't show alerts to avoid bothering the user
        }
      );
    }

    getAllDonationPoints();
  }

  // Function to get ALL donation points
  function getAllDonationPoints() {
    fetch('obtener_puntos.php')
      .then((response) => response.json())
      .then((data) => {
        if (data.length > 0) {
          data.forEach((point) => {
            addMarker(point);
          });
        } else {
          console.log('No donation points available.');
          swal({
            title: "No centers available",
            text: "No donation points were found in the database.",
            icon: "info"
          });
        }
      })
      .catch((error) => {
        console.error("Error getting points:", error);
        swal({
          title: "Error",
          text: "Could not load donation points.",
          icon: "error"
        });
      });
  }

  // Function to add a marker to the map
  function addMarker(point) {
    // Create infoWindow content
    const infoWindowContent = `
      <div style="font-family: Arial, sans-serif; padding: 10px;">
        <h3 style="margin: 0; font-size: 16px;">${point.nombre}</h3>
        <p style="margin: 5px 0; font-size: 14px;"><strong>State:</strong> ${point.estado}</p>
        <p style="margin: 5px 0; font-size: 14px;"><strong>Municipality:</strong> ${point.municipio}</p>
        <button 
          style="background-color: #4CAF50; color: white; border: none; padding: 8px 16px; text-align: center; text-decoration: none; display: inline-block; font-size: 14px; margin-top: 10px; cursor: pointer;"
          onclick="selectLocation('${point.nombre}', ${point.latitud}, ${point.longitud})"
        >
          Learn more
        </button>
      </div>
    `;

    // Create the infoWindow
    const infoWindow = new google.maps.InfoWindow({
      content: infoWindowContent,
    });

    // Create the marker with your custom image
    const marker = new google.maps.Marker({
      position: {
        lat: parseFloat(point.latitud),
        lng: parseFloat(point.longitud),
      },
      map: map,
      title: point.nombre,
      icon: {
        url: "assets/img/icon/heart.png", // Make sure this path is correct
        scaledSize: new google.maps.Size(40, 40),
        anchor: new google.maps.Point(20, 20)
      }
    });

    // Show infoWindow when clicking the marker
    marker.addListener("click", () => {
      if (infoWindow) {
        infoWindow.close();
      }
      infoWindow.open(map, marker);
    });

    markers.push(marker); // Save the marker in the array
  }

  // Function to select a location
  function selectLocation(name, latitude, longitude) {
    // Store data in localStorage
    localStorage.setItem("locationName", name);
    localStorage.setItem("latitude", latitude);
    localStorage.setItem("longitude", longitude);

    // Redirect to confirmation page
    window.location.href = "Tipo-Donacion.html";
  }
</script>

    <!-- Load Google Maps API -->
    <script
      async
      defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASN5vvit35yMwGrde7tn4dUgsSVElbpzU&callback=initMap"
    ></script>
  </body>
</html>