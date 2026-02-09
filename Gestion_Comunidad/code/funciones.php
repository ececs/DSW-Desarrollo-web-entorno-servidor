<?php
//Contiene todas las funciones de ayuda para la manipulación de datos y la renderización de vistas.
 

define('DATA_FILE_FUNC', 'vecinos.dat');

// --- Funciones Comunes ---

// Lee y devuelve el array de vecinos desde el archivo de datos.

function leer_vecinos() {
    if (!file_exists(DATA_FILE_FUNC)) return [];
    return unserialize(file_get_contents(DATA_FILE_FUNC));
}

//Escribe el array de vecinos proporcionado en el archivo de datos.
 
function guardar_vecinos($vecinos) {
    file_put_contents(DATA_FILE_FUNC, serialize($vecinos));
}

//sdCalcula las cuotas pendientes de un vecino.
 
function calcular_cuotas_pendientes($fecha_ultima_cuota) {
    $fecha_ultima = new DateTime($fecha_ultima_cuota);
    $hoy = new DateTime();
    $diferencia = $hoy->diff($fecha_ultima);
    // Calcula la diferencia total de meses
    return ($diferencia->y * 12) + $diferencia->m;
}

// --- Funciones del Administrador ---

function dar_alta_vecino($datos) {
    $vecinos = leer_vecinos();
    $usuario = $datos['usuario'];
    // Validación básica
    if (empty($usuario) || empty($datos['nombre']) || empty($datos['dni'])) return;
    
    $vecinos[$usuario] = [
        'nombre' => $datos['nombre'],
        'dni' => $datos['dni'],
        'clave' => $datos['clave'],
        'telefono' => $datos['telefono'],
        'email' => $datos['email'],
        'vivienda' => $datos['vivienda'],
        'fecha_alta' => date('Y-m-d'),
        'cuotas_pagadas' => 0,
        'fecha_ultima_cuota' => date('Y-m-d'),
        'rol' => 'vecino'
    ];
    guardar_vecinos($vecinos);
}

function dar_baja_vecino($usuario) {
    $vecinos = leer_vecinos();
    if (isset($vecinos[$usuario])) {
        unset($vecinos[$usuario]);
        guardar_vecinos($vecinos);
    }
}

// --- Funciones del Presidente ---

function modificar_cuotas($usuario, $nuevas_cuotas) {
    $vecinos = leer_vecinos();
    if (isset($vecinos[$usuario])) {
        $vecinos[$usuario]['cuotas_pagadas'] = $nuevas_cuotas;
        // Al modificar las cuotas, se actualiza la fecha del último pago a hoy
        $vecinos[$usuario]['fecha_ultima_cuota'] = date('Y-m-d');
        guardar_vecinos($vecinos);
    }
}

// --- Funciones para Renderizar Vistas ---

function mostrar_vista_vecino($vecino) {
    echo "<h3>Mis Datos Personales</h3>";
    echo "<ul>";
    echo "<li><strong>Nombre:</strong> " . htmlspecialchars($vecino['nombre']) . "</li>";
    echo "<li><strong>DNI:</strong> " . htmlspecialchars($vecino['dni']) . "</li>";
    echo "<li><strong>Teléfono:</strong> " . htmlspecialchars($vecino['telefono']) . "</li>";
    echo "<li><strong>Email:</strong> " . htmlspecialchars($vecino['email']) . "</li>";
    echo "<li><strong>Vivienda:</strong> " . htmlspecialchars($vecino['vivienda']) . "</li>";
    echo "<li><strong>Fecha de Alta:</strong> " . htmlspecialchars($vecino['fecha_alta']) . "</li>";
    echo "</ul>";
    
    echo "<h3>Estado de Cuotas</h3>";
    $pendientes = calcular_cuotas_pendientes($vecino['fecha_ultima_cuota']);
    echo "<ul>";
    echo "<li><strong>Cuotas Pagadas:</strong> " . htmlspecialchars($vecino['cuotas_pagadas']) . "</li>";
    echo "<li><strong>Último Pago:</strong> " . htmlspecialchars($vecino['fecha_ultima_cuota']) . "</li>";
    echo "<li><strong>Cuotas Pendientes:</strong> <span class='error'>" . $pendientes . "</span></li>";
    echo "</ul>";
}

function mostrar_vista_presidente($vecinos) {
    echo "<h3>Gestión de Cuotas de Vecinos</h3>";
    echo "<table>";
    echo "<tr><th>Usuario</th><th>Nombre</th><th>Vivienda</th><th>Cuotas Pagadas</th><th>Último Pago</th><th>Cuotas Pendientes</th><th>Acción</th></tr>";
    foreach ($vecinos as $usuario => $datos) {
        if ($datos['rol'] === 'vecino') {
            $pendientes = calcular_cuotas_pendientes($datos['fecha_ultima_cuota']);
            echo "<tr>";
            echo "<td>" . htmlspecialchars($usuario) . "</td>";
            echo "<td>" . htmlspecialchars($datos['nombre']) . "</td>";
            echo "<td>" . htmlspecialchars($datos['vivienda']) . "</td>";
            echo "<td>" . htmlspecialchars($datos['cuotas_pagadas']) . "</td>";
            echo "<td>" . htmlspecialchars($datos['fecha_ultima_cuota']) . "</td>";
            echo "<td>" . $pendientes . "</td>";
            echo "<td>
                    <form action='principal.php' method='POST' style='margin:0;'>
                        <input type='hidden' name='accion' value='modificar_cuotas'>
                        <input type='hidden' name='usuario' value='" . htmlspecialchars($usuario) . "'>
                        <input type='number' name='cuotas_pagadas' value='" . htmlspecialchars($datos['cuotas_pagadas']) . "' style='width: 60px;'>
                        <input type='submit' value='Actualizar'>
                    </form>
                  </td>";
            echo "</tr>";
        }
    }
    echo "</table>";
}

function mostrar_vista_administrador($vecinos) {
    echo "<h3>Gestión Total de Vecinos</h3>";
    echo "<table>";
    echo "<tr><th>Usuario</th><th>Nombre</th><th>DNI</th><th>Vivienda</th><th>Teléfono</th><th>Email</th><th>Acción</th></tr>";
    foreach ($vecinos as $usuario => $datos) {
        if ($datos['rol'] !== 'administrador') {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($usuario) . "</td>";
            echo "<td>" . htmlspecialchars($datos['nombre']) . "</td>";
            echo "<td>" . htmlspecialchars($datos['dni']) . "</td>";
            echo "<td>" . htmlspecialchars($datos['vivienda']) . "</td>";
            echo "<td>" . htmlspecialchars($datos['telefono']) . "</td>";
            echo "<td>" . htmlspecialchars($datos['email']) . "</td>";
            echo "<td>
                    <form action='principal.php' method='POST' style='margin:0;'>
                        <input type='hidden' name='accion' value='borrar'>
                        <input type='hidden' name='usuario' value='" . htmlspecialchars($usuario) . "'>
                        <input type='submit' value='Borrar'>
                    </form>
                  </td>";
            echo "</tr>";
        }
    }
    echo "</table>";

    // Formulario para añadir un nuevo vecino
    echo "<div class='alta-form'>";
    echo "<h4>Alta de Nuevo Vecino</h4>";
    echo "<form action='principal.php' method='POST'>";
    echo "<input type='hidden' name='accion' value='alta'>";
    echo "<input type='text' name='usuario' placeholder='Usuario' required> ";
    echo "<input type='password' name='clave' placeholder='Contraseña' required> ";
    echo "<input type='text' name='nombre' placeholder='Nombre Completo' required> ";
    echo "<input type='text' name='dni' placeholder='DNI' required> ";
    echo "<input type='text' name='vivienda' placeholder='Vivienda (Bloque, Piso, Letra)'> ";
    echo "<input type='tel' name='telefono' placeholder='Teléfono'> ";
    echo "<input type='email' name='email' placeholder='Email'> ";
    echo "<input type='submit' value='Dar de Alta'>";
    echo "</form>";
    echo "</div>";
}