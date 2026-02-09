<?php

/**
 * Bootstrap de la aplicación
 *
 * Este archivo inicializa la aplicación y carga la configuración necesaria
 */

// Cargar configuración del restaurante
$config = require __DIR__ . '/../config/restaurant.php';

// Extraer variables para usar en las vistas
$name = $config['name'];
$address = $config['address'];
$latitude = $config['latitude'];
$longitude = $config['longitude'];
$phone = $config['phone'];
$email = $config['email'];

// Función auxiliar para obtener configuración
function getConfig($key = null)
{
    static $config = null;

    if ($config === null) {
        $config = require __DIR__ . '/../config/restaurant.php';
    }

    if ($key === null) {
        return $config;
    }

    return $config[$key] ?? null;
}

// Incluir autoload de Composer si existe
$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require $autoloadPath;
}
