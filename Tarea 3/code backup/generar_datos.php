<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

echo "<pre>";

// Script para cargar vecinos desde un string de datos (simulando el archivo vecinos.dat)

$datos_vecinos = <<<'DATA'
Antonio Recio Matamoros|12345678A|612345678|recio.pescaderia@mirador.com|A-1-A|2024-01-15|10|2025-09-11|$2y$10$yWOQfE6OxoMP2TTgZZ2U1eiMOWtJZCISLeQYl78X1/DvF6RquJ1E.|vecino
Enrique Pastor Bravo|87654321B|698765432|enrique.pastor@mirador.com|A-2-A|2023-06-20|18|2024-09-15|$2y$10$silAwHSBmlRKd2K8jQOQfe9dPFb2Gb6KJE2brXiRQaGzUyiOH5QjW|presidente
José Luis Torrente Recio|11223344C|655443322|coque.recio@mirador.com|A-1-B|2024-03-10|8|2024-08-30|$2y$10$wBKaArePKRpQPntg3JS0eudXCqwzXXe2ts6/HRSdUWpv5d7Egzyfu|vecino
Javier Maroto García|44332211D|677889900||A-3-A|2023-12-05|10|2024-07-20|$2y$10$LjFM/ixSfJlsXN8coz4ZLuWsQvITOmjkg0SZWPLDIXXTXuLh3zUMC|vecino
Vicente Maroto García|99887766E|600000000|vicente.maroto@mirador.com|B-1-A|2023-01-01|24|2024-10-01|$2y$10$NeJpKoN05MLD17AEp1Wm3O6CB.tq1q4yUWH6xAankEOYFGcRNQvQ6|administrador
Amador Rivas Pachón|55667788F|644556677|amador.rivas@mirador.com|A-2-B|2024-02-10|9|2024-09-01|$2y$10$fI6pr8qIgnAW2VmRMiPnR.J7MA6JKkgjeJgS0V2h53IiNUBOjb4Q6|vecino
Fermín Trujillo Roales|66778899G|633445566|fermin.trujillo@mirador.com|B-2-A|2023-08-15|15|2024-09-30|$2y$10$BibKh2gdlqZ7aeZO679zaOazg/hPBsL9My8b6a0RHEyx7jIxaOeaa|vecino
Araceli Madariaga de Torrente|22334455H|622334455|araceli.madariaga@mirador.com|A-1-C|2023-11-20|11|2024-09-01|$2y$10$IRxOKZYhgZl3RQu40Tu.juopk7qvbwYgaUFyGkWXfe.waw3EWNNY6|vecino
Maite Figueroa Navas|33445566I|633445566|maite.figueroa@mirador.com|A-3-B|2024-01-05|10|2024-10-01|$2y$10$epU/pIL//UC/CozJQ2J6gexh24RsEslQZznuxSGS2ZFtJQlMLsiJK|vecino
Eudaldo|46261744G|666750753|eudaldocal@gmail.com|A-5-B|2025-10-15|0|2025-10-15|$2y$10$nb57v.5EOIytzxozBa/fq.vLfygv5Zyejw7RLJNh.Aq7xdHRcBnhu|vecino
DATA;

$lineas = explode("\n", trim($datos_vecinos));

$sql = "INSERT INTO vecinos (nombre, apellidos, dni, telefono, email, fechaAlta, piso, bloque, letra, cuotasImpagadas, fechaUltimaCuota, nombreUsuario, passUsuario, rolUsuario) 
        VALUES (:nombre, :apellidos, :dni, :telefono, :email, :fechaAlta, :piso, :bloque, :letra, :cuotasImpagadas, :fechaUltimaCuota, :nombreUsuario, :passUsuario, :rolUsuario) 
        ON DUPLICATE KEY UPDATE 
        nombre=VALUES(nombre), apellidos=VALUES(apellidos), telefono=VALUES(telefono), email=VALUES(email), fechaAlta=VALUES(fechaAlta), piso=VALUES(piso), bloque=VALUES(bloque), letra=VALUES(letra), cuotasImpagadas=VALUES(cuotasImpagadas), fechaUltimaCuota=VALUES(fechaUltimaCuota), nombreUsuario=VALUES(nombreUsuario), passUsuario=VALUES(passUsuario), rolUsuario=VALUES(rolUsuario)";

try {
    $stmt = $pdo->prepare($sql);
    echo "Cargando datos de vecinos desde el archivo .dat...\n";

    foreach ($lineas as $linea) {
        if (empty(trim($linea))) continue;

        $datos = explode('|', $linea);

        // Separar nombre y apellidos
        $nombreCompleto = $datos[0];
        $partesNombre = explode(' ', $nombreCompleto, 2);
        $nombre = $partesNombre[0];
        $apellidos = $partesNombre[1] ?? '';

        // Separar vivienda
        $vivienda = explode('-', $datos[4]);
        $bloque = $vivienda[0] ?? '';
        $piso = $vivienda[1] ?? '';
        $letra = $vivienda[2] ?? '';

        $vecino = [
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'dni' => $datos[1],
            'telefono' => $datos[2],
            'email' => $datos[3],
            'fechaAlta' => $datos[5],
            'piso' => $piso,
            'bloque' => $bloque,
            'letra' => $letra,
            'cuotasImpagadas' => (int)$datos[6],
            'fechaUltimaCuota' => $datos[7],
            'nombreUsuario' => $datos[1], // DNI como nombre de usuario
            'passUsuario' => $datos[8], // La contraseña ya está hasheada
            'rolUsuario' => $datos[9]
        ];

        $stmt->execute($vecino);
        echo " - Vecino cargado/actualizado: " . htmlspecialchars($nombreCompleto) . "\n";
    }

    echo "\n¡Proceso completado!\n";

} catch (PDOException $e) {
    die("Error al cargar los datos: " . $e->getMessage());
}

echo "</pre>";
?>
<a href="login.php">Ir al login</a>