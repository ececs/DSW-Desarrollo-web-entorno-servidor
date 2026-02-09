<?php 
session_start(); 

// Agregar registros automáticamente si no existen
if (!isset($_SESSION['contactos']) || count($_SESSION['contactos']) == 0) {
    $_SESSION['contactos'] = array();
    
    // Lista de 10 contactos con nombres y emails variados
    $registros_iniciales = array(
        array('nombre' => 'Ana García López', 'email' => 'ana.garcia@empresa.com', 'horas' => 48),
        array('nombre' => 'Carlos Martínez Ruiz', 'email' => 'carlos.martinez@correo.es', 'horas' => 36),
        array('nombre' => 'María Fernández Silva', 'email' => 'maria.fernandez@mail.com', 'horas' => 24),
        array('nombre' => 'José Luis Sánchez', 'email' => 'joseluis.sanchez@gmail.com', 'horas' => 72),
        array('nombre' => 'Laura Rodríguez Pérez', 'email' => 'laura.rodriguez@hotmail.com', 'horas' => 12),
        array('nombre' => 'Miguel Ángel Torres', 'email' => 'miguelangel.torres@yahoo.es', 'horas' => 96),
        array('nombre' => 'Isabel Jiménez Moreno', 'email' => 'isabel.jimenez@outlook.com', 'horas' => 6),
        array('nombre' => 'Francisco Díaz Castro', 'email' => 'francisco.diaz@corporativo.es', 'horas' => 120),
        array('nombre' => 'Carmen López Vega', 'email' => 'carmen.lopez@empresa.net', 'horas' => 18),
        array('nombre' => 'Antonio Romero Gil', 'email' => 'antonio.romero@contact.com', 'horas' => 60)
    );
    
    // Insertar cada registro con fecha y hora diferentes
    foreach ($registros_iniciales as $reg) {
        $fecha_base = time() - ($reg['horas'] * 3600); // Restar horas desde ahora
        $_SESSION['contactos'][] = array(
            'nombre' => $reg['nombre'],
            'email' => $reg['email'],
            'fecha' => date('Y-m-d H:i:s', $fecha_base)
        );
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Contactos</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="contenedor">
        <h1>Gestor de Contactos por Email</h1>
        
        <p>Esta es una aplicación web sencilla para gestionar una lista de contactos mediante sus direcciones de correo electrónico.</p>
        
        <div class="info-box">
            <h2>Funcionalidades principales:</h2>
            <ul>
                <li>Registro de contactos con nombre y email</li>
                <li>Validación automática de direcciones de correo</li>
                <li>Control de duplicados en la base de datos</li>
                <li>Listado completo de todos los registros</li>
            </ul>
        </div>
        
        <h2>Como usar la aplicacion</h2>
        <p>Es muy simple: pulsa el botón de abajo para acceder al formulario de registro. Ahí introduces tu nombre y tu dirección de correo. El sistema comprobará que el formato del email es correcto y que no está ya registrado. Después de guardar los datos, podrás ver el listado completo.</p>
        
        <a href="registro.php" class="boton">Ir al formulario</a>
    </div>
</body>
</html>