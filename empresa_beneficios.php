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
    <title>DoSys - Gestionar Beneficios</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
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
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
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
        <?php require_once 'templates/topbar.php'; ?>
        <!-- Topbar End -->
             
        <!-- Navbar Start -->
        <?php require_once 'templates/navbar.php'; ?>
        <!-- Navbar End -->

    <!-- Header Start -->
    <div class="container-fluid bg-light py-5">
        <div class="container">
            <div>
                <h1 class='display-5 mb-0'>Gestionar Beneficios</h1>
                <p class="fs-5 text-muted mb-0">Crea, edita y administra las promociones para los donantes.</p>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Benefits Management Content Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <!-- Header with Add Button -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Listado de Beneficios</h5>
                        <a href="#" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Añadir Nuevo Beneficio</a>
                    </div>

                    <!-- Filters -->
                    <form class="row g-2 mb-4">
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="Buscar por título o código...">
                        </div>
                        <div class="col-md-4">
                            <select class="form-select">
                                <option selected>Estado...</option>
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                                <option value="expirado">Expirado</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-secondary w-100">Filtrar</button>
                        </div>
                    </form>

                    <!-- Benefits Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Título del Beneficio</th>
                                    <th scope="col">Código</th>
                                    <th scope="col">Inicio de Vigencia</th>
                                    <th scope="col">Fin de Vigencia</th>
                                    <th scope="col" class="text-center">Estado</th>
                                    <th scope="col" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Example Row 1 -->
                                <tr>
                                    <td><strong>Café gratis para donantes</strong></td>
                                    <td><span class="badge bg-light text-dark">DONACAFE24</span></td>
                                    <td>01/07/2024</td>
                                    <td>31/07/2024</td>
                                    <td class="text-center"><span class="badge bg-success">Activo</span></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning me-1" title="Editar"><i class="fas fa-pencil-alt"></i></button>
                                        <button class="btn btn-sm btn-danger" title="Eliminar"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <!-- Example Row 2 -->
                                <tr>
                                    <td><strong>15% de descuento en toda la tienda</strong></td>
                                    <td><span class="badge bg-light text-dark">SANGREVIDA15</span></td>
                                    <td>01/06/2024</td>
                                    <td>30/06/2024</td>
                                    <td class="text-center"><span class="badge bg-danger">Expirado</span></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning me-1" title="Editar"><i class="fas fa-pencil-alt"></i></button>
                                        <button class="btn btn-sm btn-danger" title="Eliminar"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <!-- Example Row 3 -->
                                <tr>
                                    <td><strong>2x1 en entradas de cine</strong></td>
                                    <td><span class="badge bg-light text-dark">DONACINE</span></td>
                                    <td>15/07/2024</td>
                                    <td>15/08/2024</td>
                                    <td class="text-center"><span class="badge bg-success">Activo</span></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning me-1" title="Editar"><i class="fas fa-pencil-alt"></i></button>
                                        <button class="btn btn-sm btn-danger" title="Eliminar"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <!-- Example Row 4 -->
                                <tr>
                                    <td><strong>Postre gratis en tu consumo</strong></td>
                                    <td><span class="badge bg-light text-dark">DULCEAPOYO</span></td>
                                    <td>01/05/2024</td>
                                    <td>31/05/2024</td>
                                    <td class="text-center"><span class="badge bg-secondary">Inactivo</span></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning me-1" title="Editar"><i class="fas fa-pencil-alt"></i></button>
                                        <button class="btn btn-sm btn-danger" title="Eliminar"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Benefits Management Content End -->
        
        <!-- Footer Start -->
        <?php require_once 'templates/footer.php'; ?>
        <!-- Footer End -->
         
        <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
        
        <?php require_once 'templates/scripts.php'; ?>
    
</body>

</html>
