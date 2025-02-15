let map; // Variable global para el mapa
let marcadores = []; // Array para almacenar los marcadores

function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 12,
        center: { lat: 19.432608, lng: -99.133209 }, // CDMX
    });

    // Obtener ubicación del usuario
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const userLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };
                map.setCenter(userLocation);

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
            () => alert("No se pudo obtener tu ubicación.")
        );
    } else {
        alert("La geolocalización no está soportada por tu navegador.");
    }

    obtenerPuntosDonacion();
}

// Función para obtener los puntos de donación
function obtenerPuntosDonacion() {
    fetch(`obtener_puntos.php`)
        .then((response) => response.json())
        .then((data) => {
            if (data.length > 0) {
                const icono = seleccionarIcono();

                data.forEach((punto) => {
                    const contenidoInfoWindow = `
                        <div style="font-family: Arial, sans-serif; padding: 10px;">
                            <h3 style="margin: 0; font-size: 16px;">${punto.nombre}</h3>
                            <p style="margin: 5px 0; font-size: 14px;"><strong>Estado:</strong> ${punto.estado}</p>
                            <p style="margin: 5px 0; font-size: 14px;"><strong>Municipio:</strong> ${punto.municipio}</p>
                        </div>
                    `;

                    const infoWindow = new google.maps.InfoWindow({
                        content: contenidoInfoWindow,
                    });

                    const marcador = new google.maps.Marker({
                        position: { lat: parseFloat(punto.latitud), lng: parseFloat(punto.longitud) },
                        map: map,
                        title: punto.nombre,
                        icon: {
                            url: icono,
                            scaledSize: new google.maps.Size(40, 40),
                        },
                    });

                    marcador.addListener("click", () => {
                        infoWindow.open(map, marcador);
                    });

                    marcadores.push(marcador);
                });
            } else {
                console.log("No hay puntos de donación disponibles.");
            }
        })
        .catch((error) => console.error("Error al obtener los puntos:", error));
}

// Función para seleccionar el icono en base al ID del body
function seleccionarIcono() {
    const bodyId = document.body.id;

    const iconos = {
        "1": "img/icon/sangre.png",
        "2": "img/icon/medicamento.png",
        "3": "img/icon/silla.png",
    };

    return iconos[bodyId] || "img/icon/default.png";
}
