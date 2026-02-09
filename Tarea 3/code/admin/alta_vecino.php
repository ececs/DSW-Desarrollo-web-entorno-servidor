<?php
require_once '../includes/config.php';
require_once '../includes/db.php'; // Se incluye para tener acceso a $pdo
require_once '../includes/funciones.php';
require_once '../includes/validaciones.php';

verificarSesion();

if ($_SESSION['rol'] !== ROL_ADMINISTRADOR) {
    header('Location: ../dashboard.php');
    exit();
}

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recojo los datos del formulario
    $nombre = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $dni = strtoupper(trim($_POST['dni'] ?? ''));
    $telefono = trim($_POST['telefono'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $bloque = trim($_POST['bloque'] ?? '');
    $piso = trim($_POST['piso'] ?? '');
    $letra = strtoupper(trim($_POST['letra'] ?? ''));
    $fecha_alta = $_POST['fecha_alta'] ?? date('Y-m-d');
    $fecha_ultima_cuota = $_POST['fecha_ultima_cuota'] ?? date('Y-m-d');
    $password = $_POST['password'] ?? '';
    $rol = $_POST['rol'] ?? ROL_VECINO;

    // Validaciones
    if (empty($nombre)) $errores[] = "El nombre es obligatorio.";
    if (empty($apellidos)) $errores[] = "Los apellidos son obligatorios.";
    if (!validarDNI($dni)) $errores[] = "El DNI no es válido.";
    // Se pasa el objeto $pdo a la función.
    if (buscarVecinoPorDni($pdo, $dni)) $errores[] = "Ya existe un vecino con ese DNI.";
    if (empty($password)) $errores[] = "La contraseña es obligatoria.";
    // Se pasa el objeto $pdo a la función.
    if ($rol === ROL_PRESIDENTE && existePresidente($pdo)) $errores[] = "Ya existe un presidente.";
    if (empty($bloque) || empty($piso) || empty($letra)) $errores[] = "La vivienda completa es obligatoria.";

    if (empty($errores)) {
        $nuevoVecino = [
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'dni' => $dni,
            'telefono' => $telefono,
            'email' => $email,
            'fechaAlta' => $fecha_alta,
            'piso' => $piso,
            'bloque' => $bloque,
            'letra' => $letra,
            'cuotasImpagadas' => 0, // Valor inicial
            'fechaUltimaCuota' => $fecha_ultima_cuota,
            'nombreUsuario' => $dni, // Usamos DNI como nombre de usuario por defecto
            'passUsuario' => password_hash($password, PASSWORD_DEFAULT),
            'rolUsuario' => $rol
        ];

        // Se pasa el objeto $pdo a la función.
        if (crearVecino($pdo, $nuevoVecino)) {
            header('Location: ../dashboard.php?mensaje=alta_exitosa');
            exit();
        } else {
            $errores[] = "Error al guardar el vecino en la base de datos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de Vecino</title>
    <link rel="stylesheet" href="../css/estilo.css?v=2.1">
</head>
<body>
    <div class="contenedor-principal">
        <header class="cabecera">
            <h1>Comunidad de Vecinos</h1>
            <div class="info-usuario">
                <p><?php echo mensajeBienvenida($_SESSION['nombre'], $_SESSION['rol']); ?></p>
                <nav class="menu-principal">
                    <a href="../dashboard.php">Inicio</a>
                    <a href="alta_vecino.php" class="activo">Alta Vecino</a>
                    <a href="../cambiar_password.php">Cambiar Contraseña</a>
                    <a href="../logout.php" class="boton-salir">Cerrar Sesión</a>
                </nav>
            </div>
        </header>
        
        <main class="contenido-principal">
            <section class="seccion-datos">
                <h2>Alta de Nuevo Vecino</h2>
                
                <?php if (!empty($errores)): ?>
                    <div class="mensaje-error">
                        <strong>Errores:</strong>
                        <ul>
                            <?php foreach ($errores as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <h3>Datos Personales</h3>
                    <div class="campo-formulario">
                        <label for="nombre">Nombre: *</label>
                        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>" required>
                    </div>
                    <div class="campo-formulario">
                        <label for="apellidos">Apellidos: *</label>
                        <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($_POST['apellidos'] ?? ''); ?>" required>
                    </div>
                    <div class="campo-formulario">
                        <label for="dni">DNI: *</label>
                        <input type="text" id="dni" name="dni" maxlength="9" value="<?php echo htmlspecialchars($_POST['dni'] ?? ''); ?>" required>
                    </div>
                    <div class="campo-formulario">
                        <label for="telefono">Teléfono:</label>
                        <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($_POST['telefono'] ?? ''); ?>">
                    </div>
                    <div class="campo-formulario">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>
                    
                    <h3>Vivienda</h3>
                    <div class="campo-formulario">
                        <label>Bloque: *</label>
                        <input type="text" name="bloque" value="<?php echo htmlspecialchars($_POST['bloque'] ?? ''); ?>" required>
                    </div>
                    <div class="campo-formulario">
                        <label>Piso: *</label>
                        <input type="text" name="piso" value="<?php echo htmlspecialchars($_POST['piso'] ?? ''); ?>" required>
                    </div>
                    <div class="campo-formulario">
                        <label>Letra: *</label>
                        <input type="text" name="letra" maxlength="2" value="<?php echo htmlspecialchars($_POST['letra'] ?? ''); ?>" required>
                    </div>

                    <h3>Cuotas y Acceso</h3>
                    <div class="campo-formulario">
                        <label for="fecha_alta">Fecha de Alta: *</label>
                        <input type="date" id="fecha_alta" name="fecha_alta" value="<?php echo htmlspecialchars($_POST['fecha_alta'] ?? date('Y-m-d')); ?>" required>
                    </div>
                    <div class="campo-formulario">
                        <label for="fecha_ultima_cuota">Fecha Última Cuota: *</label>
                        <input type="date" id="fecha_ultima_cuota" name="fecha_ultima_cuota" value="<?php echo htmlspecialchars($_POST['fecha_ultima_cuota'] ?? date('Y-m-d')); ?>" required>
                    </div>
                    <div class="campo-formulario">
                        <label for="password">Contraseña: *</label>
                        <input type="password" id="password" name="password" minlength="6" required>
                    </div>
                    <div class="campo-formulario">
                        <label for="rol">Rol: *</label>
                        <select id="rol" name="rol" required>
                            <option value="vecino" <?php echo (($_POST['rol'] ?? '') === 'vecino') ? 'selected' : ''; ?>>Vecino</option>
                            <option value="presidente" <?php echo (($_POST['rol'] ?? '') === 'presidente') ? 'selected' : ''; ?>>Presidente</option>
                            <option value="administrador" <?php echo (($_POST['rol'] ?? '') === 'administrador') ? 'selected' : ''; ?>>Administrador</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="boton-primario">Dar de Alta</button>
                    <a href="../dashboard.php" class="boton-secundario">Cancelar</a>
                </form>
            </section>
        </main>
        
        <footer class="pie-pagina">
            <p>&copy; 2025 Sistema de Gestión de Comunidad de Vecinos</p>
        </footer>
    </div>
</body>
</html>
