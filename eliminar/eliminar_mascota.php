<?php
session_start();

if (empty($_SESSION['auth']['logueado'])) {
    header('Location: ../iniciar_sesion.php');
    exit;
}

// Agarro el nombre de la mascota que quiero borrar
$nombre = trim($_GET['nombre'] ?? '');

// Si el nombre está vacío o no existe en la lista de mascotas, te devuelvo al listado
if ($nombre === '' || !isset($_SESSION['mascotas'][$nombre])) {
    header('Location: ../listas/listar_mascotas.php');
    exit;
}

// Borro la mascota de la sesión
unset($_SESSION['mascotas'][$nombre]);

// Actualizo el localStorage para que se mantenga sincronizado con lo que hay en sesión
echo "<script>
    const mascotas = " . json_encode($_SESSION['mascotas'] ?? [], JSON_UNESCAPED_UNICODE) . ";
    localStorage.setItem('mascotas_" . $_SESSION['auth']['usuario'] . "', JSON.stringify(mascotas));
    window.location.href = '../listas/listar_mascotas.php';
</script>";
exit;
?>
