<?php
require_once 'config.php'; // Incluye la configuración y la URL base.
require_once 'conexion_local.php'; // Incluye la conexión a la BD
session_start();

// --- INICIO DE LA LÓGICA PHP PARA GESTIONAR BENEFICIOS ---

// Verificación de seguridad: el usuario debe estar logueado.
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['id'])) {
    header('Location: ' . BASE_URL . 'login.php');
    exit;
}

$usuario_id = $_SESSION['id'];
$errores = []; // Array para guardar los mensajes de error

// Obtener el ID de la empresa a la que pertenece el usuario.
$empresa_id = null;
$sql_get_empresa = "SELECT empresa_id FROM usuarios_x_empresas WHERE usuario_id = ?";
if($stmt_get_empresa = $conexion->prepare($sql_get_empresa)) {
    $stmt_get_empresa->bind_param("i", $usuario_id);
    $stmt_get_empresa->execute();
    $result_empresa = $stmt_get_empresa->get_result();
    if($row_empresa = $result_empresa->fetch_assoc()) {
        $empresa_id = $row_empresa['empresa_id'];
    }
    $stmt_get_empresa->close();
}

if (!$empresa_id) {
    die("Error de seguridad: El usuario actual no está asociado a ninguna empresa.");
}

// --- LÓGICA PARA PROCESAMIENTO DE ACCIONES (POST y GET) ---
$modo_edicion = false;
$beneficio_id = null;
$titulo = '';
$descripcion = '';
$fecha_fin = '';
$estado = 'Activo';

// 1. VERIFICAR SI ESTAMOS EN MODO ELIMINACIÓN (vía GET)
if (isset($_GET['eliminar']) && is_numeric($_GET['eliminar'])) {
    $beneficio_id_a_eliminar = $_GET['eliminar'];
    $sql_delete = "DELETE FROM empresas_apoyos WHERE id = ? AND empresa_id = ?";
    if($stmt_delete = $conexion->prepare($sql_delete)) {
        $stmt_delete->bind_param("ii", $beneficio_id_a_eliminar, $empresa_id);
        if ($stmt_delete->execute()) {
            header("Location: empresa_beneficios.php?status=eliminado");
            exit();
        } else {
            $errores[] = "Error al eliminar el beneficio.";
        }
        $stmt_delete->close();
    }
}


// 2. VERIFICAR SI ESTAMOS EN MODO EDICIÓN (vía GET)
if (isset($_GET['editar']) && is_numeric($_GET['editar'])) {
    $modo_edicion = true;
    $beneficio_id_a_editar = $_GET['editar'];

    $stmt_edit = $conexion->prepare("SELECT * FROM empresas_apoyos WHERE id = ? AND empresa_id = ?");
    $stmt_edit->bind_param("ii", $beneficio_id_a_editar, $empresa_id);
    $stmt_edit->execute();
    $resultado_edit = $stmt_edit->get_result();

    if ($resultado_edit->num_rows === 1) {
        $beneficio_actual = $resultado_edit->fetch_assoc();
        $beneficio_id = $beneficio_actual['id'];
        $titulo = $beneficio_actual['titulo'];
        $descripcion = $beneficio_actual['descripcion'];
        $fecha_fin = $beneficio_actual['fecha_expiracion'];
        $estado = $beneficio_actual['activo'] ? 'Activo' : 'Inactivo';
    } else {
        $modo_edicion = false; // El beneficio no existe o no pertenece a la empresa
    }
    $stmt_edit->close();
}

// 3. PROCESAR EL FORMULARIO (POST) PARA CREAR O ACTUALIZAR
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $beneficio_id = $_POST['beneficio_id'] ?? null;
    $titulo = htmlspecialchars(trim($_POST['titulo']));
    $descripcion = htmlspecialchars(trim($_POST['descripcion']));
    $fecha_fin = trim($_POST['fecha_fin']);
    $estado = $_POST['estado'];

    if (empty($titulo)) $errores[] = "El título es obligatorio.";
    if (empty($descripcion)) $errores[] = "La descripción es obligatoria.";
    if (empty($fecha_fin)) $errores[] = "La fecha de fin de vigencia es obligatoria.";

    if (empty($errores)) {
        $activo_db = ($estado === 'Activo') ? 1 : 0;

        if ($beneficio_id) {
            $sql = "UPDATE empresas_apoyos SET titulo = ?, descripcion = ?, fecha_expiracion = ?, activo = ? WHERE id = ? AND empresa_id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sssiii", $titulo, $descripcion, $fecha_fin, $activo_db, $beneficio_id, $empresa_id);
            $mensaje_exito_query = "status=actualizado";
        } else {
            $tipo_apoyo_id_default = 1; 
            $sql = "INSERT INTO empresas_apoyos (empresa_id, tipo_apoyo_id, titulo, descripcion, fecha_expiracion, activo) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("iisssi", $empresa_id, $tipo_apoyo_id_default, $titulo, $descripcion, $fecha_fin, $activo_db);
            $mensaje_exito_query = "status=creado";
        }

        if ($stmt->execute()) {
            header("Location: empresa_beneficios.php?" . $mensaje_exito_query);
            exit();
        } else {
            $errores[] = "Error al procesar el beneficio: " . $conexion->error;
        }
        $stmt->close();
    }
}

// 4. OBTENER BENEFICIOS EXISTENTES PARA LA TABLA (se ejecuta al final)
$beneficios_existentes = [];
$sql_select = "SELECT id, titulo, descripcion, fecha_expiracion, activo FROM empresas_apoyos WHERE empresa_id = ?";
if($stmt_select = $conexion->prepare($sql_select)) {
    $stmt_select->bind_param("i", $empresa_id);
    $stmt_select->execute();
    $resultado = $stmt_select->get_result();
    $beneficios_existentes = $resultado->fetch_all(MYSQLI_ASSOC);
    $stmt_select->close();
}
$conexion->close();

// --- FIN DE LA LÓGICA PHP ---
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
            <!-- Formulario para Crear/Editar Beneficios -->
            <div class="card border-0 shadow-sm mb-5">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4"><?php echo $modo_edicion ? 'Editar Beneficio' : 'Añadir Nuevo Beneficio'; ?></h5>

                    <!-- Mostrar errores y mensajes de éxito -->
                    <?php if (!empty($errores)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errores as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_GET['status'])): ?>
                        <div class="alert alert-success">
                            <?php 
                                if ($_GET['status'] === 'creado') echo '¡Beneficio creado con éxito!';
                                if ($_GET['status'] === 'actualizado') echo '¡Beneficio actualizado con éxito!';
                                if ($_GET['status'] === 'eliminado') echo '¡Beneficio eliminado correctamente!';
                            ?>
                        </div>
                    <?php endif; ?>

                    <form action="empresa_beneficios.php" method="POST" class="row g-3">
                        <input type="hidden" name="beneficio_id" value="<?php echo $beneficio_id; ?>">

                        <div class="col-md-12">
                            <label for="titulo" class="form-label">Título del Beneficio</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo htmlspecialchars($titulo); ?>" required>
                        </div>
                        <div class="col-12">
                            <label for="descripcion" class="form-label">Descripción y Condiciones</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?php echo htmlspecialchars($descripcion); ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_fin" class="form-label">Fin de Vigencia</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="estado" class="form-label">Estado</label>
                            <select id="estado" name="estado" class="form-select">
                                <option <?php if($estado == 'Activo') echo 'selected'; ?>>Activo</option>
                                <option <?php if($estado == 'Inactivo') echo 'selected'; ?>>Inactivo</option>
                            </select>
                        </div>
                        <div class="col-12 text-end">
                            <?php if ($modo_edicion): ?>
                                <a href="empresa_beneficios.php" class="btn btn-secondary">Cancelar</a>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-primary"><?php echo $modo_edicion ? 'Actualizar Beneficio' : 'Guardar Beneficio'; ?></button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabla de Beneficios Existentes -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">Listado de Beneficios</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Título del Beneficio</th>
                                    <th scope="col">Descripción</th>
                                    <th scope="col">Fin de Vigencia</th>
                                    <th scope="col" class="text-center">Estado</th>
                                    <th scope="col" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($beneficios_existentes)): ?>
                                    <?php foreach ($beneficios_existentes as $beneficio): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($beneficio['titulo']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($beneficio['descripcion']); ?></td>
                                        <td><?php echo date("d/m/Y", strtotime($beneficio['fecha_expiracion'])); ?></td>
                                        <td class="text-center">
                                            <?php if ($beneficio['activo']): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="empresa_beneficios.php?editar=<?php echo $beneficio['id']; ?>" class="btn btn-sm btn-warning me-1" title="Editar"><i class="fas fa-pencil-alt"></i></a>
                                            <!-- AÑADIMOS LA CONFIRMACIÓN JAVASCRIPT AQUÍ -->
                                            <a href="empresa_beneficios.php?eliminar=<?php echo $beneficio['id']; ?>" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar este beneficio? Esta acción no se puede deshacer.');"><i class="fas fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Aún no has creado ningún beneficio.</td>
                                    </tr>
                                <?php endif; ?>
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
