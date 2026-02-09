<?php

// lee vecinos del archivo
function leerVecinos() {
    $vecinos = [];
    
    if (!file_exists(ARCHIVO_DATOS)) {
        return $vecinos;
    }
    
    $lineas = file(ARCHIVO_DATOS, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lineas as $linea) {
        $datos = explode(SEPARADOR, $linea);
        
        if (count($datos) >= 10) {
            $vecinos[] = [
                'nombre' => $datos[0],
                'dni' => $datos[1],
                'telefono' => $datos[2],
                'email' => $datos[3],
                'viviendas' => viviendasStringToArray($datos[4]), // ARRAY de viviendas
                'fecha_alta' => $datos[5],
                'cuotas_pagadas' => (int)$datos[6],
                'fecha_ultima_cuota' => $datos[7],
                'password' => $datos[8],
                'rol' => $datos[9]
            ];
        }
    }
    
    return $vecinos;
}

function guardarVecinos($vecinos) {
    $contenido = '';
    
    foreach ($vecinos as $vecino) {
        $linea = implode(SEPARADOR, [
            $vecino['nombre'],
            $vecino['dni'],
            $vecino['telefono'],
            $vecino['email'],
            viviendasArrayToString($vecino['viviendas']), // Convierte array a string
            $vecino['fecha_alta'],
            $vecino['cuotas_pagadas'],
            $vecino['fecha_ultima_cuota'],
            $vecino['password'],
            $vecino['rol']
        ]);
        $contenido .= $linea . "\n";
    }
    
    return file_put_contents(ARCHIVO_DATOS, $contenido) !== false;
}

// busca vecino por DNI
function buscarVecinoPorDni($dni) {
    $dni = strtoupper(trim($dni));
    $vecinos = leerVecinos();
    
    foreach ($vecinos as $vecino) {
        if (strtoupper(trim($vecino['dni'])) === $dni) {
            return $vecino;
        }
    }
    
    return null;
    
    // intento anterior
    // $resultado = array_filter($vecinos, function($v) use ($dni) {
    //     return strtoupper(trim($v['dni'])) === $dni;
    // });
    // return $resultado ? reset($resultado) : null;
}

function viviendaOcupada($bloque, $piso, $letra, $dniExcluir = '') {
    $vecinos = leerVecinos();
    
    foreach ($vecinos as $vecino) {
        // Saltar si es el vecino actual
        if ($vecino['dni'] === $dniExcluir) {
            continue;
        }
        
        // revisar todas las viviendas del vecino
        foreach ($vecino['viviendas'] as $vivienda) {
            if ($vivienda['bloque'] === $bloque &&
                $vivienda['piso'] === $piso &&
                $vivienda['letra'] === $letra) {
                return true;
            }
        }
    }
    
    return false;
}

// calcular cuotas pendientes
function calcularCuotasPendientes($fechaUltimaCuota) {
    $hoy = new DateTime();
    $ultimaCuota = new DateTime($fechaUltimaCuota);
    
    $primerDiaMes = new DateTime($hoy->format('Y-m') . '-01');
    
    $intervalo = $ultimaCuota->diff($primerDiaMes);
    $meses = ($intervalo->y * 12) + $intervalo->m;
    
    if ($ultimaCuota < $primerDiaMes) {
        $meses++;
    }
    
    return max(0, $meses);
    
    // versión anterior
    // $diff = $hoy->getTimestamp() - $ultimaCuota->getTimestamp();
    // $meses = floor($diff / (30 * 24 * 60 * 60));
    // return $meses;
}

// autenticación - verificar usuario y contraseña
function autenticarUsuario($dni, $password) {
    $vecino = buscarVecinoPorDni($dni);
    
    if (!$vecino) {
        return false;
    }
    
    if (empty($vecino['password'])) {
        return false;
    }
    
    // password_verify es la forma segura de verificar contraseñas hasheadas
    if (password_verify($password, $vecino['password'])) {
        return $vecino;
    }
    
    return false;
}

function formatearFecha($fecha) {
    if (empty($fecha)) {
        return '';
    }
    
    $dt = DateTime::createFromFormat('Y-m-d', $fecha);
    return $dt ? $dt->format('d/m/Y') : $fecha;
}

function mensajeBienvenida($nombre, $rol) {
    $roles = [
        'vecino' => 'Vecino',
        'presidente' => 'Presidente',
        'administrador' => 'Administrador'
    ];
    
    $rolTexto = $roles[$rol] ?? 'Usuario';
    return "Bienvenido, $nombre ($rolTexto)";
}

// verifica si ya existe un presidente
function existePresidente($dniExcluir = '') {
    $vecinos = leerVecinos();
    
    foreach ($vecinos as $vecino) {
        if ($vecino['dni'] !== $dniExcluir && $vecino['rol'] === ROL_PRESIDENTE) {
            return true;
        }
    }
    
    return false;
}

/**
 * Convierte array de viviendas a string para guardar
 * Entrada: [['bloque'=>'A','piso'=>'1','letra'=>'A'], ['bloque'=>'B','piso'=>'2','letra'=>'C']]
 * Salida: "A-1-A,B-2-C"
 */
function viviendasArrayToString($viviendas) {
    if (empty($viviendas)) {
        return '';
    }
    
    $viviendasStr = [];
    foreach ($viviendas as $vivienda) {
        $viviendasStr[] = $vivienda['bloque'] . SEPARADOR_DATOS_VIVIENDA . 
                          $vivienda['piso'] . SEPARADOR_DATOS_VIVIENDA . 
                          $vivienda['letra'];
    }
    
    return implode(SEPARADOR_VIVIENDAS, $viviendasStr);
}

/**
 * Convierte string de viviendas a array
 * Entrada: "A-1-A,B-2-C"
 * Salida: [['bloque'=>'A','piso'=>'1','letra'=>'A'], ['bloque'=>'B','piso'=>'2','letra'=>'C']]
 */
function viviendasStringToArray($viviendasStr) {
    if (empty($viviendasStr)) {
        return [];
    }
    
    $viviendas = [];
    $viviendasList = explode(SEPARADOR_VIVIENDAS, $viviendasStr);
    
    foreach ($viviendasList as $vivStr) {
        $partes = explode(SEPARADOR_DATOS_VIVIENDA, trim($vivStr));
        if (count($partes) === 3) {
            $viviendas[] = [
                'bloque' => $partes[0],
                'piso' => $partes[1],
                'letra' => $partes[2]
            ];
        }
    }
    
    return $viviendas;
}

/**
 * Formatea viviendas para mostrar
 * Entrada: [['bloque'=>'A','piso'=>'1','letra'=>'A'], ['bloque'=>'B','piso'=>'2','letra'=>'C']]
 * Salida: "A-1A, B-2C"
 */
function formatearViviendas($viviendas) {
    if (empty($viviendas)) {
        return 'Sin vivienda';
    }
    
    $formatted = [];
    foreach ($viviendas as $v) {
        $formatted[] = $v['bloque'] . '-' . $v['piso'] . $v['letra'];
    }
    
    return implode(', ', $formatted);
}

?>

