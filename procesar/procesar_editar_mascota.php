<?php
require_once '../models/Mascota.php';

session_start();

if (empty($_SESSION['auth']['logueado'])) {
    header('Location: ../iniciar_sesion.php');
    exit;
}

// Agarro los datos que vienen del form (POST)
$nombreOriginal = trim($_POST['nombre_original'] ?? '');
$nombre = trim($_POST['nombre'] ?? '');
$especie = trim($_POST['especie'] ?? '');
$raza = trim($_POST['raza'] ?? '');
$fechaNacimiento = trim($_POST['fecha_nacimiento'] ?? '');
$color = trim($_POST['color'] ?? '');

// Si faltan datos básicos o la mascota no existe, error y chance de volver
if ($nombre === '' || $especie === '' || !isset($_SESSION['mascotas'][$nombreOriginal])) {
    die("Error: Datos inválidos. <a href='../editar/editar_mascota.php?nombre=" . urlencode($nombreOriginal) . "'>Volver</a>");
}

// Valido que el nombre solo tenga letras, acentos y espacios
$patron = '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/';
if (!preg_match($patron, $nombre)) {
    die("Error: Nombre inválido. <a href='../editar/editar_mascota.php?nombre=" . urlencode($nombreOriginal) . "'>Volver</a>");
}

// Si el nombre cambió y ya existe otra mascota con ese nombre, no dejo
if ($nombre !== $nombreOriginal && isset($_SESSION['mascotas'][$nombre])) {
    die("Error: Nombre ya existe. <a href='../editar/editar_mascota.php?nombre=" . urlencode($nombreOriginal) . "'>Volver</a>");
}

// Cargo la mascota vieja desde sesión
$mascota = $_SESSION['mascotas'][$nombreOriginal];

// Actualizo sus datos con lo que viene del formulario
$mascota->setNombre($nombre);
$mascota->setEspecie($especie);
$mascota->setRaza($raza ?: null);
$mascota->setFechaNacimiento($fechaNacimiento ?: null);
$mascota->setColor($color ?: null);

// Borro todos los dueños que tenía y pongo los nuevos seleccionados en el form
$mascota->duenos = [];
if (isset($_POST['duenos']) && is_array($_POST['duenos'])) {
    foreach ($_POST['duenos'] as $index) {
        $index = intval($index);
        if (isset($_SESSION['duenos'][$index])) {
            $mascota->agregarDueno($_SESSION['duenos'][$index]);
        }
    }
}

// Si el nombre cambió, reindexo el array de mascotas con el nuevo nombre
if ($nombre !== $nombreOriginal) {
    $_SESSION['mascotas'][$nombre] = $mascota;
    unset($_SESSION['mascotas'][$nombreOriginal]);
}

// Mando todas las mascotas actualizadas a localStorage para mantener la sicronización
echo "<script>
    const mascotas = " . json_encode($_SESSION['mascotas'], JSON_UNESCAPED_UNICODE) . ";
    localStorage.setItem('mascotas_" . $_SESSION['auth']['usuario'] . "', JSON.stringify(mascotas));
    window.location.href = '../listas/listar_mascotas.php';
</script>";
exit;
?>
