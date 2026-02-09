<?php
/**
 * servicio.php
 * Este es el punto de entrada del servidor SOAP.
 * Ofrece las operaciones de la tienda online.
 */

require_once __DIR__ . '/../src/Operaciones.php';

// Si me piden el WSDL, se lo devuelvo.
if (isset($_GET['wsdl'])) {
    $wsdlFile = __DIR__ . '/servicio.wsdl';

    // Compruebo si el WSDL existe y se puede leer.
    if (!file_exists($wsdlFile)) {
        header('HTTP/1.1 404 Not Found');
        die("Error 404: No encuentro 'servicio.wsdl'. Lanza primero 'generarWsdl.php'.");
    }
    if (!is_readable($wsdlFile)) {
        header('HTTP/1.1 500 Internal Server Error');
        die("Error 500: No puedo leer 'servicio.wsdl'. Revisa los permisos.");
    }

    // Limpio el buffer por si acaso.
    if (ob_get_level() > 0) {
        ob_end_clean();
    }

    // Devuelvo el WSDL como un XML.
    header('Content-Type: text/xml; charset=utf-8');
    header('Content-Length: ' . filesize($wsdlFile));
    
    $bytesRead = readfile($wsdlFile);

    // Por si falla readfile, que quede registrado.
    if ($bytesRead === false) {
        error_log("Error al leer el WSDL en 'servicio.php': " . $wsdlFile);
    }
    
    exit;
}

// Si es una petición GET normal (sin ?wsdl), muestro una página de ayuda.
if ($_SERVER['REQUEST_METHOD'] === 'GET' && empty($_POST)) {
    header('Content-Type: text/html; charset=utf-8');
    echo <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Punto de Entrada del Servicio SOAP</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 40px auto; padding: 20px; line-height: 1.6; color: #333; }
        h1 { color: #2E75B5; border-bottom: 2px solid #2E75B5; padding-bottom: 10px; }
        .info-box { background-color: #e7f3fe; border-left: 6px solid #2196F3; margin: 20px 0; padding: 15px 20px; }
        code { background: #f4f4f4; padding: 2px 5px; border-radius: 4px; }
        a { color: #2E75B5; text-decoration: none; font-weight: bold; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>Servicio Web SOAP</h1>
    <div class="info-box">
        <p>Esta URL es el punto de entrada de un servicio web que utiliza el protocolo SOAP.</p>
        <p>No está diseñada para ser usada directamente desde un navegador, sino a través de un cliente SOAP.</p>
    </div>
    <h2>¿Qué puedes hacer?</h2>
    <ul>
        <li>
            <p>
                <strong>Ver la definición del servicio (WSDL):</strong> Para entender qué operaciones ofrece este servicio, puedes consultar su archivo WSDL.
            </p>
            <p>
                <a href="?wsdl">Ver el archivo servicio.wsdl</a>
            </p>
        </li>
        <li>
            <p>
                <strong>Probar el servicio:</strong> Puedes usar el cliente de prueba para interactuar con este servicio.
            </p>
            <p>
                <a href="cliente.php">Ejecutar cliente.php</a>
            </p>
        </li>
    </ul>
</body>
</html>
HTML;
    exit;
}


// Configuración para depurar, que no se cachee el WSDL.
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 0);

try {
    // Creo el servidor SOAP a partir del WSDL.
    $wsdlFile = __DIR__ . '/servicio.wsdl';
    
    if (!file_exists($wsdlFile)) {
        // Si no hay WSDL, el cliente SOAP recibirá este error.
        throw new SoapFault('Server', 'Error interno: El WSDL no está disponible. Contacta al administrador.');
    }
    
    $server = new SoapServer(
        $wsdlFile,
        [
            'cache_wsdl' => WSDL_CACHE_NONE,
            'trace' => true,
            'exceptions' => true
        ]
    );
    
    // Le digo al servidor qué clase tiene los métodos a exponer.
    $server->setClass('Operaciones');
    
    // Atiendo la petición SOAP (normalmente viene por POST).
    $server->handle();
    
} catch (SoapFault $e) {
    // Si algo va mal, devuelvo el error SOAP al cliente.
    header('HTTP/1.1 500 Internal Server Error');
    error_log($e);
    // Para depurar, se podría mostrar el error, pero en producción mejor no.
    // echo "Error SOAP: " . $e->getMessage();
} catch (Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
    error_log($e);
}