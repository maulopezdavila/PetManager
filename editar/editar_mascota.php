<?php
require_once '../models/Mascota.php';
require_once '../models/Dueno.php';

session_start();

if (empty($_SESSION['auth']['logueado'])) {
    header('Location: ../iniciar_sesion.php');
    exit;
}

// Agarro el nombre de la mascota desde la URL, si no viene, lo dejo vacío
$nombre = trim($_GET['nombre'] ?? '');

// Si el nombre está vacío o esa mascota no existe en sesión, lo regreso al listado
if ($nombre === '' || !isset($_SESSION['mascotas'][$nombre])) {
    header('Location: ../listas/listar_mascotas.php');
    exit;
}

$mascota = $_SESSION['mascotas'][$nombre];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Mascota - Pet Manager</title>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="../estilos.css">
</head>
<body>
    <div class="contenedor-principal">

        <!-- Header con logo, menú y usuario --> 
        <header class="encabezado">
            <div class="logo-titulo">
                <span class="material-symbols-outlined icono-principal">pets</span>
                <h1>Pet Manager</h1>
            </div>
            <nav class="navegacion">
                <a href="../index.php" class="enlace-nav">Inicio</a>
                <a href="../listas/listar_mascotas.php" class="enlace-nav activo">Mascotas</a>
                <a href="../listas/listar_duenos.php" class="enlace-nav">Dueños</a>
                <a href="../listas/listar_visitas.php" class="enlace-nav">Visitas</a>
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
                <h2>Editar Mascota</h2>
                <p>Modifica los datos de la mascota</p>
            </div>

            <!-- Formulario para editar la mascota --> 
            <form action="../procesar/procesar_editar_mascota.php" method="POST" class="formulario-principal" id="formMascota">
                <input type="hidden" name="nombre_original" value="<?php echo htmlspecialchars($nombre); ?>">
                <div class="campos-grid">

                    <!-- Nombre de la mascota --> 
                    <div class="campo-formulario">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" 
                        value="<?php echo htmlspecialchars($mascota->getNombre()); ?>" placeholder="Ingresa el nombre de la mascota" required>
                        <div class="error-campo" id="errorNombre"></div>
                    </div>

                    <!-- Especie (Perro, Gato, etc.) --> 
                    <div class="campo-formulario">
                        <label for="especie">Especie</label>
                        <select id="especie" name="especie" required>
                            <option value="Perro" <?php echo $mascota->getEspecie() === 'Perro' ? 'selected' : ''; ?>>Perro</option>
                            <option value="Gato" <?php echo $mascota->getEspecie() === 'Gato' ? 'selected' : ''; ?>>Gato</option>
                            <option value="Ave" <?php echo $mascota->getEspecie() === 'Ave' ? 'selected' : ''; ?>>Ave</option>
                            <option value="Conejo" <?php echo $mascota->getEspecie() === 'Conejo' ? 'selected' : ''; ?>>Conejo</option>
                            <option value="Otro" <?php echo $mascota->getEspecie() === 'Otro' ? 'selected' : ''; ?>>Otro</option>
                        </select>
                    </div>

                    <!-- Raza (si aplica) --> 
                    <div class="campo-formulario">
                        <label for="raza">Raza</label>
                        <input type="text" id="raza" name="raza" value="<?php echo htmlspecialchars($mascota->getRaza() ?? ''); ?>" placeholder="Ej. Golden Retriever">
                    </div>

                    <!-- Fecha de nacimiento --> 
                    <div class="campo-formulario">
                        <label for="fechaNacimiento">Fecha de Nacimiento</label>
                        <input type="date" id="fechaNacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($mascota->getFechaNacimiento() ?? ''); ?>">
                    </div>
                </div>

                <!-- Descripción general de la mascota --> 
                <div class="campo-formulario">
                    <label for="color">Descripción de la mascota</label>
                    <input type="text" id="color" name="color" value="<?php echo htmlspecialchars($mascota->getColor() ?? ''); ?>" placeholder="Describe de manera general la mascota">
                </div>

                <!-- Dueños que pueden estar asociados --> 
                <div class="campo-formulario">
                    <label for="duenos">Dueños asociados (opcional)</label>
                    <select id="duenos" name="duenos[]" multiple>
                        <option value="" disabled>Selecciona uno o más dueños</option>
                        <?php
                        // Si hay dueños, los muestro, si no, aviso
                        if (isset($_SESSION['duenos']) && !empty($_SESSION['duenos'])) {
                            foreach ($_SESSION['duenos'] as $index => $dueno) {
                                $selected = '';
                                foreach ($mascota->getDuenos() as $duenoMascota) {
                                    if ($duenoMascota->getNombre() === $dueno->getNombre() && $duenoMascota->getTelefono() === $dueno->getTelefono()) {
                                        $selected = 'selected';
                                        break;
                                    }
                                }
                                echo "<option value='" . htmlspecialchars($index) . "' $selected>" . htmlspecialchars($dueno->getNombre()) . " (" . htmlspecialchars($dueno->getTelefono()) . ")</option>";
                            }
                        } else {
                            echo "<option value='' disabled>No hay dueños registrados aún</option>";
                        }
                        ?>
                    </select>
                    <p class="nota-campo">Mantén presionada la tecla Ctrl para seleccionar múltiples dueños.</p>
                </div>
                
                <!-- Botones del form --> 
                <div class="botones-formulario">
                    <a href="../listas/listar_mascotas.php" class="boton-secundario">Cancelar</a>
                    <button type="submit" class="boton-principal">Guardar Cambios</button>
                </div>
            </form>
        </main>
    </div>

    <script src="../scripts.js"></script>
</body>
</html>
