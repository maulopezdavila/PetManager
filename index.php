<?php
require_once 'models/Mascota.php';
require_once 'models/Dueno.php';
require_once 'models/VisitaMedica.php';
require_once 'models/Usuario.php';

session_start();

// Si no está logueado, redirige al login
if (empty($_SESSION['auth']['logueado'])) {
    header('Location: iniciar_sesion.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Manager - Inicio</title>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="contenedor-principal">

        <!-- Encabezado de la página --> 
        <header class="encabezado">
            <div class="logo-titulo">
                <span class="material-symbols-outlined icono-principal">pets</span>
                <h1>Pet Manager</h1>
            </div>

            <!-- Menú de navegación --> 
            <nav class="navegacion">
                <a href="index.php" class="enlace-nav activo">Inicio</a>
                <a href="listas/listar_mascotas.php" class="enlace-nav">Mascotas</a>
                <a href="listas/listar_duenos.php" class="enlace-nav">Dueños</a>
                <a href="listas/listar_visitas.php" class="enlace-nav">Visitas</a>
            </nav>

            <!-- Info del usuario que inició sesión --> 
            <div class="acciones-usuario">
                <div class="info-usuario">
                    <div class="avatar-usuario"></div>
                    <div class="datos-usuario">
                        <p class="nombre-usuario"><?php echo htmlspecialchars($_SESSION['auth']['usuario'] ?? ''); ?></p>
                        <p class="rol-usuario">Administrador</p>
                    </div>

                    <!-- Botón para cerrar sesión --> 
                    <a href="cerrar_sesion.php" class="enlace-cerrar">Cerrar sesión</a>
                </div>
            </div>
        </header>

        <!-- Contenido principal --> 
        <main class="contenido-principal">
            <div class="seccion-bienvenida">
                <h2>Bienvenido a Pet Manager - Sistema de Gestión de Mascotas</h2>
                <p>Gestion para toda la información relacionada a las mascotas</p>
            </div>

            <!-- Tarjetas con accesos rápidos --> 
            <div class="tarjetas-dashboard">
                <a href="registrar/registrar_mascota.php" class="tarjeta-accion">
                    <div class="icono-tarjeta">
                        <span class="material-symbols-outlined">pets</span>
                    </div>
                    <h3>Registrar Mascota</h3>
                </a>

                <a href="registrar/registrar_dueno.php" class="tarjeta-accion">
                    <div class="icono-tarjeta">
                        <span class="material-symbols-outlined">person_add</span>
                    </div>
                    <h3>Registrar Dueño</h3>
                </a>

                <a href="registrar/registrar_visita.php" class="tarjeta-accion">
                    <div class="icono-tarjeta">
                        <span class="material-symbols-outlined">medical_services</span>
                    </div>
                    <h3>Registrar Visita</h3>
                </a>

                <a href="listas/listar_mascotas.php" class="tarjeta-accion">
                    <div class="icono-tarjeta">
                        <span class="material-symbols-outlined">list_alt</span>
                    </div>
                    <h3>Listado de Mascotas</h3>
                </a>
            </div>

            <!-- Buscador de mascotas --> 
            <div class="buscar-seccion">
                <h3>Buscar Mascota</h3>
                <form action="otros/buscar_mascota.php" method="GET" class="formulario-busqueda">
                    <div class="campo-busqueda">
                        <span class="material-symbols-outlined">search</span>
                        <input type="text" name="nombre" placeholder="Nombre de la mascota" required>
                    </div>
                    <button type="submit" class="boton-principal">Buscar</button>
                </form>
            </div>
        </main>
    </div>

    <script src="scripts.js"></script>
</body>
</html>
