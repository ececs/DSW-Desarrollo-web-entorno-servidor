<?php
// Funciones de validación

function validarNoVacio($valor) {
    return !empty(trim($valor));
}

function validarDNI($dni) {
    $dni = strtoupper(trim($dni));
    if (!preg_match('/^[0-9]{8}[A-Z]$/', $dni)) {
        return false;
    }
    $letras = 'TRWAGMYFPDXBNJZSQVHLCKE';
    $numero = substr($dni, 0, 8);
    $letra = substr($dni, 8, 1);
    $letraCorrecta = $letras[$numero % 23];
    return $letra === $letraCorrecta;
}

function validarTelefono($telefono) {
    if (empty(trim($telefono))) return true; // Opcional
    return preg_match('/^[6-9][0-9]{8}$/', preg_replace('/\s+/', '', $telefono));
}

function validarEmail($email) {
    if (empty(trim($email))) return true; // Opcional
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validarFecha($fecha) {
    $dt = DateTime::createFromFormat('Y-m-d', $fecha);
    return $dt && $dt->format('Y-m-d') === $fecha;
}

function validarFechasCoherentes($fechaAlta, $fechaUltimaCuota) {
    if (!validarFecha($fechaAlta) || !validarFecha($fechaUltimaCuota)) return false;
    return strtotime($fechaAlta) <= strtotime($fechaUltimaCuota);
}

function validarFechaNoFutura($fecha) {
    if (!validarFecha($fecha)) return false;
    return strtotime($fecha) <= time();
}

function validarPassword($password) {
    return strlen($password) >= 6;
}

// Valida los datos de un vecino para alta o modificación
function validarDatosVecino($datos) {
    $errores = [];

    if (!validarNoVacio($datos['nombre'])) $errores[] = "El nombre es obligatorio.";
    if (!validarNoVacio($datos['apellidos'])) $errores[] = "Los apellidos son obligatorios.";
    if (!validarDNI($datos['dni'])) $errores[] = "El DNI no es válido o está vacío.";
    if (!validarTelefono($datos['telefono'])) $errores[] = "El teléfono no es válido.";
    if (!validarEmail($datos['email'])) $errores[] = "El email no es válido.";

    // Validación de vivienda
    if (!validarNoVacio($datos['bloque'])) $errores[] = "El bloque es obligatorio.";
    if (!validarNoVacio($datos['piso'])) $errores[] = "El piso es obligatorio.";
    if (!validarNoVacio($datos['letra'])) $errores[] = "La letra es obligatoria.";

    // Validar si la vivienda está ocupada (solo si los datos de la vivienda están presentes)
    if (validarNoVacio($datos['bloque']) && validarNoVacio($datos['piso']) && validarNoVacio($datos['letra'])) {
        $dniExcluir = $datos['dni_actual'] ?? $datos['dni'];
        if (viviendaOcupada($datos['bloque'], $datos['piso'], $datos['letra'], $dniExcluir)) {
            $errores[] = "La vivienda ya está ocupada por otro vecino.";
        }
    }

    // Validaciones de fechas
    if (!validarFecha($datos['fecha_alta'])) {
        $errores[] = "La fecha de alta no es válida.";
    } elseif (!validarFechaNoFutura($datos['fecha_alta'])) {
        $errores[] = "La fecha de alta no puede ser futura.";
    }

    if (!validarFecha($datos['fecha_ultima_cuota'])) {
        $errores[] = "La fecha de última cuota no es válida.";
    } elseif (!validarFechaNoFutura($datos['fecha_ultima_cuota'])) {
        $errores[] = "La fecha de última cuota no puede ser futura.";
    }

    if (!validarFechasCoherentes($datos['fecha_alta'], $datos['fecha_ultima_cuota'])) {
        $errores[] = "La fecha de alta no puede ser posterior a la de última cuota.";
    }

    return $errores;
}
?>
