<?php
require_once '../models/Mascota.php';
require_once '../models/Dueno.php';
require_once '../models/VisitaMedica.php';

session_start();

// Verificar si está logueado
if (empty($_SESSION['auth']['logueado'])) {
    header('Location: ../iniciar_sesion.php');
    exit;
}

// Obtener las mascotas
$mascotas = $_SESSION['mascotas'] ?? [];

// Preparar el navegador para descarga
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="mascotas_' . date('Y-m-d') . '.csv"');

// Crear el archivo CSV
$salida = fopen('php://output', 'w');
fputs($salida, "\xEF\xBB\xBF");

// Escribir encabezados
fputcsv($salida, [
    'Nombre',
    'Especie', 
    'Raza',
    'Color',
    'Fecha Nacimiento',
    'Dueños',
    'Teléfonos',
    'Última Visita',
    'Total Visitas'
], ',', '"');

// Escribir datos de cada mascota
foreach ($mascotas as $mascota) {
    
    // Obtener nombres y teléfonos de dueños
    $nombresDuenos = array_map(function($d) {
        return $d->getNombre();
    }, $mascota->getDuenos());
    
    $telefonosDuenos = array_map(function($d) {
        return $d->getTelefono();
    }, $mascota->getDuenos());
    
    // Obtener información de visitas
    $visitas = $mascota->getVisitas();
    $ultimaVisita = 'Ninguna';
    $totalVisitas = count($visitas);
    
    if (!empty($visitas)) {
        $ultimaVisita = end($visitas)->fecha;
    }
    
    // Escribir fila
    fputcsv($salida, [
        $mascota->getNombre(),
        $mascota->getEspecie(),
        $mascota->getRaza() ?? 'No especificada',
        $mascota->getColor() ?? 'No especificado',
        $mascota->getFechaNacimiento() ?? 'No especificada',
        implode(', ', $nombresDuenos),
        implode(', ', $telefonosDuenos),
        $ultimaVisita,
        $totalVisitas
    ], ',', '"');
}

// Cerrar el archivo
fclose($salida);
exit;
?>
