<?php
// Fichero de configuración para la conexión con la base de datos.
// Define las constantes necesarias y establece la conexión PDO.

// **CONFIGURACIÓN DE LA BASE DE DATOS**
// Defino los parámetros de conexión.
define('DB_HOST', 'localhost'); // El servidor.
define('DB_NAME', 'comunidad');   // El nombre de la base de datos.
define('DB_USER', 'eudaldo');     // El nombre de usuario.
define('DB_PASS', 'Bbdd@1234'); // La contraseña del usuario de la base de datos.

// **OPCIONES DE CONEXIÓN PDO**
// Estas opciones personalizan el comportamiento de PDO al conectarse y manejar errores.
$options = [
    // Configura el modo de error de PDO para que lance excepciones.
    // Permite capturar y manejar los errores de la base de datos.
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,

    // Establezco el modo de obtención de resultados por defecto a un array asociativo.
    // Cuando se realiza una consulta, los resultados se devuelven como un array donde las claves son los nombres de las columnas.
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

    // Desactivo la emulación de sentencias preparadas.
    // Se desactiva para usar las sentencias preparadas nativas del motor de la base de datos.
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// **ESTABLECIMIENTO DE LA CONEXIÓN**
// Intento establecer la conexión para crear una nueva instancia de PDO para conectar con la base de datos.
try {
    // Se crea el objeto PDO con los parámetros de conexión y las opciones definidas.
    // El DSN especifica el tipo de base de datos, el host, el nombre de la base de datos y el juego de caracteres.
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // **MANEJO DE ERRORES DE CONEXIÓN**
    // Si la conexión falla, capturo la excepción PDO y muestro un mensaje de error.
    die("Error de conexión: No se pudo conectar a la base de datos. " . $e->getMessage());
}
?>