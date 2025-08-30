<?php

require_once '../models/Dueno.php';
require_once '../models/Mascota.php';

session_start();

// Si no estás logueado, te manda al login
if (empty($_SESSION['auth']['logueado'])) {
    header('Location: ../iniciar_sesion.php');
    exit;
}

// Agarro el índice del dueño que quiero borrar
$index = intval($_GET['index'] ?? -1);

// Si el índice está raro (negativo o no existe), mando de vuelta a la lista de dueños
if ($index < 0 || !isset($_SESSION['duenos'][$index])) {
    header('Location: ../listas/listar_duenos.php');
    exit;
}

// Obtengo el dueño que se quiere borrar
$dueno = $_SESSION['duenos'][$index];
$nombre = $dueno->getNombre();
$telefono = $dueno->getTelefono();

// Ahora toca quitar a ese dueño de todas las mascotas
if (isset($_SESSION['mascotas'])) {
    foreach ($_SESSION['mascotas'] as $mascota) {
        $newDuenos = [];
        // Recorro los dueños de cada mascota
        foreach ($mascota->getDuenos() as $d) {
            // Solo dejo los que NO sean el dueño que estoy borrando
            if ($d->getNombre() !== $nombre || $d->getTelefono() !== $telefono) {
                $newDuenos[] = $d;
            }
        }
        // Actualizo la lista de dueños de la mascota
        $mascota->duenos = $newDuenos;
    }
}

// Ahora sí, borro el dueño de la sesión
unset($_SESSION['duenos'][$index]);

// Reorganizo el array para que no quede con huecos
$_SESSION['duenos'] = array_values($_SESSION['duenos']); // Re-index array

// Actualizo el localStorage con la lista de mascotas para que no se pierda nada
echo "<script>
    const mascotas = " . json_encode($_SESSION['mascotas'] ?? [], JSON_UNESCAPED_UNICODE) . ";
    localStorage.setItem('mascotas_" . $_SESSION['auth']['usuario'] . "', JSON.stringify(mascotas));
    window.location.href = '../listas/listar_duenos.php';
</script>";
exit;
?>
