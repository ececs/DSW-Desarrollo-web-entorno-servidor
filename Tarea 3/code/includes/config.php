<?php
// Configuración de la aplicación

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Definición de roles de usuario
define('ROL_VECINO', 'vecino');
define('ROL_PRESIDENTE', 'presidente');
define('ROL_ADMINISTRADOR', 'administrador');

// Día del mes en que se cobran las cuotas
define('DIA_COBRO', 1);

// Verifica si hay una sesión de usuario activa, si no, redirige al login
function verificarSesion() {
    if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol'])) {
        // Redirigir a la página de login, ajustando la ruta si es necesario
        $login_path = (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'code/login.php' : 'login.php';
        if (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false || strpos($_SERVER['REQUEST_URI'], '/presidente/') !== false) {
            $login_path = '../login.php';
        }
        header("Location: $login_path");
        exit();
    }
}

// Obtiene el nombre del usuario de la sesión
function obtenerNombreUsuario() {
    return $_SESSION['nombre'] ?? 'Usuario';
}

// Obtiene el rol del usuario de la sesión
function obtenerRolUsuario() {
    return $_SESSION['rol'] ?? '';
}
?>