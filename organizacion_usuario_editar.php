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
$usuario_a_editar_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$usuario_a_editar_id) {
    header('Location: organizacion_usuarios.php');
    exit();
}

// Verificar que el usuario logueado sea administrador
$stmt_permisos = $conexion->prepare("SELECT rol_id FROM usuarios WHERE id = ?");
$stmt_permisos->bind_param("i", $admin_id);
$stmt_permisos->execute();
$rol_admin = $stmt_permisos->get_result()->fetch_assoc()['rol_id'] ?? null;
$stmt_permisos->close();

if ($rol_admin != 1) {
    $_SESSION['error_message'] = "No tienes permiso para editar usuarios.";
    header('Location: organizacion_usuarios.php');
    exit();
}

// 2. OBTENER DATOS DEL USUARIO A EDITAR
$sql_voluntario = "SELECT u.email, u.estado, u.rol_id, pp.nombre, pp.apellido_paterno 
                   FROM usuarios u 
                   JOIN personas_perfil pp ON u.id = pp.usuario_id 
                   WHERE u.id = ?";
$stmt_voluntario = $conexion->prepare($sql_voluntario);
$stmt_voluntario->bind_param("i", $usuario_a_editar_id);
$stmt_voluntario->execute();
$voluntario = $stmt_voluntario->get_result()->fetch_assoc();
$stmt_voluntario->close();

if (!$voluntario) {
    $_SESSION['error_message'] = "Usuario no encontrado.";
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
    <title>DoSys - Editar Voluntario</title>
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
                <h1 class='display-5 mb-0'>Editar Voluntario</h1>
                <p class="fs-5 text-muted mb-0">Modifica el rol y el estado de un miembro de tu equipo.</p>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5">
        <div class="container">
            <div class="card border-0 shadow-sm"><div class="card-body p-4 p-md-5">
                <h5 class="mb-4">Editando a: <?php echo htmlspecialchars($voluntario['nombre'] . ' ' . $voluntario['apellido_paterno']); ?></h5>
                
                <form action="auth/gestionar_equipo.php" method="POST" class="row g-4">
                    <input type="hidden" name="accion" value="editar">
                    <input type="hidden" name="usuario_id" value="<?php echo $usuario_a_editar_id; ?>">

                    <div class="col-md-6">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($voluntario['email']); ?>" disabled readonly>
                    </div>

                    <div class="col-md-6">
                        <label for="rol_id" class="form-label">Rol del Usuario</label>
                        <select id="rol_id" name="rol_id" class="form-select" required>
                            <?php foreach ($roles as $rol): ?>
                                <option value="<?php echo $rol['id']; ?>" <?php if($rol['id'] == $voluntario['rol_id']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($rol['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="estado" class="form-label">Estado</label>
                        <select id="estado" name="estado" class="form-select" required>
                            <option value="Activo" <?php if($voluntario['estado'] == 'Activo') echo 'selected'; ?>>Activo</option>
                            <option value="Inactivo" <?php if($voluntario['estado'] == 'Inactivo') echo 'selected'; ?>>Inactivo</option>
                            <option value="Pendiente" <?php if($voluntario['estado'] == 'Pendiente') echo 'selected'; ?>>Pendiente</option>
                        </select>
                    </div>

                    <div class="col-12 mt-5 text-center">
                        <a href="organizacion_usuarios.php" class="btn btn-secondary me-3">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
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