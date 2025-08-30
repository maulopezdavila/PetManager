<?php
require_once '../models/VisitaMedica.php';
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
    <title>Registrar Visita - Pet Manager</title>
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

        <!-- Contenido principal --> 
        <main class="contenido-formulario">
            <div class="encabezado-pagina">
                <h2>Nueva Visita Médica</h2>
                <p>Completa el formulario para registrar una nueva visita médica</p>
            </div>

            <!-- Formulario para registrar la visita médica --> 
            <form action="../procesar/procesar_visita.php" method="POST" class="formulario-principal" id="formVisita">
                <div class="campos-grid">
                    <div class="campo-formulario">
                        <label for="fecha">Fecha</label>
                        <input type="date" id="fecha" name="fecha" required>
                    </div>

                    <div class="campo-formulario">
                        <label for="mascota">Mascota</label>
                        <select id="mascota" name="mascota" required>
                            <option value="" disabled selected>Selecciona una mascota</option>
                            <?php
                            // Si tenemos mascotas en sesión, las listamos en el select
                            if (isset($_SESSION['mascotas'])) {
                                foreach ($_SESSION['mascotas'] as $nombreMascota => $mascota) {
                                    echo "<option value='" . htmlspecialchars($nombreMascota) . "'>" . htmlspecialchars($mascota->getNombre()) . " (" . htmlspecialchars($mascota->getEspecie()) . ")</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="campo-formulario">
                    <label for="motivo">Motivo de la visita</label>
                    <input type="text" id="motivo" name="motivo" placeholder="Ej. Revisión anual, vacunación">
                </div>

                <div class="campo-formulario">
                    <label for="diagnostico">Diagnóstico</label>
                    <textarea id="diagnostico" name="diagnostico" placeholder="Describe el diagnóstico..." required></textarea>
                </div>

                <div class="campo-formulario">
                    <label for="tratamiento">Tratamiento</label>
                    <textarea id="tratamiento" name="tratamiento" placeholder="Describe el tratamiento..." required></textarea>
                </div>
                
                <!-- Botones para cancelar o guardar --> 
                <div class="botones-formulario">
                    <a href="../index.php" class="boton-secundario">Cancelar</a>
                    <button type="submit" class="boton-principal">Guardar Visita</button>
                </div>
            </form>
        </main>
    </div>

    <script src="../scripts.js"></script>
</body>
</html>
