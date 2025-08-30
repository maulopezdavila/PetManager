<?php
session_start();

// Si ya está logueado lo manda al index
if (!empty($_SESSION['auth']['logueado'])) {
    header('Location: index.php');
    exit;
}

// Muestra un mensaje de error si falla algo
$error = $_GET['error'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Manager - Iniciar Sesión</title>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="estilos.css">
</head>
<body class="cuerpo-login">
    <div class="contenedor-login">
        <div class="formulario-login">
            <header class="encabezado-login">
                <div class="logo-login">
                    <span class="material-symbols-outlined">pets</span>
                    <h1>Pet Manager</h1>
                </div>
            </header>

            <main class="contenido-login">
                <div class="bienvenida-login">
                    <h2>Bienvenido de nuevo</h2>
                    <p>Inicia sesión para continuar</p>
                </div>

                <!-- Si hay error al loguearse lo muestro aquí --> 
                <?php if ($error): ?>
                    <div class="mensaje-error">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Formulario de inicio de sesión --> 
                <form action="procesar/procesar_login.php" method="POST" class="formulario" id="formLogin">

                    <!-- Campo usuario --> 
                    <div class="campo-entrada">
                        <span class="material-symbols-outlined">person</span>
                        <input type="text" name="usuario" placeholder="Nombre de usuario" required autocomplete="username">
                    </div>

                    <!-- Campo contraseña --> 
                    <div class="campo-entrada">
                        <span class="material-symbols-outlined">lock</span>
                        <input type="password" name="password" placeholder="Contraseña" required autocomplete="current-password">
                    </div>

                    <!-- Botón para entrar --> 
                    <button type="submit" class="boton-login">Iniciar Sesión</button>
                </form>
                
                <!-- Enlace para registrarse si no tiene cuenta --> 
                <p class="enlace-registro">
                    ¿No tienes una cuenta?
                    <a href="registro_usuario.php">Regístrate</a>
                </p>
            </main>
        </div>
    </div>

    <script src="scripts.js"></script>
</body>
</html>
