<?php
require_once '../includes/config.php';
require_once '../includes/db.php'; // Se incluye para tener acceso a $pdo
require_once '../includes/funciones.php';
require_once '../includes/validaciones.php';

verificarSesion();

if ($_SESSION['rol'] !== ROL_PRESIDENTE) {
    header('Location: ../dashboard.php');
    exit();
}

$dni = $_GET['dni'] ?? '';
// Paso el objeto $pdo a la función.
$vecino = buscarVecinoPorDni($pdo, $dni);

if (!$vecino) {
    header('Location: ../dashboard.php');
    exit();
}

$errores = [];
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cuotas_impagadas = (int)($_POST['cuotasImpagadas'] ?? 0);
    $fecha_ultima_cuota = $_POST['fechaUltimaCuota'] ?? '';

    // Validaciones
    if ($cuotas_impagadas < 0) $errores[] = "Las cuotas no pueden ser negativas.";
    if (!validarFecha($fecha_ultima_cuota)) $errores[] = "La fecha no es válida.";
    if (empty($errores) && strtotime($fecha_ultima_cuota) < strtotime($vecino['fechaAlta'])) {
        $errores[] = "La fecha de última cuota no puede ser anterior a la fecha de alta.";
    }

    if (empty($errores)) {
        // Paso el objeto $pdo a la función.
        if (actualizarCuotas($pdo, $dni, $cuotas_impagadas, $fecha_ultima_cuota)) {
            $mensaje = "Cuotas actualizadas correctamente.";
            // Paso el objeto $pdo a la función.
            $vecino = buscarVecinoPorDni($pdo, $dni); // Recargar datos
        } else {
            $errores[] = "Error al guardar los cambios.";
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
    <link rel="stylesheet" href="../css/estilo.css?v=2.1">
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
                    <div class="mensaje-error"><strong>Errores:</strong><ul><?php foreach ($errores as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div>
                <?php endif; ?>
                <?php if ($mensaje): ?>
                    <div class="mensaje-exito"><?php echo htmlspecialchars($mensaje); ?></div>
                <?php endif; ?>
                
                <div class="tarjeta-datos" style="margin-bottom: 20px;">
                    <h3 style="color: #667eea; margin-bottom: 12px;">Información del Vecino</h3>
                    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($vecino['nombre'] . ' ' . $vecino['apellidos']); ?></p>
                    <p><strong>DNI:</strong> <?php echo htmlspecialchars($vecino['dni']); ?></p>
                    <p><strong>Vivienda:</strong> <?php echo formatearVivienda($vecino); ?></p>
                    <p><strong>Fecha de Alta:</strong> <?php echo formatearFecha($vecino['fechaAlta']); ?></p>
                </div>
                
                <form method="POST" action="">
                    <div class="campo-formulario">
                        <label for="cuotasImpagadas">Cuotas Impagadas:</label>
                        <input type="number" id="cuotasImpagadas" name="cuotasImpagadas" min="0" value="<?php echo htmlspecialchars($_POST['cuotasImpagadas'] ?? $vecino['cuotasImpagadas']); ?>" required>
                    </div>
                    
                    <div class="campo-formulario">
                        <label for="fechaUltimaCuota">Fecha Última Cuota Pagada:</label>
                        <input type="date" id="fechaUltimaCuota" name="fechaUltimaCuota" value="<?php echo htmlspecialchars($_POST['fechaUltimaCuota'] ?? $vecino['fechaUltimaCuota']); ?>" required>
                    </div>
                    
                    <div style="margin: 20px 0; padding: 15px; background: #f0f0f0; border-radius: 5px;">
                        <h4 style="color: #333; margin-bottom: 10px;">Estado Actual de Cuotas</h4>
                        <p><strong>Meses Pendientes:</strong> 
                            <span class="<?php echo calcularCuotasPendientes($vecino['fechaUltimaCuota']) > 0 ? 'texto-alerta' : 'texto-ok'; ?>">
                                <?php echo calcularCuotasPendientes($vecino['fechaUltimaCuota']); ?>
                            </span>
                        </p>
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
