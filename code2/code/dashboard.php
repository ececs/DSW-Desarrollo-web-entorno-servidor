<?php
require_once 'includes/config.php';
require_once 'includes/funciones.php';

verificarSesion();

$dni = $_SESSION['usuario'];
$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];

$vecinos = leerVecinos();
$misDatos = null;

// mensajes
$mensaje = '';
if (isset($_GET['mensaje'])) {
    if ($_GET['mensaje'] == 'baja_exitosa') {
        $mensaje = '<div class="mensaje-exito">Vecino dado de baja correctamente</div>';
    }
}

if ($rol === ROL_VECINO) {
    $misDatos = buscarVecinoPorDni($dni);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Principal</title>
    <link rel="stylesheet" href="css/estilo.css?v=2.0">
    <style>
        .texto-centrado {
            text-align: center;
        }
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
                <!-- Vista vecino -->
                <section class="seccion-datos">
                    <h2>Mis Datos</h2>
                    <div class="tarjeta-datos">
                        <div class="grupo-datos">
                            <h3>Información Personal</h3>
                            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($misDatos['nombre']); ?></p>
                            <p><strong>DNI:</strong> <?php echo htmlspecialchars($misDatos['dni']); ?></p>
                            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($misDatos['telefono'] ?: 'No especificado'); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($misDatos['email'] ?: 'No especificado'); ?></p>
                        </div>
                        
                        <div class="grupo-datos">
                            <h3>Viviendas</h3>
                            <?php foreach ($misDatos['viviendas'] as $idx => $vivienda): ?>
                                <p><strong>Vivienda <?php echo $idx + 1; ?>:</strong> 
                                <?php echo htmlspecialchars($vivienda['bloque'] . '-' . 
                                                            $vivienda['piso'] . 
                                                            $vivienda['letra']); ?>
                                </p>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="grupo-datos">
                            <h3>Cuotas</h3>
                            <p><strong>Fecha de Alta:</strong> <?php echo formatearFecha($misDatos['fecha_alta']); ?></p>
                            <p><strong>Cuotas Pagadas:</strong> <?php echo $misDatos['cuotas_pagadas']; ?></p>
                            <p><strong>Última Cuota:</strong> <?php echo formatearFecha($misDatos['fecha_ultima_cuota']); ?></p>
                            <?php $pendientes = calcularCuotasPendientes($misDatos['fecha_ultima_cuota']); ?>
                            <p class="<?php echo $pendientes > 0 ? 'texto-alerta' : 'texto-ok'; ?>">
                                <strong>Cuotas Pendientes:</strong> <?php echo $pendientes; ?>
                            </p>
                        </div>
                    </div>
                </section>
                
            <?php elseif ($rol === ROL_PRESIDENTE || $rol === ROL_ADMINISTRADOR): ?>
                <!-- Vista presidente/admin -->
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
                                    <th class="texto-centrado">Cuotas Pagadas</th>
                                    <th class="texto-centrado">Cuotas Pendientes</th>
                                    <th class="texto-centrado">Última Cuota</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($vecinos as $vecino): 
                                    $pendientes = calcularCuotasPendientes($vecino['fecha_ultima_cuota']);
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($vecino['nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($vecino['dni']); ?></td>
                                        <td><?php echo htmlspecialchars(formatearViviendas($vecino['viviendas'])); ?></td>
                                        <td><?php echo htmlspecialchars($vecino['telefono'] ?: '-'); ?></td>
                                        <td><?php echo htmlspecialchars($vecino['email'] ?: '-'); ?></td>
                                        <td class="texto-centrado"><?php echo htmlspecialchars($vecino['cuotas_pagadas']); ?></td>
                                        <td class="texto-centrado">
                                            <span class="<?php echo $pendientes > 0 ? 'texto-alerta' : 'texto-ok'; ?>" style="padding: 3px 8px; border-radius: 4px;">
                                                <?php echo $pendientes; ?>
                                            </span>
                                        </td>
                                        <td class="texto-centrado"><?php echo formatearFecha($vecino['fecha_ultima_cuota']); ?></td>
                                        <td class="acciones">
                                            <?php if ($rol === ROL_PRESIDENTE): ?>
                                                <a href="presidente/modificar_cuotas.php?dni=<?php echo urlencode($vecino['dni']); ?>" 
                                                   class="boton-pequeño">Cuotas</a>
                                            <?php endif; ?>
                                            
                                            <?php if ($rol === ROL_ADMINISTRADOR): ?>
                                                <a href="admin/modificar_vecino.php?dni=<?php echo urlencode($vecino['dni']); ?>" 
                                                   class="boton-pequeño">Modificar</a>
                                                <a href="admin/baja_vecino.php?dni=<?php echo urlencode($vecino['dni']); ?>" 
                                                   class="boton-pequeño boton-peligro"
                                                   onclick="return confirm('¿Seguro que quiere dar de baja a este vecino?')">Baja</a>
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