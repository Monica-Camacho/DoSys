<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mt-5">
                    <!-- Imagen en la parte superior -->
                    <div class="card-header bg-white text-center">
                        <img src="DoSys_largo_fondoBlanco.png" alt="Donation System" class="img-fluid">
                        <!-- Barra de separación azul -->
                        <hr class="blue-divider">
                        <!-- Título "Registro" debajo de la barra -->
                        <h2 class="mt-3">Registro</h2>
                    </div>
                    <div class="card-body">
                        <form action="register_process.php" method="post" enctype="multipart/form-data">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="apellidos">Apellidos</label>
                                    <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                            </div>
                            <div class="form-group">
                                <label for="tipo_sangre">Tipo de Sangre</label>
                                <select class="form-control" id="tipo_sangre" name="tipo_sangre" required>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="enfermedades">Enfermedades</label>
                                <textarea class="form-control" id="enfermedades" name="enfermedades" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="alergias">Alergias</label>
                                <textarea class="form-control" id="alergias" name="alergias" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="archivo">Subir Archivo</label>
                                <input type="file" class="form-control-file" id="archivo" name="archivo">
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>