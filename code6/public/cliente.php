<?php
/**
 * cliente.php
 * Cliente SOAP para probar el servicio web de la tienda
 * Realiza llamadas a las funciones getPVP y getStock
 */

// Deshabilitar cache de WSDL para desarrollo
ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 0);

// URL del servicio SOAP
$wsdlUrl = 'http://172.16.80.128/code/public/servicio.php?wsdl';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cliente SOAP - Tienda Online</title>
    <style>
        body { font-family: Arial; max-width: 1000px; margin: 30px auto; padding: 20px; background: #f5f5f5; }
        h1 { color: #2E75B5; text-align: center; }
        h2 { color: #2E75B5; border-bottom: 2px solid #2E75B5; padding-bottom: 10px; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .test-section { margin: 30px 0; padding: 20px; background: #f9f9f9; border-left: 4px solid #2E75B5; }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .result { background: #e7f3ff; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .xml-box { background: #f5f5f5; padding: 15px; border: 1px solid #ddd; border-radius: 5px; 
                   overflow-x: auto; max-height: 300px; overflow-y: auto; }
        pre { margin: 0; font-size: 12px; white-space: pre-wrap; word-wrap: break-word; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th { background: #2E75B5; color: white; padding: 10px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #ddd; }
        tr:hover { background: #f5f5f5; }
        .timestamp { text-align: center; color: #666; margin-top: 20px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Cliente SOAP - Tienda Online</h1>
        <p style="text-align: center; color: #666;">
            Prueba del servicio web SOAP generado con php2wsdl
        </p>
        <hr>
        
<?php
try {
    // Crear el cliente SOAP
    $cliente = new SoapClient($wsdlUrl, [
        'cache_wsdl' => WSDL_CACHE_NONE,
        'trace' => true,
        'exceptions' => true
    ]);
    
    echo "<p class='success'>✓ Conexion establecida con el servicio SOAP</p>";
    echo "<p>URL del WSDL: <code>$wsdlUrl</code></p>";
    
    // ========================================
    // PRUEBA 1: Obtener PVP de productos
    // ========================================
    echo "<div class='test-section'>";
    echo "<h2>PRUEBA 1: Obtener PVP de productos</h2>";
    echo "<table>";
    echo "<tr><th>Codigo Producto</th><th>PVP (€)</th><th>Estado</th></tr>";
    
    $productos = [
        ['codigo' => 'PROD001', 'nombre' => 'Samsung Galaxy S24'],
        ['codigo' => 'PROD003', 'nombre' => 'Auriculares Sony'],
        ['codigo' => 'PROD999', 'nombre' => 'Producto inexistente']
    ];
    
    foreach ($productos as $prod) {
        $pvp = $cliente->getPVP($prod['codigo']);
        $estado = $pvp > 0 ? "<span class='success'>✓ OK</span>" : "<span class='error'>✗ No encontrado</span>";
        echo "<tr>";
        echo "<td>{$prod['codigo']} <small>({$prod['nombre']})</small></td>";
        echo "<td>" . number_format($pvp, 2) . " €</td>";
        echo "<td>$estado</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    echo "</div>";
    
    // ========================================
    // PRUEBA 2: Obtener Stock de productos
    // ========================================
    echo "<div class='test-section'>";
    echo "<h2>PRUEBA 2: Obtener Stock de productos</h2>";
    echo "<table>";
    echo "<tr><th>Producto</th><th>Tienda</th><th>Stock</th><th>Estado</th></tr>";
    
    $consultas = [
        ['producto' => 'PROD001', 'tienda' => 'TIENDA01', 'desc' => 'Samsung en Madrid'],
        ['producto' => 'PROD004', 'tienda' => 'TIENDA02', 'desc' => 'TV LG en Barcelona'],
        ['producto' => 'PROD002', 'tienda' => 'TIENDA03', 'desc' => 'HP Pavilion en Valencia'],
        ['producto' => 'PROD002', 'tienda' => 'TIENDA99', 'desc' => 'Tienda inexistente']
    ];
    
    foreach ($consultas as $cons) {
        $stock = $cliente->getStock($cons['producto'], $cons['tienda']);
        $estado = $stock > 0 ? "<span class='success'>✓ Disponible</span>" : 
                  ($cons['tienda'] == 'TIENDA99' ? "<span class='error'>✗ Error</span>" : 
                   "<span style='color:#ffc107'>⚠ Sin stock</span>");
        echo "<tr>";
        echo "<td>{$cons['producto']}</td>";
        echo "<td>{$cons['tienda']} <small>({$cons['desc']})</small></td>";
        echo "<td>$stock unidades</td>";
        echo "<td>$estado</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    echo "</div>";
    
    // ========================================
    // INFORMACION DE DEPURACION
    // ========================================
    echo "<div class='test-section'>";
    echo "<h2>INFORMACION DE DEPURACION</h2>";
    
    echo "<h3>Ultimo Request SOAP (XML enviado al servidor):</h3>";
    echo "<div class='xml-box'>";
    
    // Formatear XML para visualización
    $dom = new DOMDocument('1.0');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    
    if ($cliente->__getLastRequest()) {
        $dom->loadXML($cliente->__getLastRequest());
        echo "<pre>" . htmlspecialchars($dom->saveXML()) . "</pre>";
    } else {
        echo "No se ha realizado ninguna petición (Request).";
    }
    
    echo "</div>";
    
    echo "<h3>Ultimo Response SOAP (XML recibido del servidor):</h3>";
    echo "<div class='xml-box'>";

    if ($cliente->__getLastResponse()) {
        $dom->loadXML($cliente->__getLastResponse());
        echo "<pre>" . htmlspecialchars($dom->saveXML()) . "</pre>";
    } else {
        echo "No se ha recibido ninguna respuesta (Response).";
    }
    
    echo "</div>";
    
    echo "</div>";
    
    echo "<div class='timestamp'>";
    echo "Pruebas completadas el " . date('d/m/Y') . " a las " . date('H:i:s');
    echo "</div>";
    
} catch (SoapFault $e) {
    echo "<div class='error'>";
    echo "<h2>✗ Error SOAP</h2>";
    echo "<p><strong>Mensaje:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Codigo:</strong> " . htmlspecialchars($e->getCode()) . "</p>";
    echo "</div>";
} catch (Exception $e) {
    echo "<div class='error'>";
    echo "<h2>✗ Error general</h2>";
    echo "<p><strong>Mensaje:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}
?>
    </div>
</body>
</html>
