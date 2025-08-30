<?php
require_once '../models/Dueno.php';
require_once '../models/Mascota.php';

session_start();

if (empty($_SESSION['auth']['logueado'])) {
    header('Location: ../iniciar_sesion.php');
    exit;
}

// Agarro el index que viene por GET, si no existe lo pongo en -1
$index = intval($_GET['index'] ?? -1);

// Si el index no existe o es malo, lo mando de vuelta a listar dueños
if ($index < 0 || !isset($_SESSION['duenos'][$index])) {
    header('Location: ../listas/listar_duenos.php');
    exit;
}

// Aquí agarro el dueño que quiero editar según el index
$dueno = $_SESSION['duenos'][$index];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Dueño - Pet Manager</title>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="../estilos.css">
</head>
<body>
    <div class="contenedor-principal">
        <!-- Encabezado con menú y usuario --> 
        <header class="encabezado">
            <div class="logo-titulo">
                <span class="material-symbols-outlined icono-principal">pets</span>
                <h1>Pet Manager</h1>
            </div>
            <nav class="navegacion">
                <a href="../index.php" class="enlace-nav">Inicio</a>
                <a href="../listas/listar_mascotas.php" class="enlace-nav">Mascotas</a>
                <a href="../listas/listar_duenos.php" class="enlace-nav activo">Dueños</a>
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
                <h2>Editar Dueño</h2>
                <p>Modifica los datos del dueño</p>
            </div>

            <!-- Formulario para editar dueño --> 
            <form action="../procesar/procesar_editar_dueno.php" method="POST" class="formulario-principal" id="formDueno">
                <input type="hidden" name="index" value="<?php echo $index; ?>">
                <div class="campos-grid">

                    <!-- Campo de nombre --> 
                    <div class="campo-formulario">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($dueno->getNombre()); ?>" placeholder="Ingresa el nombre" required>
                        <div class="error-campo" id="errorNombre"></div>
                    </div>

                    <!-- Campo de teléfono --> 
                    <div class="campo-formulario">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($dueno->getTelefono()); ?>" placeholder="Ingresa el número de teléfono" required>
                        <div class="error-campo" id="errorTelefono"></div>
                    </div>
                </div>

                <!-- Campo de email --> 
                <div class="campo-formulario">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($dueno->getEmail() ?? ''); ?>" placeholder="Ingresa el correo electrónico">
                </div>

                <!-- Campo de dirección --> 
                <div class="campo-formulario">
                    <label for="direccion">Dirección</label>
                    <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($dueno->getDireccion() ?? ''); ?>" placeholder="Ingresa la dirección">
                </div>

                <!-- Selector de mascota --> 
                <div class="campo-formulario">
                    <label for="mascota">Mascota asociada</label>
                    <select id="mascota" name="mascota">
                        <option value="">Selecciona una mascota existente (opcional)</option>
                        <?php
                        // Aquí cargo las mascotas que ya existen para relacionarlas con el dueño
                        if (isset($_SESSION['mascotas'])) {
                            foreach ($_SESSION['mascotas'] as $nombreMascota => $mascota) {
                                echo "<option value='" . htmlspecialchars($nombreMascota) . "'>" . htmlspecialchars($mascota->getNombre()) . " (" . htmlspecialchars($mascota->getEspecie()) . ")</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                
                <!-- Botones de acción --> 
                <div class="botones-formulario">
                    <a href="../listas/listar_duenos.php" class="boton-secundario">Cancelar</a>
                    <button type="submit" class="boton-principal">Guardar Cambios</button>
                </div>
            </form>
        </main>
    </div>

    <script src="../scripts.js"></script>
</body>
</html>
