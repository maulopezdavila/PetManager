<?php
require_once '../models/Mascota.php';
require_once '../models/Dueno.php';
require_once '../models/VisitaMedica.php';

session_start();

// Verificar que esté logueado
if (empty($_SESSION['auth']['logueado'])) {
    header('Location: ../iniciar_sesion.php');
    exit;
}

// Obtener el nombre que escribió el usuario
$nombre = trim($_GET['nombre'] ?? '');

// Verificación que el usuario no haya dejado la búsqueda vacía 
if (trim($nombre) === '') {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Buscar Mascota - Pet Manager</title>
        <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;700;900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
        <link rel="stylesheet" href="../estilos.css">
    </head>
    <body>
        <div class="contenedor-principal">
            <div class="mensaje-error-busqueda">
                <span class="material-symbols-outlined">warning</span>
                <h1>Campo vacío</h1>
                <p>Por favor ingresa el nombre de una mascota</p>
                <a href="../index.php" class="boton-principal">← Volver al inicio</a>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Sanitizar búsqueda
$nombre = strip_tags($nombre);
$nombre = preg_replace('/[\x00-\x1F\x7F]/u', '', $nombre);
if (mb_strlen($nombre) > 50) {
    die("Error: No se admiten tantos caracteres. <a href='../index.php'>Volver</a>");
}
?>

 Formulario en si 
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Mascota - Pet Manager</title>
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

        <main class="contenido-historial">
            <?php
            // Verificar si existe una mascota con el nombre ingresado
            if (isset($_SESSION['mascotas'][$nombre])) {
                $mascota = $_SESSION['mascotas'][$nombre];
            ?>
                <div class="encabezado-historial">
                    <span class="material-symbols-outlined icono-mascota">pets</span>
                    <h1>Historial de <?php echo htmlspecialchars($mascota->getNombre()); ?></h1>
                    <p>Información completa</p>
                </div>
                
                <!-- Encabezado de la mascota --> 
                <div class="tarjetas-info">
                    <div class="tarjeta-info">
                        <h2>
                            <span class="material-symbols-outlined">info</span>
                            Información General
                        </h2>
                        <div class="contenido-info">
                            <div class="dato-info">
                                <strong>Nombre:</strong> <?php echo htmlspecialchars($mascota->getNombre()); ?>
                            </div>
                            <div class="dato-info">
                                <strong>Especie:</strong> <?php echo htmlspecialchars($mascota->getEspecie()); ?>
                            </div>
                            <?php if ($mascota->getRaza()): ?>
                            <div class="dato-info">
                                <strong>Raza:</strong> <?php echo htmlspecialchars($mascota->getRaza()); ?>
                            </div>
                            <?php endif; ?>
                            <?php if ($mascota->getColor()): ?>
                            <div class="dato-info">
                                <strong>Color:</strong> <?php echo htmlspecialchars($mascota->getColor()); ?>
                            </div>
                            <?php endif; ?>
                            <?php if ($mascota->getFechaNacimiento()): ?>
                            <div class="dato-info">
                                <strong>Fecha de Nacimiento:</strong> <?php echo htmlspecialchars($mascota->getFechaNacimiento()); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Info de los dueños -->     
                    <div class="tarjeta-info">
                        <h2>
                            <span class="material-symbols-outlined">person</span>
                            Dueños
                        </h2>
                        <div class="contenido-info">
                            <?php
                            $duenos = $mascota->getDuenos();
                            
                            if (!empty($duenos)) {
                                foreach ($duenos as $dueno) {
                                    echo "<div class='info-dueno'>";
                                    echo "<strong>Nombre:</strong> " . htmlspecialchars($dueno->getNombre()) . "<br>";
                                    echo "<strong>Teléfono:</strong> " . htmlspecialchars($dueno->getTelefono());
                                    if ($dueno->getEmail()) {
                                        echo "<br><strong>Email:</strong> " . htmlspecialchars($dueno->getEmail());
                                    }
                                    if ($dueno->getDireccion()) {
                                        echo "<br><strong>Dirección:</strong> " . htmlspecialchars($dueno->getDireccion());
                                    }
                                    echo "</div>";
                                }
                            } else {
                                echo "<p class='sin-datos'>Sin dueños registrados</p>";
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Info de las visitas médicas --> 
                    <div class="tarjeta-info tarjeta-completa">
                        <h2>
                            <span class="material-symbols-outlined">medical_services</span>
                            Visitas Médicas
                        </h2>
                        <div class="contenido-info">
                            <?php
                            $visitas = $mascota->getVisitas();
                            
                            if (!empty($visitas)) {
                                foreach ($visitas as $visita) {
                                    echo "<div class='info-visita'>";
                                    echo "<div class='fecha-visita'>" . htmlspecialchars($visita->fecha) . "</div>";
                                    if ($visita->getMotivo()) {
                                        echo "<div class='motivo-visita'><strong>Motivo:</strong> " . htmlspecialchars($visita->getMotivo()) . "</div>";
                                    }
                                    echo "<div class='diagnostico-visita'><strong>Diagnóstico:</strong> " . htmlspecialchars($visita->diagnostico) . "</div>";
                                    echo "<div class='tratamiento-visita'><strong>Tratamiento:</strong> " . htmlspecialchars($visita->tratamiento) . "</div>";
                                    echo "</div>";
                                }
                            } else {
                                echo "<p class='sin-datos'>No hay visitas registradas para esta mascota.</p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>

            <?php
            } else {
                // Si la mascota no existe, aviso con este mensajito
                echo "<div class='mensaje-no-encontrado'>";
                echo "<span class='material-symbols-outlined'>search_off</span>";
                echo "<h1>Mascota no encontrada</h1>";
                echo "<p>Esta mascota no existe en nuestros registros.</p>";
                echo "</div>";
            }
            ?>
            
            <!-- Botón para volver al inicio --> 
            <div class="acciones-historial">
                <a href="../index.php" class="boton-principal">← Volver al inicio</a>
            </div>
        </main>
    </div>

    <script src="../scripts.js"></script>
</body>
</html>

<!-- estilos para la busqueda --> 
<style>
.contenido-historial {
    flex: 1;
    padding: 2rem 1.5rem;
    max-width: 80rem;
    margin: 0 auto;
}

.encabezado-historial {
    text-align: center;
    margin-bottom: 2rem;
}

.icono-mascota {
    font-size: 4rem;
    color: var(--color-primario);
    display: block;
    margin-bottom: 1rem;
}

.encabezado-historial h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.encabezado-historial p {
    color: var(--color-texto-secundario);
    font-size: 1.125rem;
}

.tarjetas-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.tarjeta-completa {
    grid-column: 1 / -1;
}

.tarjeta-info {
    background-color: var(--color-superficie);
    border-radius: 1rem;
    padding: 1.5rem;
}

.tarjeta-info h2 {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: var(--color-texto);
}

.contenido-info {
    space-y: 1rem;
}

.dato-info,
.info-dueno,
.info-visita {
    background-color: var(--color-fondo);
    padding: 1
    border-radius: 0.5rem;
    margin-bottom: 1rem;
}

.info-visita {
    border-left: 4px solid var(--color-primario);
}

.fecha-visita {
    font-weight: 700;
    color: var(--color-primario);
    margin-bottom: 0.5rem;
}

.sin-datos {
    color: var(--color-texto-secundario);
    font-style: italic;
    text-align: center;
    padding: 2rem;
}

.mensaje-no-encontrado,
.mensaje-error-busqueda {
    text-align: center;
    background-color: var(--color-superficie);
    border-radius: 1rem;
    padding: 3rem;
    margin: 2rem auto;
    max-width: 30rem;
}

.mensaje-no-encontrado .material-symbols-outlined,
.mensaje-error-busqueda .material-symbols-outlined {
    font-size: 4rem;
    color: var(--color-texto-secundario);
    display: block;
    margin-bottom: 1rem;
}

.mensaje-no-encontrado h1,
.mensaje-error-busqueda h1 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.mensaje-no-encontrado p,
.mensaje-error-busqueda p {
    color: var(--color-texto-secundario);
    margin-bottom: 2rem;
}

.acciones-historial {
    text-align: center;
}
</style>
