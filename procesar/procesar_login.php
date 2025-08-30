<?php
session_start();

// Obtener los datos y limpiarlos
$usuario = trim($_POST['usuario'] ?? '');
$password = trim($_POST['password'] ?? '');

// Para que no se acepten datos vacíos
if ($usuario === '' || $password === '') {
    header('Location: ../iniciar_sesion.php?error=' . urlencode('Por favor llena todos los campos'));
    exit;
}

// Sanitizar
$usuario = strip_tags($usuario);
$usuario = preg_replace('/[\x00-\x1F\x7F]/u', '', $usuario);

// Verificar usuario registrado
$usuariosRegistrados = $_SESSION['usuarios_registrados'] ?? [];
$usuarioValido = false;

foreach ($usuariosRegistrados as $usuarioData) {
    if ($usuarioData['usuario'] === $usuario) {
        if (password_verify($password, $usuarioData['password_hash'])) {
            $usuarioValido = true;
            break;
        }
    }
}

// Usuario por defecto (admin/admin123) para compatibilidad
if (!$usuarioValido && $usuario === 'admin' && $password === 'admin123') {
    $usuarioValido = true;
}

if (!$usuarioValido) {
    header('Location: ../iniciar_sesion.php?error=' . urlencode('Usuario o contraseña incorrectos'));
    exit;
}

// Crear la sesión
$_SESSION['auth'] = [
    'logueado' => true,
    'usuario' => $usuario
];

// Redirigir al index
header('Location: ../index.php');
exit;
?>
