<?php
require_once '../models/Mascota.php';
require_once '../models/Dueno.php';

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
    <title>Registrar Mascota - Pet Manager</title>
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
                <a href="../listas/listar_mascotas.php" class="enlace-nav">Mascotas</a>
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

        <!-- Contenido principal --> 
        <main class="contenido-formulario">
            <div class="encabezado-pagina">
                <h2>Registrar Nueva Mascota</h2>
                <p>Completa el formulario para agregar una mascota</p>
            </div>

            <!-- Formulario para crear una mascota --> 
            <form action="../procesar/procesar_mascota.php" method="POST" class="formulario-principal" id="formMascota">
                <div class="campos-grid">
                    <div class="campo-formulario">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" placeholder="Ingresa el nombre de la mascota" required>
                        <div class="error-campo" id="errorNombre"></div>
                    </div>

                    <div class="campo-formulario">
                        <label for="especie">Especie</label>
                        <select id="especie" name="especie" required>
                            <option value="" disabled selected>Selecciona la especie</option>
                            <option value="Perro">Perro</option>
                            <option value="Gato">Gato</option>
                            <option value="Ave">Ave</option>
                            <option value="Conejo">Conejo</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>

                    <div class="campo-formulario">
                        <label for="raza">Raza</label>
                        <input type="text" id="raza" name="raza" placeholder="Ej. Golden Retriever">
                    </div>

                    <div class="campo-formulario">
                        <label for="fechaNacimiento">Fecha de Nacimiento</label>
                        <input type="date" id="fechaNacimiento" name="fecha_nacimiento">
                    </div>
                </div>

                <div class="campo-formulario">
                    <label for="color">Descripción de la mascota</label>
                    <input type="text" id="color" name="color" placeholder="Describe de manera general la mascota">
                </div>


                <div class="campo-formulario">
                    <label for="duenos">Dueños asociados (opcional)</label>
                    <select id="duenos" name="duenos[]" multiple>
                        <option value="" disabled>Selecciona uno o más dueños</option>
                        <?php
                        //Para poder asociar dueños
                        if (isset($_SESSION['duenos']) && !empty($_SESSION['duenos'])) {
                            foreach ($_SESSION['duenos'] as $index => $dueno) {
                                echo "<option value='" . htmlspecialchars($index) . "'>" . htmlspecialchars($dueno->getNombre()) . " (" . htmlspecialchars($dueno->getTelefono()) . ")</option>";
                            }
                        } else {
                            echo "<option value='' disabled>No hay dueños registrados aún</option>";
                        }
                        ?>
                    </select>
                    <p class="nota-campo">Mantén presionada la tecla Ctrl para seleccionar múltiples dueños.</p>
                </div>

                <!-- Botones pa' enviar o cancelar --> 
                <div class="botones-formulario">
                    <a href="../index.php" class="boton-secundario">Cancelar</a>
                    <button type="submit" class="boton-principal">Registrar Mascota</button>
                </div>
            </form>
        </main>
    </div>

    <script src="../scripts.js"></script>
</body>
</html>
