<?php
session_start();

// Eliminar la sesión de autenticación
unset($_SESSION['auth']);

// Opcionalmente, limpiar todos los datos de la sesión
// session_destroy();

// Redirigir al login
header('Location: iniciar_sesion.php');
exit;
?>
