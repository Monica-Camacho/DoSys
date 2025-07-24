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

// Solo los administradores (rol_id = 1) de la empresa pueden gestionar usuarios
if ($rol_admin != 1) {
    $_SESSION['error_message'] = "No tienes permiso para gestionar usuarios de la empresa.";
    header('Location: empresa_dashboard.php'); // Redirigir al dashboard de empresa si no es admin
    exit();
}

// Capturar mensajes de éxito o error de la sesión
$success_message = '';
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

$error_message = '';
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

// Lógica para obtener los usuarios de la empresa
$usuarios_empresa = [];
$sql_usuarios = "SELECT u.id, pp.nombre, pp.apellido_paterno, pp.apellido_materno, u.email, r.nombre AS rol_nombre
                 FROM usuarios u
                 JOIN personas_perfil pp ON u.id = pp.usuario_id
                 JOIN usuarios_x_empresas uxe ON u.id = uxe.usuario_id
                 JOIN roles r ON u.rol_id = r.id
                 WHERE uxe.empresa_id = ?
                 ORDER BY pp.apellido_paterno, pp.nombre";
$stmt_usuarios = $conexion->prepare($sql_usuarios);
$stmt_usuarios->bind_param("i", $empresa_id);
$stmt_usuarios->execute();
$resultado_usuarios = $stmt_usuarios->get_result();
while ($row = $resultado_usuarios->fetch_assoc()) {
    $usuarios_empresa[] = $row;
}
$stmt_usuarios->close();
$conexion->close(); // Cerrar la conexión después de usarla
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>DoSys - Gestión de Usuarios de Empresa</title>
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
                <h1 class='display-5 mb-0'>Gestión de Usuarios de Empresa</h1>
                <p class="fs-5 text-muted mb-0">Administra los miembros de tu equipo empresarial.</p>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5">
        <div class="container">
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $success_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $error_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-end mb-4">
                <a href="empresa_usuario_crear.php" class="btn btn-primary"><i class="fas fa-user-plus me-2"></i>Añadir Nuevo Usuario</a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Nombre Completo</th>
                                    <th scope="col">Correo Electrónico</th>
                                    <th scope="col">Rol</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($usuarios_empresa)): ?>
                                    <?php foreach ($usuarios_empresa as $usuario): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido_paterno'] . ' ' . $usuario['apellido_materno']); ?></td>
                                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                            <td><span class="badge bg-<?php echo ($usuario['rol_nombre'] == 'Administrador') ? 'success' : 'info'; ?>"><?php echo htmlspecialchars($usuario['rol_nombre']); ?></span></td>
                                            <td>
                                                <a href="empresa_usuario_editar.php?id=<?php echo $usuario['id']; ?>" class="btn btn-sm btn-outline-primary me-2"><i class="fas fa-edit"></i> Editar</a>
                                                <a href="#" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-user-id="<?php echo $usuario['id']; ?>" data-user-name="<?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido_paterno']); ?>"><i class="fas fa-trash-alt"></i> Eliminar</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No hay usuarios registrados en esta empresa.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'templates/footer.php'; ?>
    <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>
    <?php require_once 'templates/scripts.php'; ?>

    <!-- Modal de Confirmación de Eliminación -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar al usuario <strong id="userNameToDelete"></strong>? Esta acción es irreversible.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteUserForm" action="auth/gestionar_equipo_empresa.php" method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="user_id" id="userIdToDelete">
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var confirmDeleteModal = document.getElementById('confirmDeleteModal');
            if (confirmDeleteModal) {
                confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget; // Botón que activó el modal
                    var userId = button.getAttribute('data-user-id');
                    var userName = button.getAttribute('data-user-name');

                    var modalUserName = confirmDeleteModal.querySelector('#userNameToDelete');
                    var modalUserIdInput = confirmDeleteModal.querySelector('#userIdToDelete');

                    modalUserName.textContent = userName;
                    modalUserIdInput.value = userId;
                });
            }
        });
    </script>
</body>
</html>
