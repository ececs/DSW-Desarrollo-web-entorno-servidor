<?php
/**
 * Página principal protegida.
 * Comprueba si hay una sesión activa y redirige al login si no la hay.
 * Muestra el contenido según el rol del usuario almacenado en la sesión.
 */
session_start();

// Si el usuario no ha iniciado sesión, se le redirige a la página de login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php?redirigido=true");
    exit();
}

// Incluir las funciones de gestión de datos y vistas
require_once 'funciones.php';

// Gestionar las peticiones POST para actualizar/añadir/borrar vecinos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion'])) {
    if ($_SESSION['rol'] === 'administrador') {
        if ($_POST['accion'] === 'alta') {
            dar_alta_vecino($_POST);
        } elseif ($_POST['accion'] === 'borrar') {
            dar_baja_vecino($_POST['usuario']);
        }
    }
    if ($_SESSION['rol'] === 'presidente') {
        if ($_POST['accion'] === 'modificar_cuotas') {
            modificar_cuotas($_POST['usuario'], $_POST['cuotas_pagadas']);
        }
    }
    // Redirigir para evitar que el formulario se reenvíe al recargar la página
    header("Location: principal.php");
    exit();
}

$vecinos = leer_vecinos();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Página Principal - Comunidad</title>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .welcome { font-size: 1.2em; margin-bottom: 20px; }
        .logout { float: right; }
        .error { color: red; }
        .alta-form { margin-top: 30px; border: 1px solid #ddd; padding: 15px; }
    </style>
</head>
<body>
    <a class="logout" href="logout.php">Salir</a>
    <div class="welcome">
        <?php echo "Bienvenido, <strong>" . htmlspecialchars($_SESSION['nombre']) . "</strong> (" . htmlspecialchars($_SESSION['rol']) . ")"; ?>
    </div>

    <hr>

    <?php
    // Mostrar contenido diferente según el rol del usuario
    switch ($_SESSION['rol']) {
        case 'vecino':
            mostrar_vista_vecino($vecinos[$_SESSION['usuario']]);
            break;
        case 'presidente':
            mostrar_vista_presidente($vecinos);
            break;
        case 'administrador':
            mostrar_vista_administrador($vecinos);
            break;
    }
    ?>
</body>
</html>