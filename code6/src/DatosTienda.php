<?php
/**
 * DatosTienda.php
 * Datos de una tienda online de electronica
 * Array asociativo con productos y stock por tienda
 */

// Array asociativo con los productos de la tienda
$productos = [
    'PROD001' => [
        'nombre' => 'Smartphone Samsung Galaxy S24',
        'descripcion' => 'Telefono movil 128GB Negro',
        'pvp' => 899.99,
        'categoria' => 'Telefonia'
    ],
    'PROD002' => [
        'nombre' => 'Portatil HP Pavilion 15',
        'descripcion' => 'Portatil 15.6 pulgadas, i5, 16GB RAM',
        'pvp' => 649.00,
        'categoria' => 'Informatica'
    ],
    'PROD003' => [
        'nombre' => 'Auriculares Sony WH-1000XM5',
        'descripcion' => 'Auriculares inalambricos con cancelacion de ruido',
        'pvp' => 349.99,
        'categoria' => 'Audio'
    ],
    'PROD004' => [
        'nombre' => 'Smart TV LG OLED 55"',
        'descripcion' => 'Televisor OLED 4K 55 pulgadas',
        'pvp' => 1299.00,
        'categoria' => 'Television'
    ],
    'PROD005' => [
        'nombre' => 'Tablet iPad Air',
        'descripcion' => 'Tablet Apple 10.9 pulgadas 256GB',
        'pvp' => 799.00,
        'categoria' => 'Tablets'
    ]
];

// Array asociativo con el stock por tienda
$stockTiendas = [
    'TIENDA01' => [
        'nombre' => 'Tienda Central Madrid',
        'direccion' => 'Calle Gran Via 45, Madrid',
        'stock' => [
            'PROD001' => 15,
            'PROD002' => 8,
            'PROD003' => 22,
            'PROD004' => 5,
            'PROD005' => 12
        ]
    ],
    'TIENDA02' => [
        'nombre' => 'Tienda Barcelona Centro',
        'direccion' => 'Paseo de Gracia 78, Barcelona',
        'stock' => [
            'PROD001' => 10,
            'PROD002' => 12,
            'PROD003' => 18,
            'PROD004' => 3,
            'PROD005' => 9
        ]
    ],
    'TIENDA03' => [
        'nombre' => 'Tienda Valencia',
        'direccion' => 'Calle Colon 32, Valencia',
        'stock' => [
            'PROD001' => 7,
            'PROD002' => 5,
            'PROD003' => 14,
            'PROD004' => 2,
            'PROD005' => 6
        ]
    ]
];
