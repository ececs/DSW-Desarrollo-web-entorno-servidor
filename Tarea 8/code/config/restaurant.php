<?php

/**
 * Configuración del restaurante para la demo de geolocalización
 *
 * Este archivo contiene los datos de ubicación del restaurante
 * que se utilizarán en el mapa interactivo.
 */

return [
    'name' => 'Restaurante El Drago',
    'address' => 'Calle La Rosa, 15, 38201 San Cristóbal de La Laguna, Tenerife',
    // Coordenadas de La Laguna, Tenerife (Centro histórico)
    'latitude' => 28.4853,
    'longitude' => -16.3154,
    'phone' => '+34 922 123 456',
    'email' => 'info@eldrago.es',
    'hours' => [
        'weekdays' => '13:00 - 16:00, 20:00 - 23:00',
        'saturday' => '13:00 - 16:00, 20:00 - 23:00',
        'sunday' => '13:00 - 16:00'
    ],
    'description' => 'Auténtica cocina canaria en el corazón de Tenerife',
    'capacity' => 80,
    'established' => 2004
];
