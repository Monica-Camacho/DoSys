<?php
require_once 'config.php';
require_once 'conexion_local.php';
session_start();

// 1. AUTENTICACIÓN Y PERMISOS DE ADMINISTRADOR
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}
$admin_id = $_SESSION['id'];

// Se busca la empresa, su estado, y el rol del usuario logueado
$sql_empresa = "SELECT ep.id, ep.nombre_comercial, ep.estado, u.rol_id 
                FROM empresas_perfil ep
                JOIN usuarios_x_empresas uxe ON ep.id = uxe.empresa_id
                JOIN usuarios u ON uxe.usuario_id = u.id
                WHERE uxe.usuario_id = ?";
$stmt_empresa = $conexion->prepare($sql_empresa);
$stmt_empresa->bind_param("i", $admin_id);
$stmt_empresa->execute();
$empresa = $stmt_empresa->get_result()->fetch_assoc();
$stmt_empresa->close();

// Si no se encuentra la empresa o el usuario no es admin, se le redirige
if (!$empresa || $empresa['rol_id'] != 1) {
    header('Location: empresa_dashboard.php'); // O a donde corresponda
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>DoSys - Configuración de Empresa</title>
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
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center"><div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div></div>
    
    <?php require_once 'templates/topbar.php'; ?>
    <?php require_once 'templates/navbar.php'; ?>

    <div class="container-fluid bg-light py-5">
        <div class="container">
            <div>
                <h1 class='display-5 mb-0'>Configuración de la Empresa</h1>
                <p class="fs-5 text-muted mb-0">Gestiona las opciones avanzadas de tu cuenta.</p>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5">
        <div class="container">
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
            <?php endif; ?>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <?php if ($empresa['estado'] == 'Activa'): ?>
                        <div class="card border-danger shadow-sm">
                            <div class="card-header bg-danger text-white"><h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Zona de Peligro</h5></div>
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6>Desactivar cuenta de la empresa</h6>
                                        <p class="text-muted mb-0 small">Tu empresa dejará de ser visible y sus beneficios no se mostrarán. Podrás reactivarla más tarde.</p>
                                    </div>
                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#confirmacionModal" data-accion="desactivar">Desactivar</button>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card border-success shadow-sm">
                            <div class="card-header bg-success text-white"><h5 class="mb-0"><i class="fas fa-power-off me-2"></i>Cuenta Inactiva</h5></div>
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6>Reactivar cuenta de la empresa</h6>
                                        <p class="text-muted mb-0 small">Tu empresa está actualmente inactiva. Reactívala para volver a ser visible.</p>
                                    </div>
                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmacionModal" data-accion="reactivar">Reactivar</button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="confirmacionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Acción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="auth/gestionar_cuenta_empresa.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="accion" id="modal_accion">
                        <p id="modal_texto">Para confirmar esta acción, por favor introduce tu contraseña.</p>
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger" id="modal_boton_confirmar">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
        
    <?php require_once 'templates/footer.php'; ?>
    <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
    
    <?php require_once 'templates/scripts.php'; ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const confirmacionModal = document.getElementById('confirmacionModal');
        confirmacionModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const accion = button.dataset.accion;
            
            const modalAccionInput = confirmacionModal.querySelector('#modal_accion');
            const modalTexto = confirmacionModal.querySelector('#modal_texto');
            const modalBoton = confirmacionModal.querySelector('#modal_boton_confirmar');

            modalAccionInput.value = accion;

            if (accion === 'desactivar') {
                modalTexto.textContent = 'Para confirmar la DESACTIVACIÓN de tu empresa, por favor introduce tu contraseña.';
                modalBoton.className = 'btn btn-danger';
                modalBoton.textContent = 'Sí, Desactivar';
            } else if (accion === 'reactivar') {
                modalTexto.textContent = 'Para confirmar la REACTIVACIÓN de tu empresa, por favor introduce tu contraseña.';
                modalBoton.className = 'btn btn-success';
                modalBoton.textContent = 'Sí, Reactivar';
            }
        });
    });
    </script>
</body>
</html>