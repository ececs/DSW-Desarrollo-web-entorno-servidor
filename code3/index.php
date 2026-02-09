<?php
/**
 * Archivo de entrada principal
 * Redirige al login o al dashboard según el estado de la sesión
 */

session_start();

if (isset($_SESSION['usuario'])) {
    header('Location: dashboard.php');
} else {
    header('Location: login.php');
}
exit();
?>