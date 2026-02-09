<?php
session_start();

if (!isset($_SESSION['contactos'])) {
    $_SESSION['contactos'] = array();
}

$contactos = $_SESSION['contactos'];
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
        <h1>Contactos registrados</h1>
        
        <?php if (count($contactos) > 0): ?>
            <div class="aviso-ok">
                Total de contactos: <strong><?php echo count($contactos); ?></strong>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Nº</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Fecha de registro</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contactos as $index => $contacto): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo $contacto['nombre']; ?></td>
                        <td><?php echo $contacto['email']; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($contacto['fecha'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="aviso-mal">
                Todavía no hay contactos registrados
            </div>
        <?php endif; ?>
        
        <br>
        <a href="registro.php" class="boton">Añadir más contactos</a>
        <a href="index.php" class="boton boton-gris">Volver al inicio</a>
    </div>
</body>
</html>