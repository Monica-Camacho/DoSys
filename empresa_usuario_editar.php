<?php
require_once 'config.php';
require_once 'conexion_local.php';
session_start();

// 1. AUTENTICACIÓN Y PERMISOS
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}
$admin_id = $_SESSION['id'];

// Obtener la empresa_id y el rol del usuario logueado
$sql_permisos = "SELECT ue.empresa_id, u.rol_id
                 FROM usuarios_x_empresas ue
                 JOIN usuarios u ON ue.usuario_id = u.id
                 WHERE ue.usuario_id = ?";
$stmt_permisos = $conexion->prepare($sql_permisos);
$stmt_permisos->bind_param("i", $admin_id);
$stmt_permisos->execute();
$resultado_permisos = $stmt_permisos->get_result();

if ($resultado_permisos->num_rows === 0) {
    $_SESSION['error_message'] = "No tienes permiso para acceder a esta página o no estás asociado a una empresa.";
    header('Location: index.php'); // Redirigir a una página segura si no tiene permisos
    exit();
}

$permisos = $resultado_permisos->fetch_assoc();
$empresa_id = $permisos['empresa_id'];
$rol_admin = $permisos['rol_id'];
$stmt_permisos->close();

// Solo los administradores (rol_id = 1) de la empresa pueden editar usuarios
if ($rol_admin != 1) {
    $_SESSION['error_message'] = "No tienes permiso para editar usuarios en la empresa.";
    header('Location: empresa_usuarios.php'); // Redirigir a la gestión de usuarios si no es admin
    exit();
}

// Obtener el ID del usuario a editar de la URL
$user_id_to_edit = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$user_id_to_edit) {
    $_SESSION['error_message'] = "ID de usuario no proporcionado o inválido.";
    header('Location: empresa_usuarios.php');
    exit();
}

// Obtener los datos del usuario a editar y verificar que pertenece a la empresa del admin
$user_data = null;
$sql_user_data = "SELECT u.id, u.email, pp.nombre, pp.apellido_paterno, pp.apellido_materno, u.rol_id
                  FROM usuarios u
                  JOIN personas_perfil pp ON u.id = pp.usuario_id
                  JOIN usuarios_x_empresas uxe ON u.id = uxe.usuario_id
                  WHERE u.id = ? AND uxe.empresa_id = ?";
$stmt_user_data = $conexion->prepare($sql_user_data);
$stmt_user_data->bind_param("ii", $user_id_to_edit, $empresa_id);
$stmt_user_data->execute();
$result_user_data = $stmt_user_data->get_result();

if ($result_user_data->num_rows === 0) {
    $_SESSION['error_message'] = "Usuario no encontrado o no pertenece a tu empresa.";
    header('Location: empresa_usuarios.php');
    exit();
}
$user_data = $result_user_data->fetch_assoc();
$stmt_user_data->close();

// Obtener lista de roles para el dropdown (solo roles permitidos para usuarios de empresa)
$roles = $conexion->query("SELECT id, nombre FROM roles WHERE id IN (1, 2)")->fetch_all(MYSQLI_ASSOC);
$conexion->close();

// Capturar el mensaje de error si existe y luego limpiarlo de la sesión
$error_message = '';
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>DoSys - Editar Usuario de Empresa</title>
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
                <h1 class='display-5 mb-0'>Editar Usuario de Empresa</h1>
                <p class="fs-5 text-muted mb-0">Modifica los datos del usuario.</p>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5">
        <div class="container">
            <div class="card border-0 shadow-sm"><div class="card-body p-4 p-md-5">
                <form action="auth/gestionar_equipo_empresa.php" method="POST" class="row g-4">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_data['id']); ?>">

                    <div class="col-md-4">
                        <label for="nombre" class="form-label">Nombre(s)</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($user_data['nombre']); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                        <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" value="<?php echo htmlspecialchars($user_data['apellido_paterno']); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="apellido_materno" class="form-label">Apellido Materno</label>
                        <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" value="<?php echo htmlspecialchars($user_data['apellido_materno']); ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
                        <small class="text-muted">El cambio de correo electrónico solo se aplicará si no está ya registrado.</small>
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label">Nueva Contraseña (opcional)</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Deja en blanco para no cambiar">
                        <small class="text-muted">Mínimo 8 caracteres si se cambia.</small>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="rol_id" class="form-label">Rol del Usuario</label>
                        <select id="rol_id" name="rol_id" class="form-select" required>
                            <option value="" disabled>Selecciona un rol...</option>
                            <?php foreach ($roles as $rol): ?>
                                <option value="<?php echo $rol['id']; ?>" <?php echo ($rol['id'] == $user_data['rol_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($rol['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-12 mt-5 text-center">
                        <a href="empresa_usuarios.php" class="btn btn-secondary me-3">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div></div>
        </div>
    </div>
    
    <?php require_once 'templates/footer.php'; ?>
    <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
    <?php require_once 'templates/scripts.php'; ?>

    <!-- Modal de Error (reutilizado) -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Error al Actualizar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php echo $error_message; // Mostrar el mensaje de error aquí ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Script para mostrar el modal si hay un mensaje de error
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (!empty($error_message)): ?>
                var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                errorModal.show();
            <?php endif; ?>
        });
    </script>
</body>
</html>
