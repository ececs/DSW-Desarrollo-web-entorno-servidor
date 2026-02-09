<?php
/**
 * Script para generar datos iniciales del sistema
 * EJECUTAR SOLO UNA VEZ para crear el archivo vecinos.dat con datos de prueba
 */

// Crear directorio data si no existe
if (!is_dir('data')) {
    mkdir('data', 0755, true);
}

// Definir vecinos de prueba (Personajes de La que se avecina)
$vecinos = [
    [
        'nombre' => 'Antonio Recio Matamoros',
        'dni' => '12345678A',
        'telefono' => '612345678',
        'email' => 'recio.pescaderia@mirador.com',
        'bloque' => 'A',
        'piso' => '1',
        'letra' => 'A',
        'fecha_alta' => '2024-01-15',
        'cuotas_pagadas' => 12,
        'fecha_ultima_cuota' => '2024-10-01',
        'password' => 'pescado123', // Contraseña sin hashear
        'rol' => 'vecino'
    ],
    [
        'nombre' => 'Enrique Pastor Bravo',
        'dni' => '87654321B',
        'telefono' => '698765432',
        'email' => 'enrique.pastor@mirador.com',
        'bloque' => 'A',
        'piso' => '2',
        'letra' => 'A',
        'fecha_alta' => '2023-06-20',
        'cuotas_pagadas' => 18,
        'fecha_ultima_cuota' => '2024-09-15',
        'password' => 'presidente123',
        'rol' => 'presidente'
    ],
    [
        'nombre' => 'José Luis Torrente Recio',
        'dni' => '11223344C',
        'telefono' => '655443322',
        'email' => 'coque.recio@mirador.com',
        'bloque' => 'A',
        'piso' => '1',
        'letra' => 'B',
        'fecha_alta' => '2024-03-10',
        'cuotas_pagadas' => 8,
        'fecha_ultima_cuota' => '2024-08-30',
        'password' => 'coque123',
        'rol' => 'vecino'
    ],
    [
        'nombre' => 'Javier Maroto García',
        'dni' => '44332211D',
        'telefono' => '677889900',
        'email' => '',
        'bloque' => 'A',
        'piso' => '3',
        'letra' => 'A',
        'fecha_alta' => '2023-12-05',
        'cuotas_pagadas' => 10,
        'fecha_ultima_cuota' => '2024-07-20',
        'password' => 'maroto123',
        'rol' => 'vecino'
    ],
    [
        'nombre' => 'Vicente Maroto García',
        'dni' => '99887766E',
        'telefono' => '600000000',
        'email' => 'vicente.maroto@mirador.com',
        'bloque' => 'B',
        'piso' => '1',
        'letra' => 'A',
        'fecha_alta' => '2023-01-01',
        'cuotas_pagadas' => 24,
        'fecha_ultima_cuota' => '2024-10-01',
        'password' => 'admin123',
        'rol' => 'administrador'
    ],
    [
        'nombre' => 'Amador Rivas Pachón',
        'dni' => '55667788F',
        'telefono' => '644556677',
        'email' => 'amador.rivas@mirador.com',
        'bloque' => 'A',
        'piso' => '2',
        'letra' => 'B',
        'fecha_alta' => '2024-02-10',
        'cuotas_pagadas' => 9,
        'fecha_ultima_cuota' => '2024-09-01',
        'password' => 'amador123',
        'rol' => 'vecino'
    ],
    [
        'nombre' => 'Fermín Trujillo Roales',
        'dni' => '66778899G',
        'telefono' => '633445566',
        'email' => 'fermin.trujillo@mirador.com',
        'bloque' => 'B',
        'piso' => '2',
        'letra' => 'A',
        'fecha_alta' => '2023-08-15',
        'cuotas_pagadas' => 15,
        'fecha_ultima_cuota' => '2024-09-30',
        'password' => 'fermin123',
        'rol' => 'vecino'
    ],
    [
        'nombre' => 'Araceli Madariaga de Torrente',
        'dni' => '22334455H',
        'telefono' => '622334455',
        'email' => 'araceli.madariaga@mirador.com',
        'bloque' => 'A',
        'piso' => '1',
        'letra' => 'C',
        'fecha_alta' => '2023-11-20',
        'cuotas_pagadas' => 11,
        'fecha_ultima_cuota' => '2024-09-01',
        'password' => 'araceli123',
        'rol' => 'vecino'
    ],
    [
        'nombre' => 'Maite Figueroa Navas',
        'dni' => '33445566I',
        'telefono' => '633445566',
        'email' => 'maite.figueroa@mirador.com',
        'bloque' => 'A',
        'piso' => '3',
        'letra' => 'B',
        'fecha_alta' => '2024-01-05',
        'cuotas_pagadas' => 10,
        'fecha_ultima_cuota' => '2024-10-01',
        'password' => 'maite123',
        'rol' => 'vecino'
    ]
];

// Generar archivo con contraseñas hasheadas
$lineas = [];
foreach ($vecinos as $vecino) {
    $passwordHash = password_hash($vecino['password'], PASSWORD_DEFAULT);
    
    $linea = implode('|', [
        $vecino['nombre'],
        $vecino['dni'],
        $vecino['telefono'],
        $vecino['email'],
        $vecino['bloque'],
        $vecino['piso'],
        $vecino['letra'],
        $vecino['fecha_alta'],
        $vecino['cuotas_pagadas'],
        $vecino['fecha_ultima_cuota'],
        $passwordHash,
        $vecino['rol']
    ]);
    
    $lineas[] = $linea;
}

// Guardar archivo
$resultado = file_put_contents('data/vecinos.dat', implode("\n", $lineas));

if ($resultado !== false) {
    echo "✅ Archivo vecinos.dat creado correctamente en el directorio 'data/'<br><br>";
    echo "<strong>Usuarios de prueba creados:</strong><br><br>";
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
    echo "<tr style='background: #f0f0f0;'><th>Nombre</th><th>DNI</th><th>Contraseña</th><th>Rol</th></tr>";
    
    foreach ($vecinos as $vecino) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($vecino['nombre']) . "</td>";
        echo "<td>" . htmlspecialchars($vecino['dni']) . "</td>";
        echo "<td><strong>" . htmlspecialchars($vecino['password']) . "</strong></td>";
        echo "<td>" . htmlspecialchars($vecino['rol']) . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    echo "<br><br>";
    echo "<p style='color: #dc3545;'><strong>⚠️ IMPORTANTE:</strong> Elimine este archivo (generar_datos.php) después de ejecutarlo por seguridad.</p>";
    echo "<br><a href='index.php' style='padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px;'>Ir a la aplicación</a>";
} else {
    echo "❌ Error al crear el archivo vecinos.dat<br>";
    echo "Verifique que el directorio 'data/' tenga permisos de escritura.";
}
?>