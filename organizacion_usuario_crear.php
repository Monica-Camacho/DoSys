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

$sql_permisos = "SELECT rol_id FROM usuarios WHERE id = ?";
$stmt_permisos = $conexion->prepare($sql_permisos);
$stmt_permisos->bind_param("i", $admin_id);
$stmt_permisos->execute();
$resultado_permisos = $stmt_permisos->get_result();
$rol_admin = $resultado_permisos->fetch_assoc()['rol_id'] ?? null;
$stmt_permisos->close();

if ($rol_admin != 1) { // Si el rol no es 1 (Administrador), no puede crear usuarios.
    $_SESSION['error_message'] = "No tienes permiso para acceder a esta página.";
    header('Location: organizacion_usuarios.php');
    exit();
}

// Obtener lista de roles para el dropdown
$roles = $conexion->query("SELECT id, nombre FROM roles WHERE id IN (1, 2)")->fetch_all(MYSQLI_ASSOC);
$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>DoSys - Añadir Nuevo Voluntario</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="icon" type="image/png" href="img/logos/DoSys_chico.png">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans&family=Inter&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center"><div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div></div>
    <?php require_once 'templates/topbar.php'; ?>
    <?php require_once 'templates/navbar.php'; ?>

    <div class="container-fluid bg-light py-5">
        <div class="container">
            <div>
                <h1 class='display-5 mb-0'>Añadir Nuevo Voluntario</h1>
                <p class="fs-5 text-muted mb-0">Completa los datos para registrar un nuevo miembro en tu equipo.</p>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5">
        <div class="container">
            <div class="card border-0 shadow-sm"><div class="card-body p-4 p-md-5">
                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
                <?php endif; ?>
                <form action="auth/crear_usuario_equipo.php" method="POST" class="row g-4">
                    <div class="col-md-4">
                        <label for="nombre" class="form-label">Nombre(s)</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="col-md-4">
                        <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                        <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" required>
                    </div>
                    <div class="col-md-4">
                        <label for="apellido_materno" class="form-label">Apellido Materno</label>
                        <input type="text" class="form-control" id="apellido_materno" name="apellido_materno">
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="rol_id" class="form-label">Rol del Usuario</label>
                        <select id="rol_id" name="rol_id" class="form-select" required>
                            <option value="" selected disabled>Selecciona un rol...</option>
                            <?php foreach ($roles as $rol): ?>
                                <option value="<?php echo $rol['id']; ?>"><?php echo htmlspecialchars($rol['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-12 mt-5 text-center">
                        <a href="organizacion_usuarios.php" class="btn btn-secondary me-3">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Crear Voluntario</button>
                    </div>
                </form>
            </div></div>
        </div>
    </div>
    
    <?php require_once 'templates/footer.php'; ?>
    <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
    <?php require_once 'templates/scripts.php'; ?>
</body>
</html>