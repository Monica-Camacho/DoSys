<?php
require_once 'config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Solicitud Enviada - DoSys</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="icon" type="image/png" href="img/logos/DoSys_chico.png">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
</head>
<body>
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Cargando...</span>
        </div>
    </div>

    <?php require_once 'templates/navbar.php'; ?>

    <div class="container-fluid py-5">
        <div class="container text-center">
            <div class="mx-auto" style="max-width: 600px;">
                <i class="fas fa-check-circle fa-5x text-success mb-4"></i>
                <h1 class="display-5 mb-3">¡Solicitud Enviada!</h1>
                <p class="fs-5 text-muted">
                    Tu solicitud ha sido registrada con éxito. La organización que seleccionaste la revisará pronto.
                    Recibirás una notificación cuando sea validada y publicada.
                </p>
                <hr class="my-4">
                <a href="<?php echo BASE_URL; ?>index.php" class="btn btn-primary p-3 me-2">Volver al Inicio</a>
                <a href="#" class="btn btn-outline-secondary p-3">Ver Mis Solicitudes</a>
            </div>
        </div>
    </div>

    <?php require_once 'templates/footer.php'; ?>
    <?php require_once 'templates/scripts.php'; ?>
</body>
</html>
