<?php
require_once 'includes/config.php';
require_once 'includes/db.php'; // Incluyo para tener acceso a $pdo
require_once 'includes/funciones.php';

// Si ya hay una sesión activa, redirigir al dashboard
if (isset($_SESSION['usuario'])) {
    header('Location: dashboard.php');
    exit();
}

$error = '';

// Procesar el formulario de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = strtoupper(trim($_POST['dni'] ?? ''));
    $password = $_POST['password'] ?? '';
    
    if (empty($dni) || empty($password)) {
        $error = 'Por favor, complete todos los campos.';
    } else {
        // Paso el objeto $pdo a la función.
        $vecino = autenticarUsuario($pdo, $dni, $password);
        
        if ($vecino) {
            // Iniciar sesión
            $_SESSION['usuario'] = $vecino['dni'];
            $_SESSION['nombre'] = $vecino['nombre'];
            $_SESSION['rol'] = $vecino['rolUsuario'];
            
            header('Location: dashboard.php');
            exit();
        } else {
            $error = 'DNI o contraseña incorrectos.';
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
    <link rel="stylesheet" href="css/estilo.css?v=2.1">
</head>
<body>
    <div class="contenedor-login">
        <div class="formulario-login">
            <h1>Comunidad de Vecinos</h1>
            <br><br>
            <?php if ($error): ?>
                <div class="mensaje-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="campo-formulario">
                    <label for="dni">DNI (Usuario):</label>
                    <input type="text" id="dni" name="dni" maxlength="9" placeholder="12345678A" value="<?php echo htmlspecialchars($_POST['dni'] ?? ''); ?>" required>
                </div>
                <div class="campo-formulario">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="boton-primario">Acceder</button>
            </form>

            <div style="margin-top: 20px; text-align: center;">
                <button id="btn-prueba" class="boton-pequeño">Mostrar Usuarios de Prueba</button>
            </div>

            <div id="usuarios-prueba" class="informacion-sistema" style="display: none; margin-top: 15px;">
                <p><strong>Usuarios de prueba:</strong></p>
                <ul style="list-style: none; padding: 0;">
                    <li><strong>Vecino (Antonio):</strong><br>Usuario: 12345678A<br>Contraseña: pescado123</li>
                    <li style="margin-top:10px;"><strong>Presidente (Enrique):</strong><br>Usuario: 87654321B<br>Contraseña: presidente123</li>
                    <li style="margin-top:10px;"><strong>Admin (Vicente):</strong><br>Usuario: 99887766E<br>Contraseña: admin123</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('btn-prueba').addEventListener('click', function() {
            var panel = document.getElementById('usuarios-prueba');
            if (panel.style.display === 'none') {
                panel.style.display = 'block';
                this.textContent = 'Ocultar Usuarios de Prueba';
            } else {
                panel.style.display = 'none';
                this.textContent = 'Mostrar Usuarios de Prueba';
            }
        });
    </script>
</body>
</html>