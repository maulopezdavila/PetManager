<?php
require_once '../models/Dueno.php';
require_once '../models/Mascota.php';

session_start();

// Verificar si está logueado
if (empty($_SESSION['auth']['logueado'])) {
    header('Location: ../iniciar_sesion.php');
    exit;
}

// Obtener datos del formulario
$nombre = trim($_POST['nombre'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$email = trim($_POST['email'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$nombreMascota = trim($_POST['mascota'] ?? '');

// Validaciones básicas
if ($nombre === '' || $telefono === '') {
    die("Error: Completa los campos obligatorios. <a href='../registrar/registrar_dueno.php'>Volver</a>");
}

// Patrones de validación
$patronNombre = '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/';
$patronTelefono = '/^[0-9\-\+$$$$\s]+$/';

// Validar nombre
if (!preg_match($patronNombre, $nombre)) {
    die("Error: El nombre solo puede contener letras y espacios. <a href='../registrar/registrar_dueno.php'>Volver</a>");
}

// Validar teléfono
if (!preg_match($patronTelefono, $telefono)) {
    die("Error: Formato de teléfono inválido. <a href='../registrar/registrar_dueno.php'>Volver</a>");
}

// Crear el dueño
$dueno = new Dueno($nombre, $telefono);

// Agregar datos adicionales
if ($email) {
    $dueno->setEmail($email);
}
if ($direccion) {
    $dueno->setDireccion($direccion);
}

// Si se seleccionó una mascota, agregar el dueño a esa mascota
if ($nombreMascota && isset($_SESSION['mascotas'][$nombreMascota])) {
    $_SESSION['mascotas'][$nombreMascota]->agregarDueno($dueno);
}

// Guardar el dueño en la lista de dueños
if (!isset($_SESSION['duenos'])) {
    $_SESSION['duenos'] = [];
}
$_SESSION['duenos'][] = $dueno;

// Actualizar localStorage
echo "<script>
    const mascotas = " . json_encode($_SESSION['mascotas'] ?? [], JSON_UNESCAPED_UNICODE) . ";
    const duenos = " . json_encode($_SESSION['duenos'] ?? [], JSON_UNESCAPED_UNICODE) . ";
    
    localStorage.setItem('mascotas_" . $_SESSION['auth']['usuario'] . "', JSON.stringify(mascotas));
    localStorage.setItem('duenos_" . $_SESSION['auth']['usuario'] . "', JSON.stringify(duenos));
    
    window.location.href = '../index.php';
</script>";

exit;
?>
