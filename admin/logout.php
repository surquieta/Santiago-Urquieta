<?php
/**
 * Logout - Cerrar sesión del panel administrativo
 * Santiago Urquieta - Sitio Web Profesional
 */

session_start();
session_destroy();

header('Location: login.php');
exit;
?>
