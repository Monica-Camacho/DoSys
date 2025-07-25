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

$sql_permisos = "SELECT ue.empresa_id, u.rol_id FROM usuarios_x_empresas ue JOIN usuarios u ON ue.usuario_id = u.id WHERE ue.usuario_id = ?";
$stmt_permisos = $conexion->prepare($sql_permisos);
$stmt_permisos->bind_param("i", $admin_id);
$stmt_permisos->execute();
$resultado_permisos = $stmt_permisos->get_result();
if ($resultado_permisos->num_rows === 0 || $resultado_permisos->fetch_assoc()['rol_id'] != 1) {
    $_SESSION['error_message'] = "No tienes permiso para acceder a esta página.";
    header('Location: empresa_dashboard.php');
    exit();
}
$stmt_permisos->close();
$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>DoSys - Canjear Beneficio</title>
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
                <h1 class='display-5 mb-0'>Validar Código de Beneficio</h1>
                <p class="fs-5 text-muted mb-0">Introduce el código del donante para validarlo y canjear su beneficio.</p>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4 p-md-5">
                            <form id="validateCodeForm">
                                <div class="mb-3">
                                    <label for="codigo_canje" class="form-label fs-5">Código de Canje</label>
                                    <input type="text" class="form-control form-control-lg" id="codigo_canje" name="codigo_canje" placeholder="Ej: CNJ-A8B7C6D5" required>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">Validar Código</button>
                                </div>
                            </form>
                            <div id="result-container" class="mt-4">
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
    <?php require_once 'templates/footer.php'; ?>
    <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
    
    <?php require_once 'templates/scripts.php'; ?>
    <script>
        document.getElementById('validateCodeForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const codigo = document.getElementById('codigo_canje').value;
            const resultContainer = document.getElementById('result-container');
            
            resultContainer.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>';

            const formData = new FormData();
            formData.append('codigo_canje', codigo);

            fetch('auth/validar_codigo_beneficio.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                let alertClass = data.success ? 'alert-success' : 'alert-danger';
                resultContainer.innerHTML = `<div class="alert ${alertClass}">${data.message}</div>`;
            })
            .catch(error => {
                console.error('Error:', error);
                resultContainer.innerHTML = '<div class="alert alert-danger">Ocurrió un error de conexión.</div>';
            });
        });
    </script>
</body>
</html>