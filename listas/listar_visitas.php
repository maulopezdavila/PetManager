<?php
require_once '../models/Mascota.php';
require_once '../models/VisitaMedica.php';

session_start();

// Si no está logueado, redirige al login
if (empty($_SESSION['auth']['logueado'])) {
    header('Location: ../iniciar_sesion.php');
    exit;
}

// Recopilar todas las visitas de todas las mascotas
$todasLasVisitas = [];
if (isset($_SESSION['mascotas'])) {
    foreach ($_SESSION['mascotas'] as $nombreMascota => $mascota) {
        $visitasMascota = $mascota->getVisitas();
        foreach ($visitasMascota as $visita) {
            $todasLasVisitas[] = [
                'mascota' => $nombreMascota,
                'visita' => $visita
            ];
        }
    }
}

// Ordenar por fecha (más reciente primero)
usort($todasLasVisitas, function($a, $b) {
    return strtotime($b['visita']->getFecha()) - strtotime($a['visita']->getFecha());
});
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitas - Pet Manager</title>
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
                <a href="listar_duenos.php" class="enlace-nav">Dueños</a>
                <a href="listar_visitas.php" class="enlace-nav activo">Visitas</a>
            </nav>
            <div class="acciones-usuario">
                <button class="boton-agregar" onclick="window.location.href='../registrar/registrar_visita.php'">
                    <span class="material-symbols-outlined">add</span>
                    <span>Nueva Visita</span>
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
                <h2>Visitas Médicas</h2>
                <p>Historial completo de todas las visitas médicas</p>
            </div>

            <!-- Barra de búsqueda --> 
            <div class="controles-lista">
                <div class="busqueda-filtros">
                    <div class="campo-busqueda">
                        <span class="material-symbols-outlined">search</span>
                        <input type="text" placeholder="Buscar por mascota, diagnóstico..." id="buscarVisita">
                    </div>
                </div>
            </div>

            <!-- Aquí va la tabla con todas las visitas --> 
            <div class="tabla-contenedor">
                <table class="tabla-datos">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Mascota</th>
                            <th>Motivo</th>
                            <th>Diagnóstico</th>
                            <th>Tratamiento</th>
                            <th>Detalles</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        // Recorro todas las visitas y las imprimo en la tabla
                        foreach ($todasLasVisitas as $item) {
                            $nombreMascota = $item['mascota'];
                            $visita = $item['visita'];
                            
                            echo "<tr>";
                            echo "<td class='fecha-visita'>" . htmlspecialchars($visita->getFecha()) . "</td>";
                            echo "<td class='nombre-mascota'>" . htmlspecialchars($nombreMascota) . "</td>";
                            echo "<td>" . htmlspecialchars($visita->getMotivo() ?? 'No especificado') . "</td>";
                            echo "<td class='diagnostico-celda'>" . htmlspecialchars(substr($visita->getDiagnostico(), 0, 50)) . (strlen($visita->getDiagnostico()) > 50 ? '...' : '') . "</td>";
                            echo "<td class='tratamiento-celda'>" . htmlspecialchars(substr($visita->getTratamiento(), 0, 50)) . (strlen($visita->getTratamiento()) > 50 ? '...' : '') . "</td>";
                            echo "<td>";
                            // Botón para ver más detalles de esa visita
                            echo "<button class='boton-accion' onclick='verDetallesVisita(\"" . htmlspecialchars($nombreMascota) . "\", \"" . htmlspecialchars($visita->getFecha()) . "\")'>";
                            echo "<span class='material-symbols-outlined'>more_horiz</span>";
                            echo "</button>";
                            echo "<td>";
                            echo "<a href='../editar/editar_visita.php?mascota=" . urlencode(htmlspecialchars($nombreMascota)) . "&fecha=" . 
                            urlencode(htmlspecialchars($visita->getFecha())) . "' class='boton-accion'><span class='material-symbols-outlined'>edit</span></a>";
                            echo "<a href='../eliminar/eliminar_visita.php?mascota=" . urlencode(htmlspecialchars($nombreMascota)) . 
                            "&fecha=" . urlencode(htmlspecialchars($visita->getFecha())) . "' class='boton-accion boton-eliminar' onclick='return confirmarEliminacion(\"la visita\", \"" . 
                            htmlspecialchars($visita->getFecha()) . "\")'><span class='material-symbols-outlined'>delete</span></a>";
                            echo "</td>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Si hay visitas, muestro cuántas son. Si no, muestro mensaje vacío --> 
            <?php if (count($todasLasVisitas) > 0): ?>
                <div class="paginacion">
                    <p class="info-paginacion">Mostrando <?php echo count($todasLasVisitas); ?> visitas</p>
                </div>
            <?php else: ?>
                <div class="estado-vacio">
                    <span class="material-symbols-outlined">medical_services</span>
                    <h3>No hay visitas registradas</h3>
                    <p>Comienza registrando la primera visita médica</p>
                    <a href="../registrar/registrar_visita.php" class="boton-principal">Registrar Primera Visita</a>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <script>
        // Función para ver detalles de una visita específica
        function verDetallesVisita(nombreMascota, fecha) {
            window.location.href = '../otros/buscar_mascota.php?nombre=' + encodeURIComponent(nombreMascota);
        }
        
        // Configurar búsqueda para visitas
        document.addEventListener('DOMContentLoaded', function() {
            const inputBusqueda = document.getElementById('buscarVisita');
            if (inputBusqueda) {
                inputBusqueda.addEventListener('input', function() {
                    filtrarTablaVisitas(this.value);
                });
            }
        });
        
        // Esta función es la que revisa fila por fila y esconde o muestra según lo que escriba
        function filtrarTablaVisitas(termino) {
            const tabla = document.querySelector('.tabla-datos tbody');
            if (!tabla) return;
            
            const filas = tabla.getElementsByTagName('tr');
            
            for (let i = 0; i < filas.length; i++) {
                const fila = filas[i];
                const celdas = fila.getElementsByTagName('td');
                let mostrar = false;
                
                // Buscar en fecha, mascota, motivo, diagnóstico y tratamiento
                for (let j = 0; j < 5; j++) {
                    if (celdas[j]) {
                        const texto = celdas[j].textContent.toLowerCase();
                        if (texto.includes(termino.toLowerCase())) {
                            mostrar = true;
                            break;
                        }
                    }
                }
                
                // Si hace match, la muestro; si no, la escondo
                fila.style.display = mostrar ? '' : 'none';
            }
        }
    </script>
</body>
</html>
