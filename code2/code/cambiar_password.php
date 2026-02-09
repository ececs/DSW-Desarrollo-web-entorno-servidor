<?php
// Cambiar contraseña
require_once 'includes/config.php';
require_once 'includes/funciones.php';
require_once 'includes/validaciones.php';

verificarSesion();

$dni = $_SESSION['usuario'];
$mensaje = '';
$error = '';

// procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $passwordActual = $_POST['password_actual'] ?? '';
    $passwordNueva = $_POST['password_nueva'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';
    
    // validaciones basicas
    if (empty($passwordActual) || empty($passwordNueva) || empty($passwordConfirm)) {
        $error = 'Todos los campos son obligatorios';
    } elseif ($passwordNueva !== $passwordConfirm) {
        $error = 'Las contraseñas nuevas no coinciden';
    } elseif (!validarPassword($passwordNueva)) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
    } else {
        // verificar password actual
        $usuario = autenticarUsuario($dni, $passwordActual);
        
        if (!$usuario) {
            $error = 'La contraseña actual es incorrecta';
        } else {
            // cambiar  password
            $vecinos = leerVecinos();
            $encontrado = false;
            
            foreach ($vecinos as &$vecino) {
                if ($vecino['dni'] === $dni) {
                    $vecino['password'] = password_hash($passwordNueva, PASSWORD_DEFAULT);
                    $encontrado = true;
                    break;
                }
            }
            
            if ($encontrado && guardarVecinos($vecinos)) {
                $mensaje = 'Contraseña cambiada correctamente';
                $_POST = array();
            } else {
                $error = 'Error al cambiar la contraseña';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña - Gestión Comunidad</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <div class="contenedor-principal">
        <header class="cabecera">
            <h1>Gestión de Comunidad de Vecinos</h1>
            <div class="info-usuario">
                <p><?php echo mensajeBienvenida($_SESSION['nombre'], $_SESSION['rol']); ?></p>
                <nav class="menu-principal">
                    <a href="dashboard.php">Inicio</a>
                    <a href="cambiar_password.php" class="activo">Cambiar Contraseña</a>
                    <a href="logout.php" class="boton-salir">Cerrar Sesión</a>
                </nav>
            </div>
        </header>
        
        <main class="contenido-principal">
            <section class="seccion-datos">
                <h2>Cambiar Contraseña</h2>
                
                <?php if ($error): ?>
                    <div class="mensaje-error">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($mensaje): ?>
                    <div class="mensaje-exito">
                        <?php echo htmlspecialchars($mensaje); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="campo-formulario">
                        <label for="password_actual">Contraseña Actual:</label>
                        <input type="password" 
                               id="password_actual" 
                               name="password_actual" 
                               required>
                    </div>
                    
                    <div class="campo-formulario">
                        <label for="password_nueva">Nueva Contraseña:</label>
                        <input type="password" 
                               id="password_nueva" 
                               name="password_nueva" 
                               minlength="6"
                               required>
                        <small>Mínimo 6 caracteres</small>
                    </div>
                    
                    <div class="campo-formulario">
                        <label for="password_confirm">Confirmar Nueva Contraseña:</label>
                        <input type="password" 
                               id="password_confirm" 
                               name="password_confirm" 
                               minlength="6"
                               required>
                    </div>
                    
                    <button type="submit" class="boton-primario">Cambiar Contraseña</button>
                </form>
            </section>
        </main>
        
        <footer class="pie-pagina">
            <p>&copy; 2025 Sistema de Gestión de Comunidad de Vecinos</p>
        </footer>
    </div>
</body>
</html>