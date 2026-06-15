<?php
/**
 * Configuración de la Base de Datos
 * Santiago Urquieta - Sitio Web Profesional
 */

return [
    'host' => 'localhost',
    'dbname' => 'santiago_urquieta',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
