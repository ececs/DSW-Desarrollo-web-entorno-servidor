<?php
// Página de login
require_once 'includes/config.php';
require_once 'includes/funciones.php';
require_once 'includes/validaciones.php';

// si ya tiene sesion manda al dashboard
if (isset($_SESSION['usuario'])) {
    header('Location: dashboard.php');
    exit();
}

$error = '';

// procesar login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = strtoupper(trim($_POST['dni'] ?? ''));
    $password = $_POST['password'] ?? '';
    
    if (empty($dni) || empty($password)) {
        $error = 'Por favor, complete todos los campos';
    } else {
        // verificar que existe el archivo de datos
        if (!file_exists(ARCHIVO_DATOS)) {
            $error = 'Error: El archivo de datos no existe. Ejecute generar_datos.php primero.';
        } else {
            $vecino = buscarVecinoPorDni($dni);
            
            if (!$vecino) {
                $error = 'DNI no encontrado en el sistema';
            } else {
                // verificar password
                if (password_verify($password, $vecino['password'])) {
                    // crear sesion
                    $_SESSION['usuario'] = $dni;
                    $_SESSION['nombre'] = $vecino['nombre'];
                    $_SESSION['rol'] = $vecino['rol'];
                    
                    header('Location: dashboard.php');
                    exit();
                } else {
                    $error = 'Contraseña incorrecta';
                }
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
    <title>Login - Comunidad de Vecinos</title>
    <link rel="stylesheet" href="css/estilo.css?v=2.0">
</head>
<body>
    <div class="contenedor-login">
        <div class="formulario-login">
            <h1>Comunidad de Vecinos</h1>
            <br><br>
            <?php if ($error): ?>
                <div class="mensaje-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!file_exists(ARCHIVO_DATOS)): ?>
                <div class="mensaje-info">
                    <strong>Atención:</strong> No hay archivo de datos.<br>
                    Ejecute <a href="generar_datos.php">generar_datos.php</a> primero.
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="campo-formulario">
                    <label for="dni">DNI:</label>
                    <input type="text" 
                           id="dni" 
                           name="dni" 
                           maxlength="9" 
                           placeholder="12345678A"
                           value="<?php echo htmlspecialchars($_POST['dni'] ?? ''); ?>"
                           required>
                </div>
                
                <div class="campo-formulario">
                    <label for="password">Contraseña:</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required>
                </div>
                
                <button type="submit" class="boton-primario">Acceder</button>
            </form>
            
            <!-- usuarios de prueba para testing -->
            <div class="informacion-sistema">
                <p><strong>Usuarios de prueba:</strong></p>
                <ul style="list-style: none; padding: 0;">
                    <li><strong>Antonio Recio:</strong> 12345678A / pescado123</li>
                    <li><strong>Enrique (Presidente):</strong> 87654321B / presidente123</li>
                    <li><strong>Vicente (Admin):</strong> 99887766E / admin123</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>