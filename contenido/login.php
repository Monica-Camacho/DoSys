<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <!-- Imagen en la parte superior -->
                    <div class="card-header bg-white text-center">
                        <img src="DoSys_largo_fondoBlanco.png" alt="Donation System" class="img-fluid" style="width: 30%; height: auto;">
                        <hr class="blue-divider">
                        <h2 class="mt-3">Iniciar Sesión</h2>
                    </div>
                    <div class="card-body">
                        <form action="login_process.php" method="post">
                            <!-- Campo de correo electrónico con ícono -->
                            <div class="form-group">
                                <label for="email"><i class="fas fa-envelope"></i> Correo Electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <!-- Campo de contraseña con ícono -->
                            <div class="form-group">
                                <label for="password"><i class="fas fa-lock"></i> Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <!-- Botón de Iniciar Sesión -->
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                            </button>
                        </form>
                        <!-- Enlace para crear cuenta -->
                        <p class="text-center mt-3">¿No tienes una cuenta? <a href="register.php">Crear cuenta</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>