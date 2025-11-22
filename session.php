<?php
/**
 * Verificar sesión de usuario
 * Incluye este archivo en las páginas que requieren autenticación
 */
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Si no está logueado, redirigir al login
    header('Location: login.php');
    exit;
}

// Función para cerrar sesión
function cerrarSesion() {
    session_start();
    $_SESSION = array();
    session_destroy();
    header('Location: login.php');
    exit;
}
?>

