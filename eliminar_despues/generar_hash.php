<?php
// Muestra errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

// La contraseña que quieres usar para el usuario de prueba
$password_plano = '1234';

// Generar el hash seguro
$hash_seguro = password_hash($password_plano, PASSWORD_DEFAULT);

// Mostrar el hash
echo "<h1>Hash Generado</h1>";
echo "<p>Contraseña Plana: " . htmlspecialchars($password_plano) . "</p>";
echo "<p><strong>Hash Seguro (Copia esto):</strong></p>";
echo '<textarea rows="4" cols="80" readonly>' . htmlspecialchars($hash_seguro) . '</textarea>';

?>