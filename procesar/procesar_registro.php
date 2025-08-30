<?php
require_once '../models/Usuario.php';

session_start();

// Obtener datos del formulario
$usuario = trim($_POST['usuario'] ?? '');
$password = trim($_POST['password'] ?? '');
$confirmarPassword = trim($_POST['confirmar_password'] ?? '');

// Validaciones básicas
if ($usuario === '' || $password === '' || $confirmarPassword === '') {
    header('Location: ../registro_usuario.php?error=' . urlencode('Por favor completa todos los campos'));
    exit;
}

// Validar que las contraseñas coincidan
if ($password !== $confirmarPassword) {
    header('Location: ../registro_usuario.php?error=' . urlencode('Las contraseñas no coinciden'));
    exit;
}

// Validar formato del usuario
$patronUsuario = '/^[a-zA-Z0-9_]+$/';
if (!preg_match($patronUsuario, $usuario)) {
    header('Location: ../registro_usuario.php?error=' . urlencode('El usuario solo puede contener letras, números y guiones bajos'));
    exit;
}

// Validar longitud del usuario
if (strlen($usuario) < 3 || strlen($usuario) > 20) {
    header('Location: ../registro_usuario.php?error=' . urlencode('El usuario debe tener entre 3 y 20 caracteres'));
    exit;
}

// Validar longitud de la contraseña
if (strlen($password) < 8) {
    header('Location: ../registro_usuario.php?error=' . urlencode('La contraseña debe tener al menos 8 caracteres'));
    exit;
}

// Verificar si el usuario ya existe (simulado)
$usuariosRegistrados = $_SESSION['usuarios_registrados'] ?? [];
foreach ($usuariosRegistrados as $usuarioExistente) {
    if ($usuarioExistente['usuario'] === $usuario) {
        header('Location: ../registro_usuario.php?error=' . urlencode('Este usuario ya existe'));
        exit;
    }
}

// Crear hash de la contraseña
$hashPassword = password_hash($password, PASSWORD_DEFAULT);

// Crear nuevo usuario
$nuevoUsuario = new Usuario($usuario, $hashPassword);

// Guardar en sesión temporal (simulando base de datos)
if (!isset($_SESSION['usuarios_registrados'])) {
    $_SESSION['usuarios_registrados'] = [];
}

$_SESSION['usuarios_registrados'][] = [
    'usuario' => $usuario,
    'password_hash' => $hashPassword,
    'fecha_registro' => date('Y-m-d H:i:s')
];

// Redirigir con mensaje de éxito
header('Location: ../registro_usuario.php?exito=' . urlencode('Usuario registrado exitosamente. Ya puedes iniciar sesión.'));
exit;
?>
