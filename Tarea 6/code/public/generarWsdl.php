<?php
/**
 * generarWsdl.php
 * Script para generar el archivo WSDL usando php2wsdl
 * Utiliza la clase Operaciones definida en src/Operaciones.php
 */

// Incluir el autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Incluir la clase Operaciones
require_once __DIR__ . '/../src/Operaciones.php';

// Importar la clase php2wsdl
use PHP2WSDL\PHPClass2WSDL;

echo "<!DOCTYPE html>
<html>
<head>
    <title>Generador WSDL</title>
    <style>
        body { font-family: Arial; max-width: 800px; margin: 50px auto; padding: 20px; }
        h1 { color: #2E75B5; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px; }
        .info { background: #d1ecf1; border: 1px solid #bee5eb; padding: 15px; border-radius: 5px; margin: 20px 0; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; max-height: 400px; overflow-y: auto; white-space: pre-wrap; word-wrap: break-word; }
        a { color: #2E75B5; text-decoration: none; font-weight: bold; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>Generador WSDL con php2wsdl</h1>";

try {
    // Configurar la URL del servicio (CAMBIA localhost por tu dominio si es necesario)
    $serviceUrl = 'http://172.16.80.128/code/public/servicio.php';
    
    echo "<div class='info'>
        <strong>Configuracion:</strong><br>
        Clase: Operaciones<br>
        URL del servicio: $serviceUrl
    </div>";
    
    // Crear el generador WSDL
    $wsdlGenerator = new PHPClass2WSDL(
        'Operaciones',      // Nombre de la clase
        $serviceUrl,        // URL del servicio
        true                // Usar documentacion de PHPDoc
    );
    
    echo "<p>✓ Generador creado...</p>";
    
    // Generar el WSDL
    $wsdlGenerator->generateWSDL(true);
    echo "<p>✓ WSDL generado...</p>";
    
    // Obtener el contenido del WSDL
    $wsdlContent = $wsdlGenerator->dump();
    echo "<p>✓ Contenido obtenido...</p>";
    
    // Guardar en archivo de forma atómica para evitar problemas de caché/lectura parcial.
    $outputFile = __DIR__ . '/servicio.wsdl';
    $tmpFile = $outputFile . '.tmp.' . uniqid(); // Archivo temporal único

    $bytes = file_put_contents($tmpFile, $wsdlContent);
    if ($bytes === false) {
        throw new Exception("Error: No se pudo escribir en el archivo WSDL temporal ('$tmpFile'). Verifique los permisos del directorio.");
    }
    
    // Si la escritura temporal fue exitosa, renombramos el archivo.
    if (!rename($tmpFile, $outputFile)) {
        @unlink($tmpFile); // Intentar borrar el temporal si el renombrado falla
        throw new Exception("Error: No se pudo renombrar el archivo temporal a 'servicio.wsdl'. Verifique los permisos del directorio.");
    }
    
    echo "<div class='success'>
        <h2>✓ WSDL generado correctamente</h2>
        <p><strong>Archivo:</strong> $outputFile</p>
        <p><strong>Tamano:</strong> $bytes bytes</p>
        <p><strong>Metodos incluidos:</strong> getPVP, getStock</p>
    </div>";
    
    echo "<div class='info'>
        <h3>Proximos pasos:</h3>
        <ol>
            <li>Verifica el WSDL: <a href='servicio.php?wsdl' target='_blank'>Ver WSDL</a></li>
            <li>Prueba el servicio: <a href='cliente.php' target='_blank'>Ejecutar cliente</a></li>
            <li>Haz una captura de pantalla de esta pagina para tu memoria</li>
        </ol>
    </div>";
    
    echo "<h3>Vista previa del WSDL generado:</h3>";
    
    // Formatear el WSDL para una vista previa legible
    $dom = new DOMDocument('1.0');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($wsdlContent);
    $formattedWsdl = $dom->saveXML();

    echo "<pre>" . htmlspecialchars($formattedWsdl) . "</pre>";
    echo "<p><small><i>El archivo completo ha sido guardado en <a href='servicio.wsdl' target='_blank'>servicio.wsdl</a>.</i></small></p>";
    
} catch (Exception $e) {
    echo "<div class='error'>
        <h2>✗ Error al generar el WSDL</h2>
        <p><strong>Mensaje:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
        <h3>Posibles soluciones:</h3>
        <ul>
            <li>Verifica que ejecutaste: composer install</li>
            <li>Verifica que la carpeta vendor/ existe</li>
            <li>Verifica que el archivo src/Operaciones.php existe</li>
            <li>Verifica que los metodos tienen la anotacion @soap</li>
        </ul>
    </div>";
}

echo "</body></html>";
