<?php
// Configuración de la conexión a la base de datos en Hostinger
$servername = "localhost"; // Servidor MySQL (en Hostinger es 'localhost')
$username = "u312858745_DoSys"; // Usuario MySQL (ver en Hostinger)
$password = "TU_CONTRASEÑA"; // Contraseña de la BD en Hostinger
$database = "u312858745_dosis"; // Nombre de la base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibir datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido_paterno = $_POST["apellido_paterno"];
    $apellido_materno = $_POST["apellido_materno"];
    $correo = $_POST["correo"];
    $tipo_donacion = $_POST["tipo_donacion"];

    // Verificar si el correo ya está registrado
    $check_email = "SELECT id FROM pre_registros WHERE correo_electronico = ?";
    $stmt_check = $conn->prepare($check_email);
    $stmt_check->bind_param("s", $correo);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Este correo ya está registrado."]);
    } else {
        // Insertar los datos en la base de datos
        $sql = "INSERT INTO pre_registros (nombre, apellido_paterno, apellido_materno, correo_electronico, tipo_donacion) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $nombre, $apellido_paterno, $apellido_materno, $correo, $tipo_donacion);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Registro exitoso."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al registrar."]);
        }

        $stmt->close();
    }

    $stmt_check->close();
    $conn->close();
}
?>
