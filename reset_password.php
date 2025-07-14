<?php
session_start();
require_once 'config.php';
require_once 'conexion_local.php';
$token_valido = false;

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Usamos $conexion como en tu proyecto
    $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE reset_token = ? AND reset_token_expires_at > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $token_valido = true;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Restablecer Contraseña - DoSys</title>
    <link rel="icon" type="image/png" href="img/logos/DoSys_chico.png">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    </head>
<body>
    <?php require_once 'templates/topbar.php'; ?>
    <?php require_once 'templates/navbar.php'; ?>

    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white text-center">
                            <h4 class="mb-0">Restablecer Contraseña</h4>
                        </div>
                        <div class="card-body p-4">
                            <?php
                            if (isset($_SESSION['error_message'])) {
                                echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
                                unset($_SESSION['error_message']);
                            }
                            ?>
                            <?php if ($token_valido): ?>
                                <p class="text-center text-muted mb-4">Ingresa tu nueva contraseña.</p>
                                <form action="update_password.php" method="POST">
                                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Nueva Contraseña:</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password_confirm" class="form-label">Confirmar Nueva Contraseña:</label>
                                        <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg">Cambiar Contraseña</button>
                                    </div>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-danger text-center">
                                    <h5 class="alert-heading">Enlace Inválido o Expirado</h5>
                                    <p>Este enlace ya no es válido. Por favor, solicita uno nuevo.</p>
                                    <hr>
                                    <a href="forgot_password.php" class="btn btn-secondary">Solicitar de Nuevo</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php require_once 'templates/footer.php'; ?>
    <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
    <?php require_once 'templates/scripts.php'; ?>
</body>
</html>