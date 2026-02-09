<?php
/**
 * Gestiona la autenticación de usuarios.
 * Lee los datos de los usuarios desde 'vecinos.dat', verifica las credenciales
 * y establece una sesión si el login es exitoso.
 */
session_start();

// Ruta del archivo de datos
define('DATA_FILE', 'vecinos.dat');

/**
 * Lee y deserializa los datos del archivo vecinos.dat.
 * Si el archivo no existe, lo inicializa con un administrador por defecto.
 * @return array El array de usuarios.
 */
function leer_datos_usuarios() {
    if (!file_exists(DATA_FILE)) {
        // Administrador por defecto si no se encuentra el archivo de datos
        $admin_user = [
            'nombre' => 'Administrador',
            'rol' => 'administrador'
        ];
        // En una aplicación real, las contraseñas deberían estar hasheadas.
        $usuarios = ['admin' => array_merge($admin_user, ['clave' => 'admin123'])];
        file_put_contents(DATA_FILE, serialize($usuarios));
        return $usuarios;
    }
    return unserialize(file_get_contents(DATA_FILE));
}

/**
 * Comprueba si el nombre de usuario y la contraseña proporcionados son válidos.
 * @param string $nombre El nombre de usuario a comprobar.
 * @param string $clave La contraseña a comprobar.
 * @return array|false El array de datos del usuario si es válido, si no, false.
 */
function comprobar_usuario($nombre, $clave) {
    $usuarios = leer_datos_usuarios();
    if (isset($usuarios[$nombre]) && $usuarios[$nombre]['clave'] === $clave) {
        return $usuarios[$nombre];
    }
    return false;
}

// Procesa el envío del formulario de login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_data = comprobar_usuario($_POST['usuario'], $_POST['clave']);
    
    if ($usuario_data === false) {
        $err = true;
        $usuario = $_POST['usuario'];
    } else {
        $_SESSION['usuario'] = $_POST['usuario'];
        $_SESSION['rol'] = $usuario_data['rol'];
        $_SESSION['nombre'] = $usuario_data['nombre'];
        header("Location: principal.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Formulario de Login</title>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; }
        form { border: 1px solid #ccc; padding: 20px; max-width: 300px; }
        input { display: block; margin-bottom: 10px; width: 95%; }
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Acceso a la Comunidad</h2>
    <?php if (isset($_GET["redirigido"])) {
        echo "<p class='error'>Haga login para continuar.</p>";
    } ?>
    <?php if (isset($err)) {
        echo "<p class='error'>Usuario o contraseña incorrectos.</p>";
    } ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="usuario">Usuario</label>
        <input id="usuario" name="usuario" type="text" value="<?php if(isset($usuario)) echo $usuario; ?>" required>
        
        <label for="clave">Clave</label>
        <input id="clave" name="clave" type="password" required>
        
        <input type="submit" value="Entrar">
    </form>
</body>
</html>