<?php
require_once 'config.php';
require_once 'conexion_local.php';
session_start();

// 1. AUTENTICACIÓN Y OBTENCIÓN DE ID DE EMPRESA Y ROL
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}
$usuario_logueado_id = $_SESSION['id'];

// Se busca la empresa y el rol del usuario que ha iniciado sesión
$sql_permisos = "SELECT uxe.empresa_id, u.rol_id 
                 FROM usuarios_x_empresas uxe
                 JOIN usuarios u ON uxe.usuario_id = u.id
                 WHERE uxe.usuario_id = ?";
$stmt_permisos = $conexion->prepare($sql_permisos);
$stmt_permisos->bind_param("i", $usuario_logueado_id);
$stmt_permisos->execute();
$resultado_permisos = $stmt_permisos->get_result();

if ($resultado_permisos->num_rows === 0) {
    header('Location: persona_dashboard.php'); // No pertenece a una empresa
    exit();
}
$permisos = $resultado_permisos->fetch_assoc();
$empresa_id = $permisos['empresa_id'];
$rol_usuario_logueado = $permisos['rol_id'];
$stmt_permisos->close();

// 2. LÓGICA DE FILTROS
$filtro_busqueda = $_GET['q'] ?? '';
$filtro_rol = filter_input(INPUT_GET, 'rol', FILTER_VALIDATE_INT);
$filtro_estado = $_GET['estado'] ?? '';

// 3. FUNCIÓN PARA OBTENER MIEMBROS DEL EQUIPO CON FILTROS
function obtener_miembros_equipo($conexion, $empresa_id, $busqueda, $rol_id, $estado) {
    $sql = "SELECT 
                u.id AS usuario_id, u.email, u.estado, u.rol_id,
                pp.nombre, pp.apellido_paterno,
                r.nombre AS rol_nombre
            FROM usuarios_x_empresas uxe
            JOIN usuarios u ON uxe.usuario_id = u.id
            JOIN personas_perfil pp ON u.id = pp.usuario_id
            LEFT JOIN roles r ON u.rol_id = r.id
            WHERE uxe.empresa_id = ?";
    
    $params = [$empresa_id];
    $types = 'i';

    if (!empty($busqueda)) {
        $sql .= " AND (CONCAT(pp.nombre, ' ', pp.apellido_paterno) LIKE ? OR u.email LIKE ?)";
        $like_busqueda = "%{$busqueda}%";
        array_push($params, $like_busqueda, $like_busqueda);
        $types .= 'ss';
    }
    if ($rol_id) {
        $sql .= " AND u.rol_id = ?";
        $params[] = $rol_id;
        $types .= 'i';
    }
    if (!empty($estado)) {
        $sql .= " AND u.estado = ?";
        $params[] = $estado;
        $types .= 's';
    }
    $sql .= " ORDER BY pp.nombre";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

$miembros_equipo = obtener_miembros_equipo($conexion, $empresa_id, $filtro_busqueda, $filtro_rol, $filtro_estado);

// Obtener lista de roles para el dropdown del filtro
$roles = $conexion->query("SELECT id, nombre FROM roles WHERE id IN (1, 2)")->fetch_all(MYSQLI_ASSOC);

$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>DoSys - Gestionar Equipo</title>
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
                <h1 class='display-5 mb-0'>Gestionar Equipo</h1>
                <p class="fs-5 text-muted mb-0">Administra los miembros de tu empresa y sus roles.</p>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5">
        <div class="container">
            <div class="card border-0 shadow-sm"><div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title mb-0">Listado de Miembros</h5>
                    <?php if ($rol_usuario_logueado == 1): ?>
                        <a href="empresa_usuario_crear.php" class="btn btn-primary"><i class="fas fa-user-plus me-2"></i>Añadir Nuevo Miembro</a>
                    <?php endif; ?>
                </div>

                <form class="row g-3 mb-4" method="GET">
                    <div class="col-md-5">
                        <input type="text" name="q" class="form-control" placeholder="Buscar por nombre o correo..." value="<?php echo htmlspecialchars($filtro_busqueda); ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="rol" class="form-select">
                            <option value="">Todos los roles...</option>
                            <?php foreach($roles as $rol): ?>
                                <option value="<?php echo $rol['id']; ?>" <?php if($filtro_rol == $rol['id']) echo 'selected'; ?>><?php echo htmlspecialchars($rol['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                         <select name="estado" class="form-select">
                            <option value="">Cualquier estado...</option>
                            <option value="Activo" <?php if($filtro_estado == 'Activo') echo 'selected'; ?>>Activo</option>
                            <option value="Inactivo" <?php if($filtro_estado == 'Inactivo') echo 'selected'; ?>>Inactivo</option>
                            <option value="Pendiente" <?php if($filtro_estado == 'Pendiente') echo 'selected'; ?>>Pendiente</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-secondary">Buscar</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light"><tr><th>Nombre</th><th>Correo Electrónico</th><th class="text-center">Rol</th><th class="text-center">Estado</th><?php if ($rol_usuario_logueado == 1): ?><th class="text-center">Acciones</th><?php endif; ?></tr></thead>
                        <tbody>
                            <?php if (empty($miembros_equipo)): ?>
                                <tr><td colspan="5" class="text-center text-muted">No se encontraron miembros con los filtros seleccionados.</td></tr>
                            <?php else: foreach ($miembros_equipo as $miembro): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($miembro['nombre'] . ' ' . $miembro['apellido_paterno']); ?></strong></td>
                                <td><?php echo htmlspecialchars($miembro['email']); ?></td>
                                
                                <td class="text-center">
                                    <?php
                                        $mapa_colores_rol = ['Administrador' => 'bg-dark', 'Visualizador' => 'bg-info'];
                                        $clase_rol = $mapa_colores_rol[$miembro['rol_nombre']] ?? 'bg-secondary';
                                    ?>
                                    <span class="badge <?php echo $clase_rol; ?>"><?php echo htmlspecialchars($miembro['rol_nombre'] ?? 'Sin rol'); ?></span>
                                </td>
                                <td class="text-center">
                                    <?php
                                        $mapa_colores_estado = ['Activo' => 'bg-success', 'Inactivo' => 'bg-secondary', 'Pendiente' => 'bg-warning'];
                                        $clase_estado = $mapa_colores_estado[$miembro['estado']] ?? 'bg-light text-dark';
                                    ?>
                                    <span class="badge <?php echo $clase_estado; ?>"><?php echo htmlspecialchars($miembro['estado']); ?></span>
                                </td>
                                <?php if ($rol_usuario_logueado == 1): ?>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary me-2" title="Editar"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editarUsuarioModal"
                                        data-usuario-id="<?php echo $miembro['usuario_id']; ?>"
                                        data-usuario-nombre="<?php echo htmlspecialchars($miembro['nombre'] . ' ' . $miembro['apellido_paterno']); ?>"
                                        data-rol-id="<?php echo $miembro['rol_id']; ?>"
                                        data-estado="<?php echo htmlspecialchars($miembro['estado']); ?>">
                                        <i class="fas fa-edit"></i> Editar
                                    </button>
                                    
                                    <?php if ($miembro['estado'] == 'Activo'): ?>
                                    <form action="auth/gestionar_equipo.php" method="POST" class="d-inline">
                                        <input type="hidden" name="usuario_id" value="<?php echo $miembro['usuario_id']; ?>">
                                        <button type="submit" name="accion" value="eliminar_suave" class="btn btn-sm btn-outline-danger" title="Desactivar Usuario" onclick="return confirm('¿Estás seguro de que quieres desactivar a este usuario?');">
                                            <i class="fas fa-trash-alt"></i> Eliminar
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div></div>
        </div>
    </div>
    
    <div class="modal fade" id="editarUsuarioModal" tabindex="-1" aria-labelledby="editarUsuarioModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editarUsuarioModalLabel">Editar Miembro del Equipo</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="auth/gestionar_equipo.php" method="POST">
            <div class="modal-body">
                <p>Editando a: <strong id="nombreUsuarioEditar"></strong></p>
                
                <input type="hidden" name="accion" value="editar">
                <input type="hidden" name="usuario_id" id="editar_usuario_id">
                
                <div class="mb-3">
                    <label for="editar_rol_id" class="form-label">Rol del Usuario</label>
                    <select id="editar_rol_id" name="rol_id" class="form-select" required>
                        <?php foreach ($roles as $rol): ?>
                            <option value="<?php echo $rol['id']; ?>"><?php echo htmlspecialchars($rol['nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="editar_estado" class="form-label">Estado</label>
                    <select id="editar_estado" name="estado" class="form-select" required>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                        <option value="Pendiente">Pendiente</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary">Guardar Cambios</button>
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
        const editarModal = document.getElementById('editarUsuarioModal');
        if (editarModal) {
            editarModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const usuarioId = button.dataset.usuarioId;
                const usuarioNombre = button.dataset.usuarioNombre;
                const rolId = button.dataset.rolId;
                const estado = button.dataset.estado;

                const modal = event.target;
                modal.querySelector('#nombreUsuarioEditar').textContent = usuarioNombre;
                modal.querySelector('#editar_usuario_id').value = usuarioId;
                modal.querySelector('#editar_rol_id').value = rolId;
                modal.querySelector('#editar_estado').value = estado;
            });
        }
    });
    </script>
</body>
</html>