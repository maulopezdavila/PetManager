<?php
require_once '../models/VisitaMedica.php';
require_once '../models/Mascota.php';

session_start();

if (empty($_SESSION['auth']['logueado'])) {
    header('Location: ../iniciar_sesion.php');
    exit;
}

// Recojo los datos que vienen del form
$nombreMascota = trim($_POST['mascota'] ?? '');
$fechaOriginal = trim($_POST['fecha_original'] ?? '');
$fecha = trim($_POST['fecha'] ?? '');
$diagnostico = trim($_POST['diagnostico'] ?? '');
$tratamiento = trim($_POST['tratamiento'] ?? '');
$motivo = trim($_POST['motivo'] ?? '');
$nombreMascotaNew = trim($_POST['mascota_select'] ?? $nombreMascota);

// Validaciones básicas (que no falte nada y que exista la mascota)
if ($nombreMascota === '' || $fechaOriginal === '' || $fecha === '' || $diagnostico === '' || $tratamiento === '' || !isset($_SESSION['mascotas'][$nombreMascota])) {
    die("Error: Datos inválidos. <a href='../editar/editar_visita.php?mascota=" . urlencode($nombreMascota) . "&fecha=" . urlencode($fechaOriginal) . "'>Volver</a>");
}

// Chequeo que la fecha tenga el formato correcto YYYY-MM-DD
$dt = DateTime::createFromFormat('Y-m-d', $fecha);
if (!$dt || $dt->format('Y-m-d') !== $fecha) {
    die("Error: Fecha inválida. <a href='../editar/editar_visita.php?mascota=" . urlencode($nombreMascota) . "&fecha=" . urlencode($fechaOriginal) . "'>Volver</a>");
}

// Busco la mascota en la sesión
$mascota = $_SESSION['mascotas'][$nombreMascota];

// Recorro las visitas de la mascota y edito la que coincida con la fecha original
foreach ($mascota->getVisitas() as $i => $v) {
    if ($v->getFecha() === $fechaOriginal) {
        $visita = $v;
        $visita->setFecha($fecha);
        $visita->setDiagnostico($diagnostico);
        $visita->setTratamiento($tratamiento);
        $visita->setMotivo($motivo ?: null);
        $mascota->visitas[$i] = $visita; // reemplazo la visita en el array
        break;
    }
}

// Si cambiaron la mascota a la que pertenece la visita
if ($nombreMascotaNew !== $nombreMascota && isset($_SESSION['mascotas'][$nombreMascotaNew])) {
    // Quito la visita de la mascota vieja
    $newVisitas = [];
    foreach ($mascota->getVisitas() as $v) {
        if ($v->getFecha() !== $fechaOriginal) {
            $newVisitas[] = $v;
        }
    }
    $mascota->visitas = $newVisitas;

    // Y la agrego a la mascota nueva
    $_SESSION['mascotas'][$nombreMascotaNew]->agregarVisita($visita);
}

// Actualizo el localStorage con las mascotas ya editadas
echo "<script>
    const mascotas = " . json_encode($_SESSION['mascotas'], JSON_UNESCAPED_UNICODE) . ";
    localStorage.setItem('mascotas_" . $_SESSION['auth']['usuario'] . "', JSON.stringify(mascotas));
    window.location.href = '../listas/listar_visitas.php';
</script>";
exit;
?>
