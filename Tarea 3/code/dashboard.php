<?php
require_once 'includes/config.php';
require_once 'includes/db.php'; // Se incluye para tener acceso a $pdo
require_once 'includes/funciones.php';

verificarSesion();

$dni = $_SESSION['usuario'];
$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];

// Paso el objeto $pdo a la función.
$vecinos = leerVecinos($pdo);
$misDatos = null;

// Mensajes de feedback
$mensaje = '';
if (isset($_GET['mensaje'])) {
    if ($_GET['mensaje'] == 'baja_exitosa') {
        $mensaje = '<div class="mensaje-exito">Vecino dado de baja correctamente.</div>';
    } elseif ($_GET['mensaje'] == 'alta_exitosa') {
        $mensaje = '<div class="mensaje-exito">Vecino dado de alta correctamente.</div>';
    } elseif ($_GET['mensaje'] == 'mod_exitosa') {
        $mensaje = '<div class="mensaje-exito">Datos del vecino actualizados correctamente.</div>';
    }
}

if ($rol === ROL_VECINO) {
    // Paso el objeto $pdo a la función.
    $misDatos = buscarVecinoPorDni($pdo, $dni);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Principal</title>
    <link rel="stylesheet" href="css/estilo.css?v=2.1">
    <style>
        .texto-centrado { text-align: center; }
    </style>
</head>
<body>
    <div class="contenedor-principal">
        <header class="cabecera">
            <h1>Comunidad de Vecinos</h1>
            <div class="info-usuario">
                <p><?php echo mensajeBienvenida($nombre, $rol); ?></p>
                <nav class="menu-principal">
                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="activo">Inicio</a>
                    <a href="cambiar_password.php">Cambiar Contraseña</a>
                    <?php if ($rol === ROL_ADMINISTRADOR): ?>
                        <a href="admin/alta_vecino.php">Alta Vecino</a>
                    <?php endif; ?>
                    <a href="logout.php" class="boton-salir">Cerrar Sesión</a>
                </nav>
            </div>
        </header>
        
        <main class="contenido-principal">
            <?php echo $mensaje; ?>
            
            <?php if ($rol === ROL_VECINO && $misDatos): ?>
                <!-- Vista para el rol VECINO -->
                <section class="seccion-datos">
                    <h2>Mis Datos</h2>
                    <div class="tarjeta-datos">
                        <div class="grupo-datos">
                            <h3>Información Personal</h3>
                            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($misDatos['nombre']); ?></p>
                            <p><strong>Apellidos:</strong> <?php echo htmlspecialchars($misDatos['apellidos']); ?></p>
                            <p><strong>DNI:</strong> <?php echo htmlspecialchars($misDatos['dni']); ?></p>
                            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($misDatos['telefono'] ?: 'No especificado'); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($misDatos['email'] ?: 'No especificado'); ?></p>
                        </div>
                        
                        <div class="grupo-datos">
                            <h3>Vivienda</h3>
                            <p><strong>Vivienda:</strong> <?php echo formatearVivienda($misDatos); ?></p>
                        </div>
                        
                        <div class="grupo-datos">
                            <h3>Cuotas</h3>
                            <p><strong>Fecha de Alta:</strong> <?php echo formatearFecha($misDatos['fechaAlta']); ?></p>
                            <p><strong>Cuotas Impagadas:</strong> <?php echo htmlspecialchars($misDatos['cuotasImpagadas']); ?></p>
                            <p><strong>Última Cuota Pagada:</strong> <?php echo formatearFecha($misDatos['fechaUltimaCuota']); ?></p>
                            <?php $pendientes = calcularCuotasPendientes($misDatos['fechaUltimaCuota']); ?>
                            <p class="<?php echo $pendientes > 0 ? 'texto-alerta' : 'texto-ok'; ?>">
                                <strong>Meses Pendientes:</strong> <?php echo $pendientes; ?>
                            </p>
                        </div>
                    </div>
                </section>
                
            <?php elseif (in_array($rol, [ROL_PRESIDENTE, ROL_ADMINISTRADOR])):
                // Vista para PRESIDENTE y ADMINISTRADOR
            ?>
                <section class="seccion-datos">
                    <h2>Lista de Vecinos</h2>
                    
                    <?php if (!empty($vecinos)): ?>
                    <div class="tabla-responsive">
                        <table class="tabla-vecinos">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>DNI</th>
                                    <th>Vivienda</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th class="texto-centrado">Cuotas Impagadas</th>
                                    <th class="texto-centrado">Meses Pendientes</th>
                                    <th class="texto-centrado">Última Cuota</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($vecinos as $vecino): 
                                    $pendientes = calcularCuotasPendientes($vecino['fechaUltimaCuota']);
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($vecino['nombre'] . ' ' . $vecino['apellidos']); ?></td>
                                        <td><?php echo htmlspecialchars($vecino['dni']); ?></td>
                                        <td><?php echo formatearVivienda($vecino); ?></td>
                                        <td><?php echo htmlspecialchars($vecino['telefono'] ?: '-'); ?></td>
                                        <td><?php echo htmlspecialchars($vecino['email'] ?: '-'); ?></td>
                                        <td class="texto-centrado"><?php echo htmlspecialchars($vecino['cuotasImpagadas']); ?></td>
                                        <td class="texto-centrado">
                                            <span class="<?php echo $pendientes > 0 ? 'texto-alerta' : 'texto-ok'; ?>" style="padding: 3px 8px; border-radius: 4px;">
                                                <?php echo $pendientes; ?>
                                            </span>
                                        </td>
                                        <td class="texto-centrado"><?php echo formatearFecha($vecino['fechaUltimaCuota']); ?></td>
                                        <td class="acciones">
                                            <?php if ($rol === ROL_PRESIDENTE): ?>
                                                <a href="presidente/modificar_cuotas.php?dni=<?php echo urlencode($vecino['dni']); ?>" class="boton-pequeño">Cuotas</a>
                                            <?php endif; ?>
                                            
                                            <?php if ($rol === ROL_ADMINISTRADOR): ?>
                                                <a href="admin/modificar_vecino.php?dni=<?php echo urlencode($vecino['dni']); ?>" class="boton-pequeño">Modificar</a>
                                                <a href="admin/baja_vecino.php?dni=<?php echo urlencode($vecino['dni']); ?>" class="boton-pequeño boton-peligro" onclick="return confirm('¿Seguro que quiere dar de baja a este vecino?')">Baja</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                        <p class="mensaje-info">No hay vecinos registrados en el sistema.</p>
                    <?php endif; ?>
                </section>
            <?php endif; ?>
        </main>
        
        <footer class="pie-pagina">
            <p>&copy; 2025 Sistema de Gestión de Comunidad de Vecinos</p>
        </footer>
    </div>
</body>
</html>
