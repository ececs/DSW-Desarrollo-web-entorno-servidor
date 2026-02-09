<?php
// Config

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// archivo de datos
define('ARCHIVO_DATOS', __DIR__ . '/../data/vecinos.dat');
define('SEPARADOR', '|');
define('SEPARADOR_VIVIENDAS', ','); 
define('SEPARADOR_DATOS_VIVIENDA', '-'); 


//  roles
define('ROL_VECINO', 'vecino');
define('ROL_PRESIDENTE', 'presidente');
define('ROL_ADMINISTRADOR', 'administrador');

define('DIA_COBRO', 1);

function verificarSesion() {
    if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol'])) {
        header('Location: login.php');
        exit();
    }
}

function obtenerNombreUsuario() {
    return isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Usuario';
}

function obtenerRolUsuario() {
    return isset($_SESSION['rol']) ? $_SESSION['rol'] : '';
}
?>