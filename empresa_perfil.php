<?php
// 1. Carga la configuración e inicia la sesión
require_once 'config.php';
require_once 'conexion_local.php';
session_start();

// 2. Seguridad: Redirige si no hay sesión o el usuario no es de tipo "Empresa"
if (!isset($_SESSION['loggedin']) || $_SESSION['tipo_usuario_id'] != 2) {
    header("Location: " . BASE_URL . "login.php");
    exit;
}

// 3. Obtiene el ID de usuario de la sesión
$usuario_id = $_SESSION['id'];
$empresa_perfil = []; 

// 4. CONSULTA SQL CORREGIDA Y VERIFICADA
$sql = "SELECT 
            ep.nombre_comercial,
            ep.razon_social,
            ep.rfc,
            ep.descripcion,
            u.email,
            u.rol_id,
            (SELECT ruta_archivo FROM documentos WHERE propietario_id = u.id AND tipo_documento_id = 2 ORDER BY fecha_subida DESC LIMIT 1) as logo_url
        FROM usuarios u
        LEFT JOIN empresas_perfil ep ON u.id = ep.usuario_id
        WHERE u.id = ?";

if ($stmt = $conexion->prepare($sql)) {
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $empresa_perfil = $resultado->fetch_assoc();
        echo "<pre>";
print_r($empresa_perfil);
echo "</pre>";

    }
    echo "DEBUG: usuario_id de sesión = " . $usuario_id . "<br>";

    $stmt->close();
}
$conexion->close();

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <script src="https://cdn.userway.org/widget.js" data-account="C07GrJafQK"></script>
    <meta charset="utf-8">
    <title>DoSys - Perfil de Empresa</title>
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
                <h1 class='display-5 mb-0'>Perfil de la Empresa</h1>
                <p class="fs-5 text-muted mb-0">Administra la información pública y de contacto de tu empresa.</p>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Profile Content Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">
                <!-- Columna de la Foto -->
                <div class="col-lg-4">
    <div class="d-flex flex-column text-center align-items-center bg-white p-4 rounded shadow-sm">
        
        <div style="border: 2px dashed red; padding: 10px; margin-bottom: 15px; text-align: left; background-color: #fff0f0; width: 100%;">
            <strong style="color: red;">DEBUG EN VIVO:</strong><br>
            <?php 
                // 1. ¿Existe el array principal?
                if (isset($empresa_perfil) && is_array($empresa_perfil)) {
                    echo "✔️ El array \$empresa_perfil existe.<br>";
                } else {
                    echo "❌ <strong>ERROR: El array \$empresa_perfil NO existe o no es un array.</strong><br>";
                }

                // 2. ¿Existe la clave 'nombre_comercial' dentro del array?
                if (isset($empresa_perfil['nombre_comercial'])) {
                    echo "✔️ La clave 'nombre_comercial' existe.<br>";
                    echo "✔️ Valor: <strong>" . htmlspecialchars($empresa_perfil['nombre_comercial']) . "</strong>";
                } else {
                    echo "❌ <strong>ERROR: La clave 'nombre_comercial' NO existe en el array.</strong>";
                }
            ?>
        </div>
        <img id="profileImage" class="img-fluid rounded-circle mb-3" 
             src="<?php echo !empty($empresa_perfil['logo_url']) ? BASE_URL . htmlspecialchars($empresa_perfil['logo_url']) : BASE_URL . 'img/building.png'; ?>" 
             alt="Logo de la Empresa" 
             style="width: 150px; height: 150px; object-fit: cover;">
        
        <h4 class="mb-1">
            <?php echo htmlspecialchars($empresa_perfil['nombre_comercial'] ?? 'Nombre de la Empresa'); ?>
        </h4>
        
        <button type="button" class="btn btn-outline-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#changeLogoModal">
            <i class="fas fa-camera me-2"></i>Cambiar Logo
        </button>
        
    </div>
</div>

                <!-- Profile Tabs Column -->
                <div class="col-lg-8">
                    <!-- Nav Tabs -->
                    <ul class="nav nav-tabs nav-pills nav-fill mb-4" id="profileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="company-tab" data-bs-toggle="tab" data-bs-target="#company" type="button" role="tab" aria-controls="company" aria-selected="true">
                                <i class="fas fa-building me-2"></i>Perfil de Empresa
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="representative-tab" data-bs-toggle="tab" data-bs-target="#representative" type="button" role="tab" aria-controls="representative" aria-selected="false">
                                <i class="fas fa-user-tie me-2"></i>Representante Legal
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab" aria-controls="security" aria-selected="false">
                                <i class="fas fa-shield-alt me-2"></i>Seguridad
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="profileTabsContent">
                        <!-- Company Info Tab -->
                        <div class="tab-pane fade show active" id="company" role="tabpanel" aria-labelledby="company-tab">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <h5 class="card-title mb-4">Datos de la Empresa</h5>
                                    <form>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="nombre_empresa" class="form-label">Nombre Comercial</label>
                                                <input type="text" class="form-control" id="nombre_empresa" value="Mi Empresa S.A. de C.V." disabled>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="razon_social" class="form-label">Razón Social</label>
                                                <input type="text" class="form-control" id="razon_social" value="Mi Empresa S.A. de C.V." disabled>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="rfc" class="form-label">RFC</label>
                                                <input type="text" class="form-control" id="rfc" value="MEM123456XYZ" disabled>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="telefono_empresa" class="form-label">Teléfono de Contacto</label>
                                                <input type="tel" class="form-control" id="telefono_empresa" value="993-987-6543" disabled>
                                            </div>
                                            <div class="col-12">
                                                <label for="direccion_empresa" class="form-label">Dirección Fiscal</label>
                                                <textarea class="form-control" id="direccion_empresa" rows="2" disabled>Av. Principal 456, Col. Industrial, Villahermosa, Tabasco.</textarea>
                                            </div>
                                             <div class="col-12">
                                                <label for="ubicacion_comercial" class="form-label">Ubicación Comercial (Sucursal Principal)</label>
                                                <textarea class="form-control" id="ubicacion_comercial" rows="2" disabled>Plaza Las Américas, Local 25, Villahermosa, Tabasco.</textarea>
                                            </div>
                                            <div class="col-12">
                                                <label for="descripcion_empresa" class="form-label">Descripción de la Empresa</label>
                                                <textarea class="form-control" id="descripcion_empresa" rows="3" disabled>Somos una empresa comprometida con el apoyo a la comunidad.</textarea>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Legal Representative Tab -->
                        <div class="tab-pane fade" id="representative" role="tabpanel" aria-labelledby="representative-tab">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <h5 class="card-title mb-4">Datos del Representante Legal</h5>
                                    <form>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="rep_nombre" class="form-label">Nombre(s)</label>
                                                <input type="text" class="form-control" id="rep_nombre" value="Ana" disabled>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="rep_apellidos" class="form-label">Apellidos</label>
                                                <input type="text" class="form-control" id="rep_apellidos" value="López Hernández" disabled>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="rep_email" class="form-label">Correo Electrónico</label>
                                                <input type="email" class="form-control" id="rep_email" value="ana.lopez@miempresa.com" disabled>
                                            </div>
                                             <div class="col-md-6">
                                                <label for="rep_telefono" class="form-label">Teléfono</label>
                                                <input type="tel" class="form-control" id="rep_telefono" value="993-111-2233" disabled>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Security Tab -->
                        <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <h5 class="card-title mb-4">Cambiar Correo y Contraseña de la Cuenta</h5>
                                    <form>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label for="email_cuenta" class="form-label">Correo de la Cuenta</label>
                                                <input type="email" class="form-control" id="email_cuenta" value="contacto@miempresa.com" disabled>
                                            </div>
                                            <hr>
                                            <div class="col-md-6">
                                                <label for="current_password" class="form-label">Contraseña Actual</label>
                                                <input type="password" class="form-control" id="current_password" disabled>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="new_password" class="form-label">Nueva Contraseña</label>
                                                <input type="password" class="form-control" id="new_password" disabled>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="mt-4 text-end">
                        <button type="button" class="btn btn-secondary">Editar Perfil</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Profile Content End -->
 
    <!-- Ventana Modal para Subir el logo -->
    </div>
    <div class="modal fade" id="changeLogoModal" tabindex="-1" aria-labelledby="changeLogoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changeLogoModalLabel">Cambiar Logo de la Empresa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="uploadLogoForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="logoFile" class="form-label">Selecciona una imagen (JPG, PNG, GIF - Máx 5MB)</label>
                            <input class="form-control" type="file" id="logoFile" name="logoFile" accept="image/jpeg, image/png, image/gif">
                        </div>
                        <div id="uploadLogoMessage" class="mt-3"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="submitLogoButton">Subir Logo</button>
                </div>
            </div>
        </div>
    </div>

        <!-- Footer Start -->
        <?php require_once 'templates/footer.php'; ?>
        <!-- Footer End -->
         
        <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
        
        <?php require_once 'templates/scripts.php'; ?>

        <script>
        $(document).ready(function() {
            // Cuando se hace clic en "Subir Logo"
            $('#submitLogoButton').on('click', function() {
                var formData = new FormData($('#uploadLogoForm')[0]);
                var messageDiv = $('#uploadLogoMessage');

                messageDiv.html('<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></div>');

                $.ajax({
                    url: '<?php echo BASE_URL; ?>auth/upload_logo_process.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            messageDiv.html('<div class="alert alert-success">' + response.message + '</div>');
                            // Actualizar la imagen del perfil en la página
                            $('#profileImage').attr('src', response.new_logo_url + '?t=' + new Date().getTime());
                            // Cerrar la modal después de 2 segundos
                            setTimeout(function() {
                                $('#changeLogoModal').modal('hide');
                                messageDiv.html(''); // Limpiar mensaje
                            }, 2000);
                        } else {
                            messageDiv.html('<div class="alert alert-danger">' + response.message + '</div>');
                        }
                    },
                    error: function() {
                        messageDiv.html('<div class="alert alert-danger">Error de conexión. Inténtalo de nuevo.</div>');
                    }
                });
            });
        });
        </script>
          
</body>

</html>
