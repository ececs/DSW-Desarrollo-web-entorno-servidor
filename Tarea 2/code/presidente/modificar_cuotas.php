<?php
/**
 * Modificar cuotas de vecino (PRESIDENTE)
 */

require_once '../includes/config.php';
require_once '../includes/funciones.php';
require_once '../includes/validaciones.php';

// Definir la función verificarSesion si no existe
if (!function_exists('verificarSesion')) {
    function verificarSesion() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['rol'])) {
            header('Location: ../login.php');
            exit();
        }
    }
}

verificarSesion();

// Solo el presidente puede acceder
if ($_SESSION['rol'] !== ROL_PRESIDENTE) {
    header('Location: ../dashboard.php');
    exit();
}

$dni = $_GET['dni'] ?? '';
$vecino = buscarVecinoPorDni($dni);

if (!$vecino) {
    header('Location: ../dashboard.php');
    exit();
}

$errores = [];
$mensaje = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cuotas_pagadas = (int)($_POST['cuotas_pagadas'] ?? 0);
    $fecha_ultima_cuota = $_POST['fecha_ultima_cuota'] ?? '';
    
    // Validaciones
    if ($cuotas_pagadas < 0) {
       $errores[] = "Las cuotas pagadas no pueden ser negativas";
    }
    
    if (!validarFecha($fecha_ultima_cuota)) {
        $errores[] = "La fecha de última cuota no es válida";
    } elseif (!validarFechaNoFutura($fecha_ultima_cuota)) {
        $errores[] = "La fecha de última cuota no puede ser futura";
    }
    
    // Verificar coherencia: la fecha no puede ser anterior a la fecha de alta
    if (empty($errores) && !validarFechasCoherentes($vecino['fecha_alta'], $fecha_ultima_cuota)) {
        $errores[] = "La fecha de última cuota no puede ser anterior a la fecha de alta";
    }
    
    // Si no hay errores, actualizar
    if (empty($errores)) {
        $vecinos = leerVecinos();
        
        foreach ($vecinos as &$v) {
            if ($v['dni'] === $dni) {
                $v['cuotas_pagadas'] = $cuotas_pagadas;
                $v['fecha_ultima_cuota'] = $fecha_ultima_cuota;
                break;
            }
        }
        
        if (guardarVecinos($vecinos)) {
            $mensaje = "Cuotas actualizadas correctamente";
            $vecino = buscarVecinoPorDni($dni); // Recargar datos
        } else {
            $errores[] = "Error al guardar los cambios";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Cuotas - Gestión Comunidad</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    <div class="contenedor-principal">
        <header class="cabecera">
            <h1>Gestión de Comunidad de Vecinos</h1>
            <div class="info-usuario">
                <p><?php echo mensajeBienvenida($_SESSION['nombre'], $_SESSION['rol']); ?></p>
                <nav class="menu-principal">
                    <a href="../dashboard.php">Inicio</a>
                    <a href="../logout.php" class="boton-salir">Cerrar Sesión</a>
                </nav>
            </div>
        </header>
        
        <main class="contenido-principal">
            <section class="seccion-datos">
                <h2>Modificar Cuotas de Vecino</h2>
                
                <?php if (!empty($errores)): ?>
                    <div class="mensaje-error">
                        <strong>Errores encontrados:</strong>
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
                
                <div class="tarjeta-datos" style="margin-bottom: 20px;">
                    <h3 style="color: #667eea; margin-bottom: 12px;">Información del Vecino</h3>
                    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($vecino['nombre']); ?></p>
                    <p><strong>DNI:</strong> <?php echo htmlspecialchars($vecino['dni']); ?></p>                    
                    <p><strong>Vivienda(s):</strong> <?php echo htmlspecialchars(formatearViviendas($vecino['viviendas'])); ?></p>
                    <p><strong>Fecha de Alta:</strong> <?php echo formatearFecha($vecino['fecha_alta']); ?></p>
                </div>
                
                <form method="POST" action="">
                    <div class="campo-formulario">
                        <label for="cuotas_pagadas">Cuotas Pagadas:</label>
                        <input type="number" 
                               id="cuotas_pagadas" 
                               name="cuotas_pagadas" 
                               min="0"
                               value="<?php echo htmlspecialchars($_POST['cuotas_pagadas'] ?? $vecino['cuotas_pagadas']); ?>"
                               required>
                        <small>Número total de cuotas que ha pagado el vecino</small>
                    </div>
                    
                    <div class="campo-formulario">
                        <label for="fecha_ultima_cuota">Fecha Última Cuota Pagada:</label>
                        <input type="date" 
                               id="fecha_ultima_cuota" 
                               name="fecha_ultima_cuota" 
                               value="<?php echo htmlspecialchars($_POST['fecha_ultima_cuota'] ?? $vecino['fecha_ultima_cuota']); ?>"
                               required>
                        <small>Fecha en la que se pagó la última cuota</small>
                    </div>
                    
                    <div style="margin: 20px 0; padding: 15px; background: #f0f0f0; border-radius: 5px;">
                        <h4 style="color: #333; margin-bottom: 10px;">Estado Actual de Cuotas</h4>
                        <p>
                            <strong>Cuotas Pendientes:</strong> 
                            <span class="<?php echo calcularCuotasPendientes($vecino['fecha_ultima_cuota']) > 0 ? 'texto-alerta' : 'texto-ok'; ?>">
                                <?php echo calcularCuotasPendientes($vecino['fecha_ultima_cuota']); ?>
                            </span>
                        </p>
                        <p style="margin-top: 8px; font-size: 13px; color: #666;">
                            <em>* Las cuotas pendientes se calculan automáticamente según la fecha de última cuota pagada</em>
                        </p>
                    </div>
                    
                    <div class="mensaje-info">
                        <strong> Nota:</strong> Al actualizar las cuotas pagadas, actualizar también 
                        la fecha de la última cuota pagada para mantener la coherencia de los datos.
                    </div>
                    
                    <button type="submit" class="boton-primario" style="margin-top: 20px;">Guardar Cambios</button>
                    <a href="../dashboard.php" class="boton-secundario" style="margin-left: 10px;">Cancelar</a>
                </form>
            </section>
        </main>
        
        <footer class="pie-pagina">
            <p>&copy; 2025 Sistema de Gestión de Comunidad de Vecinos</p>
        </footer>
    </div>
</body>
</html>