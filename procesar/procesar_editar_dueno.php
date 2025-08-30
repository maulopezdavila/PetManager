<?php
require_once '../models/Dueno.php';

session_start();

if (empty($_SESSION['auth']['logueado'])) {
    header('Location: ../iniciar_sesion.php');
    exit;
}

// Agarro los datos que vienen del formulario (POST)
$index = intval($_POST['index'] ?? -1);
$nombre = trim($_POST['nombre'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$email = trim($_POST['email'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$nombreMascota = trim($_POST['mascota'] ?? '');

// Si faltan datos importantes o el dueño no existe, tiro error y doy chance de volver
if ($index < 0 || $nombre === '' || $telefono === '' || !isset($_SESSION['duenos'][$index])) {
    die("Error: Datos inválidos. <a href='../editar/editar_dueno.php?index=$index'>Volver</a>");
}

// Validación del nombre: solo letras, espacios y acentos
$patronNombre = '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/';
if (!preg_match($patronNombre, $nombre)) {
    die("Error: Nombre inválido. <a href='../editar/editar_dueno.php?index=$index'>Volver</a>");
}

// Validación del teléfono: solo números, +, -, paréntesis y espacios
$patronTelefono = '/^[0-9\-\+$$$$\s]+$/';
if (!preg_match($patronTelefono, $telefono)) {
    die("Error: Teléfono inválido. <a href='../editar/editar_dueno.php?index=$index'>Volver</a>");
}

// Cargo el dueño viejo desde sesión
$dueno = $_SESSION['duenos'][$index];
$oldNombre = $dueno->getNombre();
$oldTelefono = $dueno->getTelefono();

// Actualizo la info del dueño con los nuevos datos
$dueno->setNombre($nombre);
$dueno->setTelefono($telefono);
$dueno->setEmail($email ?: null); // si está vacío, lo dejo como null
$dueno->setDireccion($direccion ?: null);

// Sincronizo los cambios en todas las mascotas que tenían a este dueño
if (isset($_SESSION['mascotas'])) {
    foreach ($_SESSION['mascotas'] as $mascota) {
        foreach ($mascota->getDuenos() as $d) {
            if ($d->getNombre() === $oldNombre && $d->getTelefono() === $oldTelefono) {
                $d->setNombre($nombre);
                $d->setTelefono($telefono);
                $d->setEmail($email ?: null);
                $d->setDireccion($direccion ?: null);
            }
        }
    }
}

// Si seleccionaron una mascota en el form, le agrego el dueño actualizado
if ($nombreMascota && isset($_SESSION['mascotas'][$nombreMascota])) {
    $_SESSION['mascotas'][$nombreMascota]->agregarDueno($dueno);
}

// Actualizo localStorage con todas las mascotas para que quede en sync
echo "<script>
    const mascotas = " . json_encode($_SESSION['mascotas'] ?? [], JSON_UNESCAPED_UNICODE) . ";
    localStorage.setItem('mascotas_" . $_SESSION['auth']['usuario'] . "', JSON.stringify(mascotas));
    window.location.href = '../listas/listar_duenos.php';
</script>";
exit;
?>
