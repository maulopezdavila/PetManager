<?php
require_once '../models/Mascota.php';
require_once '../models/Dueno.php';
require_once '../models/VisitaMedica.php';

session_start();

// Si no está logueado, redirige al login
if (empty($_SESSION['auth']['logueado'])) {
    header('Location: ../iniciar_sesion.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mascotas - Pet Manager</title>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="../estilos.css">
</head>
<body>
    <div class="contenedor-principal">
        <header class="encabezado">
            <div class="logo-titulo">
                <span class="material-symbols-outlined icono-principal">pets</span>
                <h1>Pet Manager</h1>
            </div>

            <!-- menú de navegación --> 
            <nav class="navegacion">
                <a href="../index.php" class="enlace-nav">Inicio</a>
                <a href="listar_mascotas.php" class="enlace-nav activo">Mascotas</a>
                <a href="listar_duenos.php" class="enlace-nav">Dueños</a>
                <a href="listar_visitas.php" class="enlace-nav">Visitas</a>
            </nav>
            <div class="acciones-usuario">
                <button class="boton-agregar" onclick="window.location.href='../registrar/registrar_mascota.php'">
                    <span class="material-symbols-outlined">add</span>
                    <span>Nueva Mascota</span>
                </button>
                <div class="info-usuario">
                    <div class="avatar-usuario"></div>
                    <div class="datos-usuario">
                        <p class="nombre-usuario"><?php echo htmlspecialchars($_SESSION['auth']['usuario'] ?? ''); ?></p>
                        <p class="rol-usuario">Administrador</p>
                    </div>
                    <a href="../cerrar_sesion.php" class="enlace-cerrar">Cerrar sesión</a>
                </div>
            </div>
        </header>

        <!-- Contenido principal --> 
        <main class="contenido-lista">
            <div class="encabezado-lista">
                <h2>Mascotas</h2>
                <p>Aquí encontrarás todas las mascotas registradas</p>
            </div>

            <!-- Barra de búsqueda y exportar CSV --> 
            <div class="controles-lista">
                <div class="busqueda-filtros">
                    <div class="campo-busqueda">
                        <span class="material-symbols-outlined">search</span>
                        <input type="text" placeholder="Buscar por nombre, raza..." id="buscarMascota">
                    </div>

                </div>
                <form action="../otros/exportar_csv.php" method="POST" class="formulario-exportar">
                    <button type="submit" class="boton-exportar">
                        <span class="material-symbols-outlined">download</span>
                        Exportar CSV
                    </button>
                </form>
            </div>

            <!-- Tabla con la info de las mascotas --> 
            <div class="tabla-contenedor">
                <table class="tabla-datos">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Especie</th>
                            <th>Raza</th>
                            <th>Dueños</th>
                            <th>Última Visita</th>
                            <th>Detalles</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        // Reviso si hay mascotas en la sesión
                        if (isset($_SESSION['mascotas'])) {
                            foreach ($_SESSION['mascotas'] as $mascota) {
                                // Obtener nombres de dueños
                                $duenos = array_map(function($d) {
                                    return $d->getNombre();
                                }, $mascota->getDuenos());
                                
                                // Obtener última visita
                                $visitas = $mascota->getVisitas();
                                if (!empty($visitas)) {
                                    $ultimaVisita = end($visitas)->fecha;
                                } else {
                                    $ultimaVisita = 'Ninguna';
                                }

                                // Muestro cada fila de la tabla con los datos de la mascota
                                echo "<tr>";
                                echo "<td class='nombre-mascota'>" . htmlspecialchars($mascota->getNombre()) . "</td>";
                                echo "<td>" . htmlspecialchars($mascota->getEspecie()) . "</td>";
                                echo "<td>" . htmlspecialchars($mascota->getRaza() ?? 'No especificada') . "</td>";
                                echo "<td>" . htmlspecialchars(implode(', ', $duenos) ?: 'Sin dueño') . "</td>";
                                echo "<td>" . htmlspecialchars($ultimaVisita) . "</td>";
                                echo "<td>";
                                // Botón para ver más detalles de la mascota
                                echo "<button class='boton-accion' onclick='verDetalles(\"" . htmlspecialchars($mascota->getNombre()) . "\")'>";
                                echo "<span class='material-symbols-outlined'>more_horiz</span>";
                                echo "</button>";
                                echo "<td>";
                                //Botón para editar y eleminar mascotas
                                echo "<a href='../editar/editar_mascota.php?nombre=" . urlencode(htmlspecialchars($mascota->getNombre())) . 
                                "' class='boton-accion'><span class='material-symbols-outlined'>edit</span></a>";
                                echo "<a href='../eliminar/eliminar_mascota.php?nombre=" . urlencode(htmlspecialchars($mascota->getNombre())) . 
                                "' class='boton-accion boton-eliminar' onclick='return confirmarEliminacion(\"la mascota\", \"" . 
                                htmlspecialchars($mascota->getNombre()) . "\")'><span class='material-symbols-outlined'>delete</span></a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Si hay mascotas muestro cuántas son --> 
            <?php if (isset($_SESSION['mascotas']) && count($_SESSION['mascotas']) > 0): ?>
                <div class="paginacion">
                    <p class="info-paginacion">Mostrando <?php echo count($_SESSION['mascotas']); ?> mascotas</p>
                </div>
            <?php else: ?>
                <!-- Si no hay mascotas registro la primera --> 
                <div class="estado-vacio">
                    <span class="material-symbols-outlined">pets</span>
                    <h3>No hay mascotas registradas</h3>
                    <p>Comienza registrando tu primera mascota</p>
                    <a href="../registrar/registrar_mascota.php" class="boton-principal">Registrar Primera Mascota</a>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <script src="../scripts.js"></script>
    <script>
        // Función para ver detalles de una mascota
        function verDetalles(nombreMascota) {
            window.location.href = '../otros/buscar_mascota.php?nombre=' + encodeURIComponent(nombreMascota);
        }
    </script>
</body>
</html>
