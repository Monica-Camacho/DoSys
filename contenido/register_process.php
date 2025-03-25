<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $tipo_sangre = $_POST['tipo_sangre'];
    $enfermedades = $_POST['enfermedades'];
    $alergias = $_POST['alergias'];

    // Procesar archivo subido
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["archivo"]["name"]);
        move_uploaded_file($_FILES["archivo"]["tmp_name"], $target_file);
    }

    // Aquí puedes agregar la lógica para guardar los datos en una base de datos
    // Por ejemplo, usando MySQLi o PDO

    echo "Registro exitoso!";
}
?>