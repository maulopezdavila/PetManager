<?php
require_once '../models/Mascota.php';

session_start();

// Verificar si está logueado
if (empty($_SESSION['auth']['logueado'])) {
    header('Location: ../iniciar_sesion.php');
    exit;
}

// Si aún no existe el array de mascotas, lo inicializamos
if (!isset($_SESSION['mascotas'])) {
    $_SESSION['mascotas'] = [];
}

// Obtener los datos del formulario
$nombre = trim($_POST['nombre'] ?? '');
$especie = trim($_POST['especie'] ?? '');
$raza = trim($_POST['raza'] ?? '');
$fechaNacimiento = trim($_POST['fecha_nacimiento'] ?? '');
$color = trim($_POST['color'] ?? '');

// Validación para campos obligatorios
if ($nombre === '' || $especie === '') {
    die("Error: Completa los campos obligatorios. <a href='../registrar/registrar_mascota.php'>Volver</a>");
}

// Patrón para nombres (solo letras y espacios)
$patron = '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/';

// Validaciones
if (!preg_match($patron, $nombre)) {
    die("Error: El nombre solo puede contener letras y espacios. <a href='../registrar/registrar_mascota.php'>Volver</a>");
}

// Verificar si ya existe una mascota con ese nombre
if (isset($_SESSION['mascotas'][$nombre])) {
    die("Error: Ya existe una mascota con ese nombre. <a href='../registrar/registrar_mascota.php'>Volver</a>");
}

// Crear la mascota
$mascota = new Mascota($nombre, $especie);

// Agregar datos adicionales si fueron proporcionados
if ($raza) {
    $mascota->setRaza($raza);
}
if ($fechaNacimiento) {
    $mascota->setFechaNacimiento($fechaNacimiento);
}
if ($color) {
    $mascota->setColor($color);
}

// Agregar dueños seleccionados (opcional)
if (isset($_POST['duenos']) && is_array($_POST['duenos'])) {
    foreach ($_POST['duenos'] as $index) {
        $index = intval($index); // Sanitizar a entero
        if (isset($_SESSION['duenos'][$index])) {
            $mascota->agregarDueno($_SESSION['duenos'][$index]);
        }
    }
}

// Guardar en sesión
$_SESSION['mascotas'][$nombre] = $mascota;

// Actualizar localStorage via JavaScript (se ejecuta en el navegador)
echo "<script>
    // Guardar en localStorage
    const mascotas = " . json_encode($_SESSION['mascotas'], JSON_UNESCAPED_UNICODE) . ";
    localStorage.setItem('mascotas_" . $_SESSION['auth']['usuario'] . "', JSON.stringify(mascotas));
    
    // Redirigir
    window.location.href = '../index.php';
</script>";

exit;
?>
