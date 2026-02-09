<?php
// Alta  vecinos - solo administrador

require_once '../includes/config.php';
require_once '../includes/funciones.php';
require_once '../includes/validaciones.php';

verificarSesion();

if ($_SESSION['rol'] !== ROL_ADMINISTRADOR) {
    header('Location: ../dashboard.php');
    exit();
}

$errores = [];
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $dni = strtoupper(trim($_POST['dni'] ?? ''));
    $telefono = trim($_POST['telefono'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $viviendas = [];
    if (isset($_POST['viviendas']) && is_array($_POST['viviendas'])) {
        foreach ($_POST['viviendas'] as $v) {
            if (!empty($v['bloque']) && !empty($v['piso']) && !empty($v['letra'])) {
                $viviendas[] = [
                    'bloque' => trim($v['bloque']),
                    'piso' => trim($v['piso']),
                    'letra' => strtoupper(trim($v['letra']))
                ];
            }
        }
    }
    $fecha_alta = $_POST['fecha_alta'] ?? date('Y-m-d');
    $fecha_ultima_cuota = $_POST['fecha_ultima_cuota'] ?? date('Y-m-d');
    $password = $_POST['password'] ?? '';
    $rol = $_POST['rol'] ?? ROL_VECINO;
    
    // Preparar array de datos para validación
    $datosVecino = [
        'nombre' => $nombre,
        'dni' => $dni,
        'telefono' => $telefono,
        'email' => $email,
        'viviendas' => $viviendas, // Pasamos el array de viviendas
        'fecha_alta' => $fecha_alta,
        'fecha_ultima_cuota' => $fecha_ultima_cuota
    ];
    
    // Uso la funcion validarDatosVecino() para validar los datos.
    $errores = validarDatosVecino($datosVecino);
    
    // Validaciones adicionales específicas de alta
    if (empty($password)) {
        $errores[] = "La contraseña es obligatoria";
    } elseif (!validarPassword($password)) {
        $errores[] = "La contraseña debe tener al menos 6 caracteres";
    }
    
    // comprobar DNI único
    if (empty($errores) && buscarVecinoPorDni($dni)) {
        $errores[] = "Ya existe un vecino con ese DNI";
    }

    // verificar que no haya otro presidente si se intenta asignar ese rol
    if (empty($errores) && $rol === ROL_PRESIDENTE && existePresidente()) {
        $errores[] = "Solo puede haber un presidente en la comunidad y ya hay un presidente dado de alta";
    }

    // guardar
    if (empty($errores)) {
        $vecinos = leerVecinos();
        
        $nuevoVecino = [
            'nombre' => $nombre,
            'dni' => $dni,
            'telefono' => $telefono,
            'email' => $email,
            'viviendas' => $viviendas,
            'fecha_alta' => $fecha_alta,
            'cuotas_pagadas' => 0,
            'fecha_ultima_cuota' => $fecha_ultima_cuota,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'rol' => $rol
        ];
        
        $vecinos[] = $nuevoVecino;
        
        if (guardarVecinos($vecinos)) {
            $mensaje = "Vecino dado de alta correctamente";
            $_POST = [];
        } else {
            $errores[] = "Error al guardar los datos";
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
    <link rel="stylesheet" href="../css/estilo.css?v=2.0">
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
                
                <?php if ($mensaje): ?>
                    <div class="mensaje-exito">
                        <?php echo htmlspecialchars($mensaje); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <h3>Datos Personales</h3>
                    
                    <div class="campo-formulario">
                        <label for="nombre">Nombre y Apellidos: *</label>
                        <input type="text" id="nombre" name="nombre" 
                               value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="campo-formulario">
                        <label for="dni">DNI: *</label>
                        <input type="text" id="dni" name="dni" maxlength="9"
                               value="<?php echo htmlspecialchars($_POST['dni'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="campo-formulario">
                        <label for="telefono">Teléfono:</label>
                        <input type="tel" id="telefono" name="telefono"
                               value="<?php echo htmlspecialchars($_POST['telefono'] ?? ''); ?>">
                    </div>
                    
                    <div class="campo-formulario">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email"
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>
                    
                    <h3>Viviendas</h3>

                    <div id="viviendas-container">
                        <div class="vivienda-item" data-index="0">
                            <h4>Vivienda 1</h4>
                            <div class="campo-formulario">
                                <label>Bloque: *</label>
                                <input type="text" name="viviendas[0][bloque]" required>
                            </div>
                            
                            <div class="campo-formulario">
                                <label>Piso: *</label>
                                <input type="text" name="viviendas[0][piso]" required>
                            </div>
                            
                            <div class="campo-formulario">
                                <label>Letra: *</label>
                                <input type="text" name="viviendas[0][letra]" maxlength="1" required>
                            </div>
                        </div>
                    </div>

                    <button type="button" onclick="añadirVivienda()" class="boton-secundario" style="margin-bottom: 20px;">
                        + Añadir otra vivienda
                    </button>

                    <script>
                    let contadorViviendas = 1;

                    function añadirVivienda() {
                        const container = document.getElementById('viviendas-container');
                        const index = contadorViviendas++;
                        
                        const div = document.createElement('div');
                        div.className = 'vivienda-item';
                        div.setAttribute('data-index', index);
                        div.innerHTML = `
                            <h4>Vivienda ${index + 1} 
                                <button type="button" onclick="eliminarVivienda(${index})" 
                                        class="boton-pequeño boton-peligro">Eliminar</button>
                            </h4>
                            <div class="campo-formulario">
                                <label>Bloque: *</label>
                                <input type="text" name="viviendas[${index}][bloque]" required>
                            </div>
                            <div class="campo-formulario">
                                <label>Piso: *</label>
                                <input type="text" name="viviendas[${index}][piso]" required>
                            </div>
                            <div class="campo-formulario">
                                <label>Letra: *</label>
                                <input type="text" name="viviendas[${index}][letra]" maxlength="1" required>
                            </div>
                        `;
                        
                        container.appendChild(div);
                    }

                    function eliminarVivienda(index) {
                        const item = document.querySelector(`[data-index="${index}"]`);
                        if (item) {
                            item.remove();
                        }
                    }
                    </script>
                   
                    <h3>Cuotas</h3>
                    
                    <div class="campo-formulario">
                        <label for="fecha_alta">Fecha de Alta: *</label>
                        <input type="date" id="fecha_alta" name="fecha_alta"
                               value="<?php echo htmlspecialchars($_POST['fecha_alta'] ?? date('Y-m-d')); ?>" required>
                    </div>
                    
                    <div class="campo-formulario">
                        <label for="fecha_ultima_cuota">Fecha Última Cuota: *</label>
                        <input type="date" id="fecha_ultima_cuota" name="fecha_ultima_cuota"
                               value="<?php echo htmlspecialchars($_POST['fecha_ultima_cuota'] ?? date('Y-m-d')); ?>" required>
                    </div>
                    
                    <h3>Acceso</h3>
                    
                    <div class="campo-formulario">
                        <label for="password">Contraseña: *</label>
                        <input type="password" id="password" name="password" minlength="6" required>
                        <small>Mínimo 6 caracteres</small>
                    </div>
                    
                    <div class="campo-formulario">
                        <label for="rol">Rol: *</label>
                        <select id="rol" name="rol" required>
                            <option value="vecino">Vecino</option>
                            <option value="presidente">Presidente</option>
                            <option value="administrador">Administrador</option>
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