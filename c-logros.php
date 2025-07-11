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
    <title>DoSys - Nuestros Logros</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="img/logos/dosyslogochico.ico">

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
        .logro-item .carousel-item img {
            height: 450px;
            object-fit: cover;
            border-radius: 0.5rem;
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

        <!-- Navbar Start -->
        <?php require_once 'templates/navbar.php'; ?>
        <!-- Navbar End -->

    <!-- Header Start -->
    <div class="container-fluid bg-light py-5">
        <div class="container text-center">
            <div>
                <h1 class='display-5 mb-0'>Nuestros Logros</h1>
                <p class="fs-5 text-muted mb-0">Un recuento de nuestro viaje y el impacto que hemos logrado juntos.</p>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Descripción -->
    <div class="container-fluid py-4">
        <div class="container py-4">
            <div class="row g-4">
                <div class="col-12">
                    <div style="text-align: justify;">
                        <p class="lead">
                            DoSys (Donation System) es una herramienta tecnológica diseñada para fomentar y fortalecer la donación altruista en México, enfocándose en tres áreas clave: donación de sangre, medicamentos y dispositivos de asistencia. A lo largo de su desarrollo, DoSys ha sido reconocido en importantes eventos locales y regionales, destacando su impacto y potencial para transformar la cultura de la Donación Altruista en el sector salud. Esta página recopila los logros y acontecimientos que han marcado la trayectoria de este proyecto innovador.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Logros Sections -->

    <!-- Hito: INFOMATRIX -->
    <section class="container-fluid py-5 logro-item">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                    <h2 class="display-6 mb-4">XIX Concurso Iberoamericano de Proyectos Estudiantiles</h2>
                    <p class="text-primary fw-bold">Febrero 2025 - UJAT</p>
                    <p><i class="fas fa-trophy me-2 text-warning"></i><strong>Logro:</strong> Reconocimiento de Plata en "Desarrollo de Software y Videojuegos", con pase a la etapa nacional.</p>
                    <blockquote class="blockquote fst-italic">
                        "Este reconocimiento marca el inicio de una nueva etapa para DoSys, demostrando que nuestro esfuerzo y dedicación están dando frutos."
                    </blockquote>
                    <a href="https://www.facebook.com/TecNMCampusVhsa/posts/937965328515909" class="btn btn-primary rounded-pill py-2 px-4" target="_blank">Ver Evidencia</a>
                </div>
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.3s">
                    <div id="carouselInfomatrix" class="carousel slide shadow-lg rounded" data-bs-ride="carousel">
                        <div class="carousel-indicators"></div>
                        <div class="carousel-inner rounded"></div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselInfomatrix" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span></button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselInfomatrix" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span></button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Hito: 5to Foro -->
    <section class="container-fluid bg-light py-5 logro-item">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow fadeIn order-lg-2" data-wow-delay="0.1s">
                    <h2 class="display-6 mb-4">5º Foro El Edén de la Ciencia</h2>
                    <p class="text-primary fw-bold">Noviembre 2024 - UJAT</p>
                    <p><i class="fas fa-award me-2 text-warning"></i><strong>Logro:</strong> Tercer Lugar en el Área de Ingenierías y Desarrollo Tecnológico.</p>
                    <blockquote class="blockquote fst-italic">
                        "Fue una experiencia increíble ver cómo DoSys fue reconocido por su innovación y aplicabilidad en el sector tecnológico. Este logro nos motiva a seguir mejorando."
                    </blockquote>
                    <a href="https://www.facebook.com/TecNMCampusVhsa/posts/880038037641972" class="btn btn-primary rounded-pill py-2 px-4" target="_blank">Ver Evidencia</a>
                </div>
                <div class="col-lg-6 wow fadeIn order-lg-1" data-wow-delay="0.3s">
                    <div id="carouselForo" class="carousel slide shadow-lg rounded" data-bs-ride="carousel">
                        <div class="carousel-indicators"></div>
                        <div class="carousel-inner rounded"></div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselForo" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span></button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselForo" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span></button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Hito: INNOVATECNM Regional -->
    <section class="container-fluid py-5 logro-item">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                    <h2 class="display-6 mb-4">INNOVATECNM Etapa Regional</h2>
                    <p class="text-primary fw-bold">Septiembre 2024 - ITVH</p>
                    <p><i class="fas fa-flag me-2 text-warning"></i><strong>Logro:</strong> Participación en la etapa regional en el Área de Servicios para la Salud.</p>
                    <blockquote class="blockquote fst-italic">
                        "Llegar a la etapa regional fue un gran paso para DoSys. Pudimos conectarnos con expertos y recibir retroalimentación valiosa para seguir creciendo."
                    </blockquote>
                    <a href="https://www.facebook.com/TecNMCampusVhsa/posts/843402454638864" class="btn btn-primary rounded-pill py-2 px-4" target="_blank">Ver Evidencia</a>
                </div>
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.3s">
                    <div id="carouselInnovatecRegional" class="carousel slide shadow-lg rounded" data-bs-ride="carousel">
                        <div class="carousel-indicators"></div>
                        <div class="carousel-inner rounded"></div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselInnovatecRegional" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span></button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselInnovatecRegional" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span></button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Hito: Campaña ISSET -->
    <section class="container-fluid bg-light py-5 logro-item">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow fadeIn order-lg-2" data-wow-delay="0.1s">
                    <h2 class="display-6 mb-4">Campaña de Donación al ISSET</h2>
                    <p class="text-primary fw-bold">Junio 2024 - ISSET</p>
                    <p><i class="fas fa-hands-helping me-2 text-warning"></i><strong>Logro:</strong> Campaña exitosa de donación altruista.</p>
                    <blockquote class="blockquote fst-italic">
                        "Ver a DoSys en acción durante la campaña de donación al ISSET fue muy gratificante. Pudimos comprobar su impacto positivo en la gestión de donaciones."
                    </blockquote>
                    <a href="https://tabasco.gob.mx/noticias/fomenta-isset-la-donacion-altruista-de-sangre" class="btn btn-primary rounded-pill py-2 px-4" target="_blank">Ver Evidencia</a>
                </div>
                <div class="col-lg-6 wow fadeIn order-lg-1" data-wow-delay="0.3s">
                    <div id="carouselIsset" class="carousel slide shadow-lg rounded" data-bs-ride="carousel">
                        <div class="carousel-indicators"></div>
                        <div class="carousel-inner rounded"></div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselIsset" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span></button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselIsset" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span></button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Hito: INNOVATECNM Local -->
    <section class="container-fluid py-5 logro-item">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                    <h2 class="display-6 mb-4">INNOVATECNM Etapa Local</h2>
                    <p class="text-primary fw-bold">Mayo 2024 - ITVH</p>
                    <p><i class="fas fa-medal me-2 text-warning"></i><strong>Logro:</strong> Segundo Lugar en el Área de Servicios para la Salud.</p>
                    <blockquote class="blockquote fst-italic">
                        "Ganar el segundo lugar en la etapa local fue un gran impulso para nuestro equipo. Nos dio la confianza para seguir adelante y mejorar DoSys."
                    </blockquote>
                    <a href="https://www.facebook.com/share/p/4sbhMqbCzSgFfjV3/?mibextid=oEMz7o" class="btn btn-primary rounded-pill py-2 px-4" target="_blank">Ver Evidencia</a>
                </div>
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.3s">
                    <div id="carouselInnovatecLocal" class="carousel slide shadow-lg rounded" data-bs-ride="carousel">
                        <div class="carousel-indicators"></div>
                        <div class="carousel-inner rounded"></div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselInnovatecLocal" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span></button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselInnovatecLocal" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span></button>
                    </div>
                </div>
            </div>
        </div>
    </section>
        
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
    
    <!-- Script para los carruseles -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            function createCarousel(id, totalImages, imagesPath, imagesExtension) {
                const carousel = document.querySelector(`#${id}`);
                if (!carousel) return;

                const indicatorsContainer = carousel.querySelector(".carousel-indicators");
                const innerContainer = carousel.querySelector(".carousel-inner");

                indicatorsContainer.innerHTML = "";
                innerContainer.innerHTML = "";

                for (let i = 0; i < totalImages; i++) {
                    const indicator = document.createElement("button");
                    indicator.type = "button";
                    indicator.dataset.bsTarget = `#${id}`;
                    indicator.dataset.bsSlideTo = i;
                    indicator.ariaLabel = `Foto ${i + 1}`;
                    if (i === 0) indicator.classList.add("active");
                    indicatorsContainer.appendChild(indicator);

                    const item = document.createElement("div");
                    item.classList.add("carousel-item");
                    if (i === 0) item.classList.add("active");

                    const img = document.createElement("img");
                    img.src = `${imagesPath}${i + 1}${imagesExtension}`;
                    img.alt = `Evidencia del evento ${id}`;
                    img.classList.add("d-block", "w-100");
                    
                    item.appendChild(img);
                    innerContainer.appendChild(item);
                }
            }

            createCarousel("carouselInnovatecLocal", 5, "img/Conocenos/2024_Mayo/imagen_", ".jpg");
            createCarousel("carouselIsset", 11, "img/Conocenos/2024_Junio/imagen_", ".jpg");
            createCarousel("carouselInnovatecRegional", 7, "img/Conocenos/2024_Septiembre/imagen_", ".jpg");
            createCarousel("carouselForo", 4, "img/Conocenos/2024_Noviembre/imagen_", ".jpg");
            createCarousel("carouselInfomatrix", 7, "img/Conocenos/2025_Febrero/imagen_", ".jpg");
        });
    </script>
</body>
</html>
