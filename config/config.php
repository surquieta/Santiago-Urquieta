<?php
/**
 * Configuración General del Sitio
 * Santiago Urquieta - Sitio Web Profesional
 */

return [
    'site_name' => 'Santiago Urquieta',
    'site_subtitle' => 'Ingeniero en Informática | Inteligencia Artificial | Ciberseguridad | Marketing Digital',
    'site_url' => 'http://localhost/santiago-urquieta',
    'admin_url' => 'http://localhost/santiago-urquieta/admin',
    'timezone' => 'America/Santiago',
    'locale' => 'es_ES',
    'uploads_dir' => __DIR__ . '/../uploads/',
    'uploads_url' => '/santiago-urquieta/uploads/',
    'max_upload_size' => 5242880, // 5MB
    'allowed_extensions' => ['jpg', 'jpeg', 'png', 'webp', 'gif'],
    'session_timeout' => 3600, // 1 hora
    'smtp' => [
        'host' => '',
        'port' => 587,
        'username' => '',
        'password' => '',
        'from_email' => '',
        'from_name' => 'Santiago Urquieta'
    ]
];
