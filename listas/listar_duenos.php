<?php
require_once '../models/Dueno.php';
require_once '../models/Mascota.php';

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
    <title>Dueños - Pet Manager</title>
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
                <a href="listar_mascotas.php" class="enlace-nav">Mascotas</a>
                <a href="listar_duenos.php" class="enlace-nav activo">Dueños</a>
                <a href="listar_visitas.php" class="enlace-nav">Visitas</a>
            </nav>
            <div class="acciones-usuario">
                <button class="boton-agregar" onclick="window.location.href='../registrar/registrar_dueno.php'">
                    <span class="material-symbols-outlined">add</span>
                    <span>Nuevo Dueño</span>
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

        <main class="contenido-lista">
            <div class="encabezado-lista">
                <h2>Dueños de Mascotas</h2>
                <p>Aquí encontrarás todas los dueños registrados</p>
            </div>

            <!-- barra de búsqueda para filtrar dueños --> 
            <div class="controles-lista">
                <div class="busqueda-filtros">
                    <div class="campo-busqueda">
                        <span class="material-symbols-outlined">search</span>
                        <input type="text" placeholder="Buscar por nombre, teléfono..." id="buscarDueno">
                    </div>
                </div>
            </div>

            <!-- tabla con todos los dueños --> 
            <div class="tabla-contenedor">
                <table class="tabla-datos">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Dirección</th>
                            <th>Mascotas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        // reviso si hay dueños guardados en la sesión
                        if (isset($_SESSION['duenos'])) {

                            // recorro todos los dueños y los muestro en la tabla
                            foreach ($_SESSION['duenos'] as $index => $dueno) {

                                $mascotasAsociadas = [];
                                if (isset($_SESSION['mascotas'])) {
                                    foreach ($_SESSION['mascotas'] as $nombreMascota => $mascota) {
                                        foreach ($mascota->getDuenos() as $d) {
                                            if ($d->getNombre() === $dueno->getNombre() && $d->getTelefono() === $dueno->getTelefono()){
                                                $mascotasAsociadas[] = $nombreMascota;
                                                break;
                                            }
                                        }
                                    }
                                }

                                echo "<tr>";
                                echo "<td class='nombre-dueno'>" . htmlspecialchars($dueno->getNombre()) . "</td>";
                                echo "<td>" . htmlspecialchars($dueno->getTelefono()) . "</td>";
                                echo "<td>" . htmlspecialchars($dueno->getEmail() ?? 'No especificado') . "</td>";
                                echo "<td>" . htmlspecialchars($dueno->getDireccion() ?? 'No especificada') . "</td>";
                                echo "<td>";
                                if (!empty($mascotasAsociadas)) {
                                    $links = array_map(function($n) {
                                        return '<a class="mascota-link" href="../otros/buscar_mascota.php?nombre=' . htmlspecialchars(urlencode($n)) . '">' . htmlspecialchars($n) . '</a>';
                                    }, $mascotasAsociadas);
                                    echo implode(', ', $links);
                                } else {
                                    echo 'Ninguna';
                                }
                                echo "</td>";
                                echo "<td class='acciones'>";
                                echo "<a href='../editar/editar_dueno.php?index=" . $index . "' class='boton-accion'><span class='material-symbols-outlined'>edit</span></a>";
                                echo "<a href='../eliminar/eliminar_dueno.php?index=" . $index . "' class='boton-accion boton-eliminar' onclick='return confirmarEliminacion(\"el dueño\", \"" . htmlspecialchars($dueno->getNombre()) . "\")'><span class='material-symbols-outlined'>delete</span></a>";
                                echo "</td>";
                                echo "<div class='menu-contenedor'>";
                                echo "</button>";
                                echo "</div>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <!-- si hay dueños muestro cuántos son --> 
            <?php if (isset($_SESSION['duenos']) && count($_SESSION['duenos']) > 0): ?>
                <div class="paginacion">
                    <p class="info-paginacion">Mostrando <?php echo count($_SESSION['duenos']); ?> dueños</p>
                </div>
            
            <?php else: ?>
                <!-- si no hay dueños muestro un mensajito --> 
                <div class="estado-vacio">
                    <span class="material-symbols-outlined">person_add</span>
                    <h3>No hay dueños registrados</h3>
                    <p>Comienza registrando el primer dueño</p>
                    <a href="../registrar/registrar_dueno.php" class="boton-principal">Registrar Primer Dueño</a>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <script src="../scripts.js"></script>
    <script>
        // Configurar búsqueda para dueños
        document.addEventListener('DOMContentLoaded', function() {
            const inputBusqueda = document.getElementById('buscarDueno');
            if (inputBusqueda) {
                inputBusqueda.addEventListener('input', function() {
                    filtrarTablaDuenos(this.value);
                });
            }
        });
        
        function filtrarTablaDuenos(termino) {
            const tabla = document.querySelector('.tabla-datos tbody');
            if (!tabla) return;
            
            const filas = tabla.getElementsByTagName('tr');
            
            for (let i = 0; i < filas.length; i++) {
                const fila = filas[i];
                const celdas = fila.getElementsByTagName('td');
                let mostrar = false;
                
                // Buscar en nombre, teléfono, email, dirección y mascotas
                for (let j = 0; j < 5; j++) {
                    if (celdas[j]) {
                        const texto = celdas[j].textContent.toLowerCase();
                        if (texto.includes(termino.toLowerCase())) {
                            mostrar = true;
                            break;
                        }
                    }
                }
                
                fila.style.display = mostrar ? '' : 'none';
            }
        }


    </script>
</body>
</html>
