<?php
// Conexión a la base de datos
// Para no tener que escribirla en todos los archivos

// --- DATOS DE CONEXIÓN ---
// Mis datos para entrar a la base de datos
define('DB_HOST', 'localhost'); // El servidor, casi siempre localhost
define('DB_NAME', 'comunidad');   // El nombre de la BBDD que he creado
define('DB_USER', 'eudaldo');     // Mi usuario de la BBDD
define('DB_PASS', 'Bbdd@1234'); // La contraseña de mi usuario

// --- OPCIONES DE PDO ---
// Algunas opciones para que PDO funcione como quiero
$options = [
    // Para que PDO me diga los errores y no se quede callado
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,

    // Para que cuando haga una consulta, me devuelva un array con los nombres de las columnas
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

    // Para que no simule las sentencias preparadas, que use las de verdad de MySQL
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// --- CONECTAR ---
// Intento conectar a la base de datos con los datos de arriba
try {
    // Aquí se crea el objeto PDO que usaré en las otras páginas
    // Le paso el tipo de BBDD, el servidor, el nombre de la BBDD y el juego de caracteres
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // --- MANEJO DE ERRORES ---
    // Si algo va mal al conectar, se para todo y me dice el error
    die("Error de conexión: No se pudo conectar a la base de datos. " . $e->getMessage());
}
?>