<?php
/**
 * Script para cerrar la sesión del usuario.
 */

// Incluimos el archivo de configuración para poder usar BASE_URL.
// La ruta usa '../' porque estamos subiendo un nivel desde la carpeta 'auth'.
require_once '../config.php';

// Inicia o reanuda la sesión actual.
session_start();

// Destruye todas las variables de la sesión.
$_SESSION = array();

// Destruye la sesión por completo.
session_destroy();

// Redirige al usuario a la página de inicio usando la constante.
header("Location: " . BASE_URL . "index.php");
exit;

?>