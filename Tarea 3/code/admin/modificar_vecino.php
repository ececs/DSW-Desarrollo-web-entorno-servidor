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
    $telefono = trim($_POST['telefono'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $bloque = trim($_POST['bloque'] ?? '');
    $piso = trim($_POST['piso'] ?? '');
    $letra = strtoupper(trim($_POST['letra'] ?? ''));
    $cuotas_impagadas = (int)($_POST['cuotasImpagadas'] ?? 0);
    $fecha_ultima_cuota = $_POST['fechaUltimaCuota'] ?? '';
    $rol_nuevo = $_POST['rolUsuario'] ?? $vecino['rolUsuario'];

    $datosActualizados = [
        'telefono' => $telefono, 'email' => $email, 'piso' => $piso, 'bloque' => $bloque, 
        'letra' => $letra, 'cuotasImpagadas' => $cuotas_impagadas, 
        'fechaUltimaCuota' => $fecha_ultima_cuota, 'rolUsuario' => $rol_nuevo, 'dni' => $dni
    ];

    // Validaciones
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errores[] = "El email no es válido.";
    // Paso el objeto $pdo a la función.
    if (viviendaOcupada($pdo, $bloque, $piso, $letra, $dni)) $errores[] = "La vivienda ya está ocupada por otro vecino.";

    if (empty($errores)) {
        // Lógica para cambiar de presidente
        if ($rol_nuevo === ROL_PRESIDENTE && $vecino['rolUsuario'] !== ROL_PRESIDENTE) {
            // PAso el objeto $pdo a la función.
            $antiguoPresidente = buscarPresidente($pdo);
            
            try {
                $pdo->beginTransaction();

                // 1. Degradar al antiguo presidente si existe
                if ($antiguoPresidente) {
                    $stmt1 = $pdo->prepare("UPDATE vecinos SET rolUsuario = ? WHERE dni = ?");
                    $stmt1->execute([ROL_VECINO, $antiguoPresidente['dni']]);
                }

                // 2. Actualizar al nuevo presidente
                // Paso el objeto $pdo a la función.
                actualizarVecino($pdo, $datosActualizados);

                $pdo->commit();
                $mensaje = "Datos actualizados. Se ha asignado un nuevo presidente.";
                // PAso el objeto $pdo a la función.
                $vecino = buscarVecinoPorDni($pdo, $dni); // Recargar datos

            } catch (PDOException $e) {
                $pdo->rollBack();
                $errores[] = "Error al cambiar de presidente: " . $e->getMessage();
            }

        } else {
            // Actualización normal
            // Paso el objeto $pdo a la función.
            if (actualizarVecino($pdo, $datosActualizados)) {
                $mensaje = "Datos actualizados correctamente.";
                // Paso el objeto $pdo a la función.
                $vecino = buscarVecinoPorDni($pdo, $dni); // Recargar datos
            } else {
                $errores[] = "Error al actualizar los datos.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Vecino</title>
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
                    <a href="../cambiar_password.php">Cambiar Contraseña</a>
                    <a href="../logout.php" class="boton-salir">Cerrar Sesión</a>
                </nav>
            </div>
        </header>
        
        <main class="contenido-principal">
            <section class="seccion-datos">
                <h2>Modificar Vecino</h2>
                
                <?php if (!empty($errores)): ?>
                    <div class="mensaje-error"><strong>Errores:</strong><ul><?php foreach ($errores as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div>
                <?php endif; ?>
                <?php if ($mensaje): ?>
                    <div class="mensaje-exito"><?php echo htmlspecialchars($mensaje); ?></div>
                <?php endif; ?>
                
                <div class="tarjeta-datos" style="margin-bottom: 20px;">
                    <p><strong>Vecino:</strong> <?php echo htmlspecialchars($vecino['nombre'] . ' ' . $vecino['apellidos']); ?></p>
                    <p><strong>DNI:</strong> <?php echo htmlspecialchars($vecino['dni']); ?></p>
                </div>
                
                <form id="formModificar" method="POST" action="">
                    <h3>Datos de Contacto y Vivienda</h3>
                    <div class="campo-formulario">
                        <label for="telefono">Teléfono:</label>
                        <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($_POST['telefono'] ?? $vecino['telefono']); ?>">
                    </div>
                    <div class="campo-formulario">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? $vecino['email']); ?>">
                    </div>
                    <div class="campo-formulario">
                        <label>Bloque: *</label>
                        <input type="text" name="bloque" value="<?php echo htmlspecialchars($_POST['bloque'] ?? $vecino['bloque']); ?>" required>
                    </div>
                    <div class="campo-formulario">
                        <label>Piso: *</label>
                        <input type="text" name="piso" value="<?php echo htmlspecialchars($_POST['piso'] ?? $vecino['piso']); ?>" required>
                    </div>
                    <div class="campo-formulario">
                        <label>Letra: *</label>
                        <input type="text" name="letra" maxlength="2" value="<?php echo htmlspecialchars($_POST['letra'] ?? $vecino['letra']); ?>" required>
                    </div>

                    <h3>Cuotas y Rol</h3>
                    <div class="campo-formulario">
                        <label for="cuotasImpagadas">Cuotas Impagadas:</label>
                        <input type="number" id="cuotasImpagadas" name="cuotasImpagadas" min="0" value="<?php echo htmlspecialchars($_POST['cuotasImpagadas'] ?? $vecino['cuotasImpagadas']); ?>" required>
                    </div>
                    <div class="campo-formulario">
                        <label for="fechaUltimaCuota">Fecha Última Cuota: *</label>
                        <input type="date" id="fechaUltimaCuota" name="fechaUltimaCuota" value="<?php echo htmlspecialchars($_POST['fechaUltimaCuota'] ?? $vecino['fechaUltimaCuota']); ?>" required>
                    </div>
                    <div class="campo-formulario">
                        <label for="rolUsuario">Rol: *</label>
                        <select id="rolUsuario" name="rolUsuario" required>
                            <?php $rolActual = $_POST['rolUsuario'] ?? $vecino['rolUsuario']; ?>
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
    </div>

    <script>
        document.getElementById('formModificar').addEventListener('submit', function(e) {
            const rolSelect = document.getElementById('rolUsuario');
            const nuevoRol = rolSelect.value;
            const rolOriginal = '<?php echo $vecino["rolUsuario"]; ?>';

            if (nuevoRol === '<?php echo ROL_PRESIDENTE; ?>' && rolOriginal !== '<?php echo ROL_PRESIDENTE; ?>') {
                const confirmacion = confirm('¿Está seguro de que desea asignar un nuevo presidente?\nEl presidente actual pasará a ser un vecino.');
                if (!confirmacion) {
                    e.preventDefault(); // Cancela el envío del formulario
                }
            }
        });
    </script>
</body>
</html>