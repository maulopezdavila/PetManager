<?php

require_once '../models/Mascota.php';
require_once '../models/VisitaMedica.php';

session_start();

if (empty($_SESSION['auth']['logueado'])) {
    header('Location: ../iniciar_sesion.php');
    exit;
}

// Agarro el nombre de la mascota y la fecha de la visita que quiero borrar
$nombreMascota = trim($_GET['mascota'] ?? '');
$fecha = trim($_GET['fecha'] ?? '');

// Si no hay nombre, no hay fecha o la mascota no existe -> te devuelvo al listado
if ($nombreMascota === '' || $fecha === '' || !isset($_SESSION['mascotas'][$nombreMascota])) {
    header('Location: ../listas/listar_visitas.php');
    exit;
}

// Traigo la mascota desde la sesión
$mascota = $_SESSION['mascotas'][$nombreMascota];

// Creo un nuevo array de visitas donde NO esté la que quiero eliminar
$newVisitas = [];
foreach ($mascota->getVisitas() as $v) {
    // Solo agrego las visitas que no tengan la misma fecha
    if ($v->getFecha() !== $fecha) {
        $newVisitas[] = $v;
    }
}

// Actualizo las visitas de la mascota con el nuevo array
$mascota->visitas = $newVisitas;

// Actualizo el localStorage para que el cambio se vea reflejado en el navegador
echo "<script>
    const mascotas = " . json_encode($_SESSION['mascotas'], JSON_UNESCAPED_UNICODE) . ";
    localStorage.setItem('mascotas_" . $_SESSION['auth']['usuario'] . "', JSON.stringify(mascotas));
    window.location.href = '../listas/listar_visitas.php';
</script>";
exit;
?>
