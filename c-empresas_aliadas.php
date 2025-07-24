<?php
require_once 'config.php';
require_once 'conexion_local.php';
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
        <meta charset="utf-8">
        <title>DoSys - Empresas Aliadas</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="img/logos/DoSys_chico.png">

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

    <style>
        .empresa-item {
            position: relative;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            cursor: pointer;
            background-color: #f2f5f9;

        }
        .empresa-item:hover {
            box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important;
        }

        .empresa-item .empresa-img {
            position: relative;
            overflow: hidden;
            background-color: #ffffff;
            height: 200px; /* Altura para el contenedor del logo */
        }

        .empresa-item .empresa-img img {
            width: 100%;
            height: 100%;
            object-fit: contain; /* Usamos 'contain' para que los logos se vean completos */
            padding: 1rem;
            transition: transform 0.5s ease;
        }

        .empresa-item:hover .empresa-img img {
            transform: scale(1.1); /* Efecto de zoom sutil */
        }

        .empresa-item .empresa-img::after {
            position: absolute;
            content: "";
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background: rgba(6, 163, 218, 0.85); /* Color de superposición */
            opacity: 0;
            transition: 0.5s;
        }

        .empresa-item:hover .empresa-img::after {
            opacity: 1;
        }

        .empresa-item .empresa-hover-info {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0;
            transition: 0.5s;
            z-index: 1;
            text-align: center;
            color: white;
        }

        .empresa-item:hover .empresa-hover-info {
            opacity: 1;
        }
    </style>

    </head>
<body>
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div>
    </div>
    
    <?php require_once 'templates/topbar.php'; ?>
    <?php require_once 'templates/navbar.php'; ?>

    <div class="container-fluid bg-light py-5">
        <div class="container text-center">
            <div>
                <h1 class='display-5 mb-0'>Nuestras Empresas Aliadas</h1>
                <p class="fs-5 text-muted mb-0">Organizaciones que fortalecen nuestra misión con su apoyo y confianza.</p>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5">
        <div class="container">
            <?php
            require 'conexion_local.php';
            $query = "SELECT 
                        ep.id, ep.nombre_comercial, ep.descripcion, doc.ruta_archivo AS logo_ruta,
                        GROUP_CONCAT(ea.titulo SEPARATOR '|||') AS apoyos_titulos
                      FROM empresas_perfil ep
                      LEFT JOIN documentos doc ON ep.logo_documento_id = doc.id
                      LEFT JOIN empresas_apoyos ea ON ep.id = ea.empresa_id AND ea.activo = 1
                      WHERE ep.estado = 'Activa'
                      GROUP BY ep.id";
            
            $result = $conexion->query($query);
            $empresas = ($result && $result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : [];
            $conexion->close();
            ?>

            <div class="row g-4 justify-content-center">
                <?php if (!empty($empresas)): ?>
                    <?php foreach ($empresas as $index => $empresa): ?>
                        <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="<?php echo ($index * 0.1) + 0.1; ?>s">
                            
                            <div class="card h-100 shadow-sm empresa-item" 
                                 data-bs-toggle="modal" 
                                 data-bs-target="#apoyosModal"
                                 data-empresa-nombre="<?php echo htmlspecialchars($empresa['nombre_comercial']); ?>"
                                 data-apoyos="<?php echo htmlspecialchars($empresa['apoyos_titulos'] ?? ''); ?>">
                                
                                <div class="empresa-img">
                                    <img src="<?php echo htmlspecialchars($empresa['logo_ruta'] ?? 'img/elements/sin_logo.jpg'); ?>" 
                                         alt="Logo de <?php echo htmlspecialchars($empresa['nombre_comercial']); ?>">
                                    <div class="empresa-hover-info">
                                        <i class="fa fa-eye fa-2x mb-2"></i>
                                        <p class="mb-0">Ver Beneficios</p>
                                    </div>
                                </div>
                                <div class="card-body text-center p-4">
                                    <h5 class="card-title mb-2"><?php echo htmlspecialchars($empresa['nombre_comercial']); ?></h5>
                                    <p class="card-text text-muted small"><?php echo htmlspecialchars($empresa['descripcion']); ?></p>
                                </div>
                            </div>
                            </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p class="lead">Actualmente no hay empresas aliadas para mostrar. ¡Sé la primera en unirte!</p>
                        <a href="r_empresa.php" class="btn btn-primary rounded-pill py-3 px-5">Únete como Empresa</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="modal fade" id="apoyosModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header"><h5 class="modal-title" id="apoyosModalLabel">Apoyos Ofrecidos</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body">
            <h6 class="mb-3" id="modalEmpresaNombre"></h6>
            <div id="modalApoyosLista"></div>
          </div>
          <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button></div>
        </div>
      </div>
    </div>

    <?php require_once 'templates/footer.php'; ?>
    <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
    
    <?php require_once 'templates/scripts.php'; ?>
    
    <script>
    // El script para el modal no necesita cambios.
    document.addEventListener('DOMContentLoaded', function() {
        const apoyosModal = document.getElementById('apoyosModal');
        apoyosModal.addEventListener('show.bs.modal', function (event) {
            const card = event.relatedTarget;
            const empresaNombre = card.dataset.empresaNombre;
            const apoyosTitulosStr = card.dataset.apoyos;
            const modalTitle = apoyosModal.querySelector('#modalEmpresaNombre');
            const modalBody = apoyosModal.querySelector('#modalApoyosLista');

            modalTitle.textContent = 'Beneficios de: ' + empresaNombre;
            modalBody.innerHTML = ''; 

            if (apoyosTitulosStr) {
                const apoyosArray = apoyosTitulosStr.split('|||');
                const ul = document.createElement('ul');
                ul.classList.add('list-group', 'list-group-flush');
                apoyosArray.forEach(titulo => {
                    const li = document.createElement('li');
                    li.classList.add('list-group-item');
                    li.textContent = titulo;
                    ul.appendChild(li);
                });
                modalBody.appendChild(ul);
            } else {
                modalBody.innerHTML = '<p class="text-muted">Esta empresa aún no tiene apoyos específicos registrados.</p>';
            }
        });
    });
    </script>
</body>
</html>