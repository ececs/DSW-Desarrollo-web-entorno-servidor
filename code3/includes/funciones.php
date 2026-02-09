<?php
/**
 * Funciones para la gestión de datos de la comunidad de vecinos.
 * Incluye operaciones CRUD para vecinos, autenticación, y otras utilidades.
 */

require_once 'db.php';

/**
 * Obtiene todos los vecinos de la base de datos.
 * Los resultados se ordenan por rol (administrador, presidente, vecino) y luego por nombre.
 */
function leerVecinos(PDO $pdo): array {
    try {
        // La consulta SQL ordena los vecinos para mostrar primero los roles más altos.
        $sql = "SELECT * FROM vecinos ORDER BY FIELD(rolUsuario, 'administrador', 'presidente', 'vecino'), nombre ASC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        // En caso de error, se registra en el log y se devuelve un array vacío.
        error_log("Error en leerVecinos: " . $e->getMessage());
        return [];
    }
}

/**
 * Busca un vecino específico por su número de DNI.
 */
function buscarVecinoPorDni(PDO $pdo, string $dni): ?array {
    $dni = strtoupper(trim($dni));
    try {
        $stmt = $pdo->prepare("SELECT * FROM vecinos WHERE dni = ?");
        $stmt->execute([$dni]);
        // fetch() devuelve false si no hay resultado, lo convertimos a null por consistencia.
        $resultado = $stmt->fetch();
        return $resultado === false ? null : $resultado;
    } catch (PDOException $e) {
        error_log("Error en buscarVecinoPorDni: " . $e->getMessage());
        return null;
    }
}

/**
 * Busca al presidente actual de la comunidad.
 */
function buscarPresidente(PDO $pdo): ?array {
    try {
        $stmt = $pdo->prepare("SELECT * FROM vecinos WHERE rolUsuario = ?");
        $stmt->execute([ROL_PRESIDENTE]);
        $resultado = $stmt->fetch();
        return $resultado === false ? null : $resultado;
    } catch (PDOException $e) {
        error_log("Error en buscarPresidente: " . $e->getMessage());
        return null;
    }
}

/**
 * Comprueba si una vivienda ya está ocupada por otro vecino.
 * Permite excluir un DNI para casos de modificación de un vecino existente.
 */
function viviendaOcupada(PDO $pdo, string $bloque, int $piso, string $letra, string $dniExcluir = ''): bool {
    try {
        $sql = "SELECT COUNT(*) FROM vecinos WHERE bloque = ? AND piso = ? AND letra = ?";
        $params = [$bloque, $piso, $letra];
        
        // Si se proporciona un DNI a excluir, se añade a la consulta.
        if (!empty($dniExcluir)) {
            $sql .= " AND dni != ?";
            $params[] = $dniExcluir;
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log("Error en viviendaOcupada: " . $e->getMessage());
        // En caso de error, se asume que está ocupada para prevenir duplicados.
        return true;
    }
}

/**
 * Calcula el número de cuotas pendientes desde la fecha del último pago.
 */
function calcularCuotasPendientes(?string $fechaUltimaCuota): int {
    if (empty($fechaUltimaCuota)) {
        return 12; // Valor por defecto si nunca ha pagado (1 año).
    }
    try {
        $hoy = new DateTime();
        $ultimaCuota = new DateTime($fechaUltimaCuota);
        
        // Si la fecha de última cuota es futura, no hay deudas.
        if ($ultimaCuota > $hoy) return 0;
        
        $intervalo = $ultimaCuota->diff($hoy);
        $meses = ($intervalo->y * 12) + $intervalo->m;
        
        // Si el día actual es posterior al día de pago del mes, se suma un mes.
        if ((int)$hoy->format('d') > (int)$ultimaCuota->format('d')) {
            $meses++;
        }

        return $meses;

    } catch (Exception $e) {
        error_log("Error en calcularCuotasPendientes: " . $e->getMessage());
        return 0; // En caso de error, se asume que no hay deuda.
    }
}

/**
 * Autentica a un usuario comparando el DNI y la contraseña con la base de datos.
 */
function autenticarUsuario(PDO $pdo, string $dni, string $password) {
    $vecino = buscarVecinoPorDni($pdo, $dni);
    
    // Se verifica que el vecino exista y que la contraseña sea correcta.
    if ($vecino && password_verify($password, $vecino['passUsuario'])) {
        return $vecino;
    }
    
    return false;
}

/**
 * Formatea una fecha del formato 'Y-m-d' a 'd/m/Y'.
 */
function formatearFecha(?string $fecha): string {
    if (empty($fecha)) return '';
    try {
        $dt = new DateTime($fecha);
        return $dt->format('d/m/Y');
    } catch (Exception $e) {
        // Si la fecha no es válida, se devuelve tal cual para no perder el dato.
        return $fecha;
    }
}

/**
 * Genera un mensaje de bienvenida personalizado para el usuario.
 */
function mensajeBienvenida(string $nombre, string $rol): string {
    $roles = [
        'vecino' => 'Vecino',
        'presidente' => 'Presidente',
        'administrador' => 'Administrador'
    ];
    $rolTexto = $roles[$rol] ?? 'Usuario';
    return "Bienvenido, " . htmlspecialchars($nombre) . " ($rolTexto)";
}

/**
 * Comprueba si ya existe un presidente en la comunidad.
 * Permite excluir un DNI para casos de modificación.
 */
function existePresidente(PDO $pdo, string $dniExcluir = ''): bool {
    try {
        $sql = "SELECT COUNT(*) FROM vecinos WHERE rolUsuario = 'presidente'";
        $params = [];
        if (!empty($dniExcluir)) {
            $sql .= " AND dni != ?";
            $params[] = $dniExcluir;
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log("Error en existePresidente: " . $e->getMessage());
        // En caso de error, se devuelve true para evitar nombrar otro presidente.
        return true;
    }
}

/**
 * Formatea la dirección de una vivienda para su visualización.
 */
function formatearVivienda(array $vecino): string {
    if (empty($vecino['bloque']) && empty($vecino['piso'])) {
        return 'Sin vivienda';
    }
    return htmlspecialchars($vecino['bloque'] . '-' . $vecino['piso'] . $vecino['letra']);
}

/**
 * Crea un nuevo vecino en la base de datos.
 */
function crearVecino(PDO $pdo, array $datos): bool {
    $sql = "INSERT INTO vecinos (nombre, apellidos, dni, telefono, email, fechaAlta, piso, bloque, letra, cuotasImpagadas, fechaUltimaCuota, nombreUsuario, passUsuario, rolUsuario) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    try {
        $stmt = $pdo->prepare($sql);
        return $stmt->execute(array_values($datos));
    } catch (PDOException $e) {
        error_log("Error en crearVecino: " . $e->getMessage());
        return false;
    }
}

/**
 * Actualiza los datos de un vecino en la base de datos.
 */
function actualizarVecino(PDO $pdo, array $datos): bool {
    $sql = "UPDATE vecinos SET telefono = ?, email = ?, piso = ?, bloque = ?, letra = ?, cuotasImpagadas = ?, fechaUltimaCuota = ?, rolUsuario = ? WHERE dni = ?";
    try {
        $stmt = $pdo->prepare($sql);
        return $stmt->execute(array_values($datos));
    } catch (PDOException $e) {
        error_log("Error en actualizarVecino: " . $e->getMessage());
        return false;
    }
}

/**
 * Actualiza únicamente las cuotas y la fecha de pago de un vecino.
 * Esta función es utilizada por el presidente.
 */
function actualizarCuotas(PDO $pdo, string $dni, int $cuotas, string $fecha): bool {
    $sql = "UPDATE vecinos SET cuotasImpagadas = ?, fechaUltimaCuota = ? WHERE dni = ?";
    try {
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$cuotas, $fecha, $dni]);
    } catch (PDOException $e) {
        error_log("Error en actualizarCuotas: " . $e->getMessage());
        return false;
    }
}

/**
 * Elimina un vecino de la base de datos por su DNI.
 */
function eliminarVecino(PDO $pdo, string $dni): bool {
    $sql = "DELETE FROM vecinos WHERE dni = ?";
    try {
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$dni]);
    } catch (PDOException $e) {
        error_log("Error en eliminarVecino: " . $e->getMessage());
        return false;
    }
}

/**
 * Cambia la contraseña de un usuario.
 */
function cambiarPassword(PDO $pdo, string $dni, string $newPassword): bool {
    // Se hashea la nueva contraseña para almacenarla de forma segura.
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $sql = "UPDATE vecinos SET passUsuario = ? WHERE dni = ?";
    try {
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$hashedPassword, $dni]);
    } catch (PDOException $e) {
        error_log("Error en cambiarPassword: " . $e->getMessage());
        return false;
    }
}
?>