<?php
// Validaciones
// He puesto aqui todas las validaciones para tenerlas juntas y no repetir codigo

function validarNoVacio($valor) {
    return !empty(trim($valor));
}

// Validación DNI español
// Fuentes: 
// - https://www.iteramos.com/pregunta/5316/algoritmo-para-validar-dni-espanol
// - https://es.stackoverflow.com/questions/55910/validar-nif-nie-cif-en-php
function validarDNI($dni) {
    $dni = strtoupper(trim($dni));
    
    // primero compruebo formato basico
    if (!preg_match('/^[0-9]{8}[A-Z]$/', $dni)) {
        return false;
    }
    
    // erificar la letra
    // la letra se calcula con el resto de dividir entre 23
    $letras = 'TRWAGMYFPDXBNJZSQVHLCKE'; // esta secuencia es fija
    $numero = substr($dni, 0, 8);
    $letra = substr($dni, 8, 1);
    
    // calculo cual debería ser
    $letraCorrecta = $letras[$numero % 23];
    
    return $letra === $letraCorrecta;
}

// validar telefono movil español
function validarTelefono($telefono) {
    if (empty(trim($telefono))) {
        return true; // vacio es valido porque es opcional
    }
    
    // quito espacios 
    $telefono = preg_replace('/\s+/', '', $telefono);
    
    // 9 digitos empezando por 6, 7, 8 o 9
    return preg_match('/^[6-9][0-9]{8}$/', $telefono);
}

function validarEmail($email) {
    if (empty(trim($email))) {
        return true;
    }
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// validar fecha formato Y-m-d
function validarFecha($fecha) {
    if (empty($fecha)) {
        return false;
    }
    
    $dt = DateTime::createFromFormat('Y-m-d', $fecha);
    return $dt && $dt->format('Y-m-d') === $fecha;
}

// que la fecha de alta sea antes que la ultima cuota
function validarFechasCoherentes($fechaAlta, $fechaUltimaCuota) {
    if (empty($fechaAlta) || empty($fechaUltimaCuota)) {
        return false;
    }
    
    $dtAlta = new DateTime($fechaAlta);
    $dtCuota = new DateTime($fechaUltimaCuota);
    
    return $dtAlta <= $dtCuota;
}

function validarFechaNoFutura($fecha) {
    $dt = new DateTime($fecha);
    $hoy = new DateTime();
    
    return $dt <= $hoy;
}

// password minimo 6 caracteres
function validarPassword($password) {
    return strlen($password) >= 6;
}

// Esta función valida TODOS los datos de un vecino
// devuelve array con los errores (vacio si todo esta ok)
function validarDatosVecino($datos) {
    $errores = [];
    
    // nombre
    if (!validarNoVacio($datos['nombre'])) {
        $errores[] = "El nombre es obligatorio";
    }
    
    // dni
    if (!validarNoVacio($datos['dni'])) {
        $errores[] = "El DNI es obligatorio";
    } elseif (!validarDNI($datos['dni'])) {
        $errores[] = "El formato del DNI no es válido";
    }
    
    // telefono (opcional)
    if (!validarTelefono($datos['telefono'])) {
        $errores[] = "El formato del teléfono no es válido (9 dígitos)";
    }
    
    // email (opcional)
    if (!validarEmail($datos['email'])) {
        $errores[] = "El formato del email no es válido";
    }
    
    $viviendas = $datos['viviendas'] ?? [];

    // vivienda completa
    if (empty($viviendas)) {
        $errores[] = "Debe indicar al menos una vivienda";
    } else {
        // Validar que ninguna vivienda esté ocupada por otro vecino
        $dniExcluir = $datos['dni_actual'] ?? null;
        foreach ($viviendas as $v) {
            if (viviendaOcupada($v['bloque'], $v['piso'], $v['letra'], $dniExcluir)) {
                $errores[] = "La vivienda {$v['bloque']}-{$v['piso']}{$v['letra']} ya está ocupada";
            }
        }
    }
    
    // fecha alta
    if (!validarFecha($datos['fecha_alta'])) {
        $errores[] = "La fecha de alta no es válida";
    } elseif (!validarFechaNoFutura($datos['fecha_alta'])) {
        $errores[] = "La fecha de alta no puede ser futura";
    }
    
    // fecha ultima cuota
    if (!validarFecha($datos['fecha_ultima_cuota'])) {
        $errores[] = "La fecha de última cuota no es válida";
    } elseif (!validarFechaNoFutura($datos['fecha_ultima_cuota'])) {
        $errores[] = "La fecha de última cuota no puede ser futura";
    }
    
    // verificar coherencia entre fechas
    if (validarFecha($datos['fecha_alta']) && validarFecha($datos['fecha_ultima_cuota'])) {
        if (!validarFechasCoherentes($datos['fecha_alta'], $datos['fecha_ultima_cuota'])) {
            $errores[] = "La fecha de alta no puede ser posterior a la fecha de última cuota";
        }
    }
    
    return $errores;
}
?>