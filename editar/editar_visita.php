<?php
require_once '../models/VisitaMedica.php';
require_once '../models/Mascota.php';

session_start();

if (empty($_SESSION['auth']['logueado'])) {
    header('Location: ../iniciar_sesion.php');
    exit;
}

// Agarro el nombre de la mascota y la fecha de la visita desde la UR
$nombreMascota = trim($_GET['mascota'] ?? '');
$fecha = trim($_GET['fecha'] ?? '');

// Si falta el nombre, la fecha o la mascota no existe, lo mando al listado de visitas
if ($nombreMascota === '' || $fecha === '' || !isset($_SESSION['mascotas'][$nombreMascota])) {
    header('Location: ../listas/listar_visitas.php');
    exit;
}

// Aquí obtengo la mascota y luego busco la visita exacta con esa fecha
$mascota = $_SESSION['mascotas'][$nombreMascota];
$visita = null;
$indexVisita = -1;

// Recorro todas las visitas de la mascota para ver cuál tiene la fecha que me pasaron
foreach ($mascota->getVisitas() as $i => $v) {
    if ($v->getFecha() === $fecha) {
        $visita = $v;
        $indexVisita = $i; // guardo el índice por si hace falta
        break;
    }
}

// Si no encontré la visita, lo regreso al listado
if (!$visita) {
    header('Location: ../listas/listar_visitas.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Visita - Pet Manager</title>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="../estilos.css">
</head>
<body>
    <div class="contenedor-principal">

        <!-- Encabezado con logo, menú y usuario --> 
        <header class="encabezado">
            <div class="logo-titulo">
                <span class="material-symbols-outlined icono-principal">pets</span>
                <h1>Pet Manager</h1>
            </div>
            <nav class="navegacion">
                <a href="../index.php" class="enlace-nav">Inicio</a>
                <a href="../listas/listar_mascotas.php" class="enlace-nav">Mascotas</a>
                <a href="../listas/listar_duenos.php" class="enlace-nav">Dueños</a>
                <a href="../listas/listar_visitas.php" class="enlace-nav activo">Visitas</a>
            </nav>
            <div class="acciones-usuario">
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

        <main class="contenido-formulario">
            <div class="encabezado-pagina">
                <h2>Editar Visita Médica</h2>
                <p>Modifica los datos de la visita</p>
            </div>

            <!-- Formulario para editar la visita --> 
            <form action="../procesar/procesar_editar_visita.php" method="POST" class="formulario-principal" id="formVisita">
                <input type="hidden" name="mascota" value="<?php echo htmlspecialchars($nombreMascota); ?>">
                <input type="hidden" name="fecha_original" value="<?php echo htmlspecialchars($fecha); ?>">
                <div class="campos-grid">

                    <!-- Fecha de la visita --> 
                    <div class="campo-formulario">
                        <label for="fecha">Fecha</label>
                        <input type="date" id="fecha" name="fecha" value="<?php echo htmlspecialchars($visita->getFecha()); ?>" required>
                    </div>

                    <!-- Mascota asociada (puedes cambiarla si quieres) --> 
                    <div class="campo-formulario">
                        <label for="mascota_select">Mascota</label>
                        <select id="mascota_select" name="mascota_select" required>
                            <!-- La mascota actual sale seleccionada --> 
                            <option value="<?php echo htmlspecialchars($nombreMascota); ?>" selected><?php echo htmlspecialchars($mascota->getNombre()) . " (" . htmlspecialchars($mascota->getEspecie()) . ")"; ?></option>
                            <?php
                            // Aquí muestro las demás mascotas para poder reasignar la visita si se quiere
                            foreach ($_SESSION['mascotas'] as $nm => $m) {
                                if ($nm !== $nombreMascota) {
                                    echo "<option value='" . htmlspecialchars($nm) . "'>" . htmlspecialchars($m->getNombre()) . " (" . htmlspecialchars($m->getEspecie()) . ")</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                
                <!-- Motivo de la visita --> 
                <div class="campo-formulario">
                    <label for="motivo">Motivo de la visita</label>
                    <input type="text" id="motivo" name="motivo" value="<?php echo htmlspecialchars($visita->getMotivo() ?? ''); ?>" placeholder="Ej. Revisión anual, vacunación">
                </div>
                
                <!-- Diagnóstico --> 
                <div class="campo-formulario">
                    <label for="diagnostico">Diagnóstico</label>
                    <textarea id="diagnostico" name="diagnostico" placeholder="Describe el diagnóstico..." required><?php echo htmlspecialchars($visita->getDiagnostico()); ?></textarea>
                </div>
                
                <!-- Tratamiento --> 
                <div class="campo-formulario">
                    <label for="tratamiento">Tratamiento</label>
                    <textarea id="tratamiento" name="tratamiento" placeholder="Describe el tratamiento..." required><?php echo htmlspecialchars($visita->getTratamiento()); ?></textarea>
                </div>
                
                <!-- Botones de acción --> 
                <div class="botones-formulario">
                    <a href="../listas/listar_visitas.php" class="boton-secundario">Cancelar</a>
                    <button type="submit" class="boton-principal">Guardar Cambios</button>
                </div>
            </form>
        </main>
    </div>

    <script src="../scripts.js"></script>
</body>
</html>
