<?php
session_start();

// Inicializar array de contactos si no existe
if (!isset($_SESSION['contactos'])) {
    $_SESSION['contactos'] = array();
}

$mensaje = '';
$tipo_mensaje = '';

// Procesar formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    
    // Validación en servidor
    if (empty($nombre) || empty($email)) {
        $mensaje = 'Por favor completa todos los campos';
        $tipo_mensaje = 'mal';
    } 
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = 'El formato del email no es válido';
        $tipo_mensaje = 'mal';
    } 
    else {
        // Verificar duplicados
        $duplicado = false;
        foreach ($_SESSION['contactos'] as $contacto) {
            if (strtolower($contacto['email']) == strtolower($email)) {
                $duplicado = true;
                break;
            }
        }
        
        if ($duplicado) {
            $mensaje = 'Este email ya está registrado en la lista';
            $tipo_mensaje = 'mal';
        } else {
            // Guardar contacto
            $_SESSION['contactos'][] = array(
                'nombre' => htmlspecialchars($nombre),
                'email' => htmlspecialchars($email),
                'fecha' => date('Y-m-d H:i:s')
            );
            
            // Redirigir a la lista
            header('Location: lista.php');
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Contacto</title>
    <link rel="stylesheet" href="estilos.css">
    <script>
        // Solo JavaScript para validación básica del @
        function validarEmail() {
            var email = document.getElementById('email').value;
            var error = document.getElementById('errorMail');
            
            if (email && email.indexOf('@') == -1) {
                error.style.display = 'block';
                return false;
            }
            error.style.display = 'none';
            return true;
        }
    </script>
</head>
<body>
    <div class="contenedor">
        <h1>Añadir nuevo contacto</h1>
        
        <?php if ($mensaje): ?>
            <div class="aviso-<?php echo $tipo_mensaje; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>
        
        <p>Rellena los siguientes campos para darte de alta en la lista:</p>
        
        <form method="POST" action="registro.php">
            <div class="campo">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" placeholder="Escribe tu nombre completo" required>
            </div>
            
            <div class="campo">
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" placeholder="tu.email@ejemplo.com" 
                       onblur="validarEmail()" required>
                <span class="mensaje-error" id="errorMail">El email debe contener @</span>
            </div>
            
            <button type="submit" class="boton">Guardar contacto</button>
            <a href="index.php" class="boton boton-gris">Cancelar</a>
        </form>
    </div>
</body>
</html>