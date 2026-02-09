<?php
// Modificar datos de un vecino
// Solo accesible para el administrador
// El nombre y DNI no se pueden cambiar, solo el resto

require_once '../includes/config.php';
require_once '../includes/funciones.php';
require_once '../includes/validaciones.php';

verificarSesion();

// Control de acceso - solo admin
if ($_SESSION['rol'] !== ROL_ADMINISTRADOR) {
    header('Location: ../dashboard.php');
    exit();
}

// recibo el DNI por GET
$dni = $_GET['dni'] ?? '';
$vecino = buscarVecinoPorDni($dni);

// si no existe ese vecino, redirijo al dashboard
if (!$vecino) {
    header('Location: ../dashboard.php');
    exit();
}

$errores = [];
$mensaje = '';

// Proceso el formulario si lo envían
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // recojo los campos modificables
    $telefono = trim($_POST['telefono'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $rol = $_POST['rol'] ?? $vecino['rol']; // Rol también es modificable

    // ... (dentro del if ($_SERVER['REQUEST_METHOD'] === 'POST'))
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
    
    $cuotas_pagadas = (int)($_POST['cuotas_pagadas'] ?? 0);
    $fecha_ultima_cuota = $_POST['fecha_ultima_cuota'] ?? '';
    
    // Validaciones
    if (!empty($telefono) && !validarTelefono($telefono)) {
        $errores[] = "El teléfono no es válido";
    }
    
    if (!empty($email) && !validarEmail($email)) {
        $errores[] = "El email no es válido";
    }
    
    // al menos una vivienda es obligatoria
    if (empty($viviendas)) {
        $errores[] = "Debe indicar al menos una vivienda";
    } else {
        // verifico que las viviendas no esten ocupadas por otro
        // le paso el DNI actual para que no se cuente a si mismo
        foreach ($viviendas as $v) {
            if (viviendaOcupada($v['bloque'], $v['piso'], $v['letra'], $dni)) {
                $errores[] = "La vivienda {$v['bloque']}-{$v['piso']}{$v['letra']} ya está ocupada por otro vecino.";
            }
        }
    }
    
    if (!validarFecha($fecha_ultima_cuota)) {
        $errores[] = "La fecha no es válida";
    }

    if (empty($errores) && $rol === ROL_PRESIDENTE && existePresidente($dni)) {
        $errores[] = "Solo puede haber un presidente en la comunidad y ya hay uno asignado.";
    }
    
    // Si todo ok guardo los cambios
    if (empty($errores)) {
        $vecinos = leerVecinos();
        
        // busco el vecino y actualizo sus datos
        foreach ($vecinos as &$v) {
            if ($v['dni'] === $dni) {
                // solo modifico los campos que permito
                $v['telefono'] = $telefono;
                $v['email'] = $email;
                $v['viviendas'] = $viviendas;
                $v['cuotas_pagadas'] = $cuotas_pagadas;
                $v['fecha_ultima_cuota'] = $fecha_ultima_cuota;
                $v['rol'] = $rol;
                break;
            }
        }
        
        // intento guardar
        if (guardarVecinos($vecinos)) {
            $mensaje = "Datos actualizados correctamente";
            $vecino = buscarVecinoPorDni($dni); // recargo los datos actualizados
        } else {
            $errores[] = "Error al guardar";
        }
    }
}

// Preparamos las viviendas para mostrarlas en el formulario
// Usamos los datos de POST si existen (p.ej. si falla la validación), si no, los del vecino
$viviendasActuales = $_POST['viviendas'] ?? $vecino['viviendas'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Vecino</title>
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
                    <a href="../cambiar_password.php">Cambiar Contraseña</a>
                    <a href="../logout.php" class="boton-salir">Cerrar Sesión</a>
                </nav>
            </div>
        </header>
        
        <main class="contenido-principal">
            <section class="seccion-datos">
                <h2>Modificar Vecino</h2>
                
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
                
                <!-- muestro los datos fijos que no se pueden cambiar -->
                <div class="tarjeta-datos" style="margin-bottom: 20px;">
                    <p><strong>Vecino:</strong> <?php echo htmlspecialchars($vecino['nombre']); ?></p>
                    <p><strong>DNI:</strong> <?php echo htmlspecialchars($vecino['dni']); ?></p>
                </div>
                
                <form method="POST" action="">
                    <h3>Datos de Contacto</h3>
                    
                    <div class="campo-formulario">
                        <label for="telefono">Teléfono:</label>
                        <input type="tel" id="telefono" name="telefono"
                               value="<?php echo htmlspecialchars($_POST['telefono'] ?? $vecino['telefono']); ?>">
                    </div>
                    
                    <div class="campo-formulario">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email"
                               value="<?php echo htmlspecialchars($_POST['email'] ?? $vecino['email']); ?>">
                    </div>
                    
                    <h3>Viviendas</h3>

                    <div id="viviendas-container">
                        <?php foreach ($viviendasActuales as $idx => $vivienda): ?>
                        <div class="vivienda-item" data-index="<?php echo $idx; ?>">
                            <h4>Vivienda <?php echo $idx + 1; ?> 
                                <?php if ($idx > 0): ?>
                                    <button type="button" onclick="eliminarVivienda(<?php echo $idx; ?>)" 
                                            class="boton-pequeño boton-peligro">Eliminar</button>
                                <?php endif; ?>
                            </h4>
                            <div class="campo-formulario">
                                <label>Bloque: *</label>
                                <input type="text" name="viviendas[<?php echo $idx; ?>][bloque]" 
                                    value="<?php echo htmlspecialchars($vivienda['bloque']); ?>" required>
                            </div>
                            <div class="campo-formulario">
                                <label>Piso: *</label>
                                <input type="text" name="viviendas[<?php echo $idx; ?>][piso]" 
                                    value="<?php echo htmlspecialchars($vivienda['piso']); ?>" required>
                            </div>
                            <div class="campo-formulario">
                                <label>Letra: *</label>
                                <input type="text" name="viviendas[<?php echo $idx; ?>][letra]" 
                                    value="<?php echo htmlspecialchars($vivienda['letra']); ?>" 
                                    maxlength="1" required>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" onclick="añadirVivienda()" class="boton-secundario" style="margin-bottom: 20px;">
                        + Añadir otra vivienda
                    </button>

                    <script>
                    let contadorViviendas = <?php echo count($viviendasActuales); ?>;

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
                        <label for="cuotas_pagadas">Cuotas Pagadas:</label>
                        <input type="number" id="cuotas_pagadas" name="cuotas_pagadas" min="0"
                               value="<?php echo htmlspecialchars($_POST['cuotas_pagadas'] ?? $vecino['cuotas_pagadas']); ?>" required>
                    </div>
                    
                    <div class="campo-formulario">
                        <label for="fecha_ultima_cuota">Fecha Última Cuota: *</label>
                        <input type="date" id="fecha_ultima_cuota" name="fecha_ultima_cuota"
                               value="<?php echo htmlspecialchars($_POST['fecha_ultima_cuota'] ?? $vecino['fecha_ultima_cuota']); ?>" required>
                    </div>
                    
                    <!-- cuotas pendientes -->
                    <p style="padding: 10px; background: #f0f0f0; border-radius: 5px;">
                        <strong>Cuotas Pendientes:</strong>
                        <span class="<?php echo calcularCuotasPendientes($vecino['fecha_ultima_cuota']) > 0 ? 'texto-alerta' : 'texto-ok'; ?>">
                            <?php echo calcularCuotasPendientes($vecino['fecha_ultima_cuota']); ?>
                        </span>
                    </p>

                    <h3>Acceso</h3>
                    <div class="campo-formulario">
                        <label for="rol">Rol: *</label>
                        <select id="rol" name="rol" required>
                            <?php $rolActual = $_POST['rol'] ?? $vecino['rol']; ?>
                            <option value="vecino" <?php echo $rolActual === ROL_VECINO ? 'selected' : ''; ?>>Vecino</option>
                            <option value="presidente" <?php echo $rolActual === ROL_PRESIDENTE ? 'selected' : ''; ?>>Presidente</option>
                            <option value="administrador" <?php echo $rolActual === ROL_ADMINISTRADOR ? 'selected' : ''; ?>>Administrador</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="boton-primario">Guardar Cambios</button>
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