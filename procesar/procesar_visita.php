<?php
require_once '../models/Mascota.php';
require_once '../models/VisitaMedica.php';

session_start();

// Verificar si está logueado
if (empty($_SESSION['auth']['logueado'])) {
    header('Location: ../iniciar_sesion.php');
    exit;
}

// Obtener los datos del formulario
$nombreMascota = trim($_POST['mascota'] ?? '');
$fecha = trim($_POST['fecha'] ?? '');
$diagnostico = trim($_POST['diagnostico'] ?? '');
$tratamiento = trim($_POST['tratamiento'] ?? '');
$motivo = trim($_POST['motivo'] ?? '');

// Validación para campos obligatorios
if ($nombreMascota === '' || $fecha === '' || $diagnostico === '' || $tratamiento === '') {
    die("Error: Todos los campos obligatorios deben ser completados. <a href='../registrar/registrar_visita.php'>Volver</a>");
}

// Validar formato de fecha
$dt = DateTime::createFromFormat('Y-m-d', $fecha);
$fechaValida = $dt && $dt->format('Y-m-d') === $fecha;

if (!$fechaValida) {
    die("Error: La fecha indicada no es válida. <a href='../registrar/registrar_visita.php'>Volver</a>");
}

// Sanitizar texto
$diagnostico = strip_tags($diagnostico);
$diagnostico = preg_replace('/[\x00-\x1F\x7F]/u', '', $diagnostico);
if (mb_strlen($diagnostico) > 2000) {
    die("Error: El diagnóstico es demasiado largo. <a href='../registrar/registrar_visita.php'>Volver</a>");
}

$tratamiento = strip_tags($tratamiento);
$tratamiento = preg_replace('/[\x00-\x1F\x7F]/u', '', $tratamiento);
if (mb_strlen($tratamiento) > 2000) {
    die("Error: El tratamiento es demasiado largo. <a href='../registrar/registrar_visita.php'>Volver</a>");
}

// Crear la visita
$visita = new VisitaMedica($fecha, $diagnostico, $tratamiento);
if ($motivo) {
    $visita->setMotivo($motivo);
}

// Si existe la mascota, agregar la visita
if (isset($_SESSION['mascotas'][$nombreMascota])) {
    $_SESSION['mascotas'][$nombreMascota]->agregarVisita($visita);
    
    // Actualizar localStorage
    echo "<script>
        const mascotas = " . json_encode($_SESSION['mascotas'], JSON_UNESCAPED_UNICODE) . ";
        localStorage.setItem('mascotas_" . $_SESSION['auth']['usuario'] . "', JSON.stringify(mascotas));
        
        window.location.href = '../index.php';
    </script>";
} else {
    die("Error: La mascota especificada no existe. <a href='../registrar/registrar_visita.php'>Volver</a>");
}

exit;
?>
