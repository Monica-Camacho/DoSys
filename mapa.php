<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Hospitales</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            box-sizing: border-box;
        }

        .container {
            display: flex;
            width: 100%;
            max-width: 1600px; /* Limita el ancho máximo del contenedor */
            gap: 20px; /* Espacio entre el mapa y el formulario */
            padding: 20px; /* Espacio interno */
            box-sizing: border-box;
        }

        #map-container, .sidebar {
            flex: 1; /* Ambos elementos ocupan el mismo espacio */
            background: white;
            border-radius: 15px;
            padding: 0px;
            box-sizing: border-box;
        }

        #map {
            width: 100%;
            height: 580px; /* Altura fija para el mapa */
            border-radius: 10px;
        }

        .sidebar {
            display: flex;
            flex-direction: column;
        }


        .form-container {
            display: flex;
            flex-direction: column;
        }

        .form-container input {
            margin: 10px 0;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ccc;
            background: #f9f9f9;
            width: 100%;
        }

        .form-container button {
            margin-top: 15px;
            padding: 12px;
            font-size: 16px;
            color: #fff;
            background: #5a2a82;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .form-container button:hover {
            background: #452165;
        }

        .info-window {
            font-size: 14px;
            color: #333;
        }

        .info-window strong {
            color: #d9534f;
            font-size: 16px;
        }

        /* Media queries para pantallas pequeñas */
        @media (max-width: 768px) {
            .container {
                flex-direction: column; /* Cambia a una columna en pantallas pequeñas */
                gap: 10px; /* Menor espacio entre elementos */
                padding: 10px; /* Menos padding en móviles */
            }

            #map-container, .sidebar {
                width: 100%; /* Ocupa el 100% del ancho */
                padding: 15px; /* Padding interno reducido */
            }

            #map {
                height: 400px; /* Altura reducida para móviles */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div id="map-container">
            <div id="map"></div>
        </div>
        <div class="sidebar">
            <h3>Formulario de Donante</h3>
            <div class="form-container">
                <input type="text" id="nombre" placeholder="Nombre" />
                <input type="text" id="apellidoP" placeholder="Apellido Paterno" />
                <input type="text" id="apellidoM" placeholder="Apellido Materno" />
                <button onclick="submitForm()">Enviar</button>
            </div>
        </div>
    </div>
    
    <script>
        function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: { lat: 19.432608, lng: -99.133209 }
            });

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var userLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    map.setCenter(userLocation);
                    var userMarker = new google.maps.Marker({
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

            fetch('get_hospitales.php')
                .then(response => response.json())
                .then(data => {
                    data.forEach(hospital => {
                        var marker = new google.maps.Marker({
                            position: { lat: parseFloat(hospital.lat), lng: parseFloat(hospital.lng) },
                            map: map,
                            title: hospital.nombre,
                            animation: google.maps.Animation.DROP,
                            icon: {
                                url: "https://maps.google.com/mapfiles/kml/shapes/hospitals.png",
                                scaledSize: new google.maps.Size(40, 40)
                            }
                        });
                        var infoWindow = new google.maps.InfoWindow({
                            content: `<div class='info-window'>
                                        <strong>${hospital.nombre}</strong><br>
                                        📍 ${hospital.direccion}<br>
                                        📞 ${hospital.telefono || 'No disponible'}<br>
                                        ⏰ ${hospital.horario_atencion || 'No disponible'}
                                      </div>`
                        });
                        marker.addListener("click", () => {
                            infoWindow.open(map, marker);
                        });
                    });
                });
        }

        function submitForm() {
            const nombre = document.getElementById('nombre').value;
            const apellidoP = document.getElementById('apellidoP').value;
            const apellidoM = document.getElementById('apellidoM').value;
            if (nombre && apellidoP && apellidoM) {
                alert(`Formulario enviado:\nNombre: ${nombre}\nApellido Paterno: ${apellidoP}\nApellido Materno: ${apellidoM}`);
            } else {
                alert('Por favor, llena todos los campos.');
            }
        }
    </script>
    
    <script async defer 
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASN5vvit35yMwGrde7tn4dUgsSVElbpzU&callback=initMap">
    </script>
</body>
</html>