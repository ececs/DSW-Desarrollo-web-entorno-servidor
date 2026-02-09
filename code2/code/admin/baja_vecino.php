<?php
/**
 * Baja vecino (solo ADMINISTRADOR)
 */

require_once '../includes/config.php';
require_once '../includes/funciones.php';

verificarSesion();

// Solo el administrador puede acceder
if ($_SESSION['rol'] !== ROL_ADMINISTRADOR) {
    header('Location: ../dashboard.php');
    exit();
}

$dni = $_GET['dni'] ?? '';
$vecino = buscarVecinoPorDni($dni);

if (!$vecino) {
    header('Location: ../dashboard.php');
    exit();
}

$error = '';

// Procesar confirmacion de baja
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar'])) {
    $vecinos = leerVecinos();
    $nuevosVecinos = [];
    
    foreach ($vecinos as $v) {
        if ($v['dni'] !== $dni) {
            $nuevosVecinos[] = $v;
        }
    }
    
    if (guardarVecinos($nuevosVecinos)) {
        header('Location: ../dashboard.php?mensaje=baja_exitosa');
        exit();
    } else {
        $error = 'Error al dar de baja al vecino';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baja de Vecino - Gestión Comunidad</title>
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
                <h2>Confirmar Baja de Vecino</h2>
                
                <?php if ($error): ?>
                    <div class="mensaje-error">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <div class="mensaje-info" style="background: #fff3cd; border-color: #ffc107; color: #856404;">
                    <strong>Atención:</strong> Esta acción eliminará permanentemente al vecino del sistema.
                </div>
                
                <div class="tarjeta-datos">
                    <h3 style="color: #dc3545; margin-bottom: 15px;">Datos del Vecino a Eliminar</h3>
                    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($vecino['nombre']); ?></p>
                    <p><strong>DNI:</strong> <?php echo htmlspecialchars($vecino['dni']); ?></p>
                    <p><strong>Vivienda:</strong> <?php echo htmlspecialchars($vecino['bloque'] . '-' . $vecino['piso'] . $vecino['letra']); ?></p>
                    <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($vecino['telefono'] ?: 'No especificado'); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($vecino['email'] ?: 'No especificado'); ?></p>
                    <p><strong>Cuotas Pendientes:</strong> 
                        <span class="texto-alerta">
                            <?php echo calcularCuotasPendientes($vecino['fecha_ultima_cuota']); ?>
                        </span>
                    </p>
                </div>
                
                <form method="POST" action="" style="margin-top: 30px;">
                    <button type="submit" name="confirmar" class="boton-peligro" 
                            onclick="return confirm('¿Está completamente seguro de eliminar este vecino?')">
                        Confirmar Baja
                    </button>
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