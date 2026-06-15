-- =====================================================
-- BASE DE DATOS: Santiago Urquieta - Sitio Web Profesional
-- =====================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `santiago_urquieta` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `santiago_urquieta`;

-- =====================================================
-- TABLA: usuarios (Administradores)
-- =====================================================
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `rol` enum('admin','editor') DEFAULT 'admin',
  `activo` tinyint(1) DEFAULT 1,
  `ultimo_acceso` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `activo` (`activo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Usuario administrador por defecto
-- Email: admin@santiagourquieta.com
-- Password: Admin123! (cifrado con bcrypt)
INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`, `activo`) VALUES
(1, 'Santiago Urquieta', 'admin@santiagourquieta.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1);

-- =====================================================
-- TABLA: categorias (Categorías del Blog)
-- =====================================================
CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `orden` int(11) DEFAULT 0,
  `activo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `activo` (`activo`),
  KEY `orden` (`orden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Categorías iniciales
INSERT INTO `categorias` (`id`, `nombre`, `slug`, `descripcion`, `orden`, `activo`) VALUES
(1, 'Inteligencia Artificial', 'inteligencia-artificial', 'Artículos sobre IA, machine learning y automatización', 1, 1),
(2, 'Ciberseguridad', 'ciberseguridad', 'Seguridad informática, protección de datos y hacking ético', 2, 1),
(3, 'Marketing Digital', 'marketing-digital', 'Estrategias de marketing online y crecimiento digital', 3, 1),
(4, 'Publicidad', 'publicidad', 'Publicidad online, Google Ads, Facebook Ads y más', 4, 1),
(5, 'Desarrollo Web', 'desarrollo-web', 'Tecnologías web, frameworks y mejores prácticas', 5, 1),
(6, 'Tecnología', 'tecnologia', 'Novedades tecnológicas y tendencias digitales', 6, 1);

-- =====================================================
-- TABLA: articulos (Artículos del Blog)
-- =====================================================
CREATE TABLE `articulos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `extracto` text DEFAULT NULL,
  `contenido` longtext NOT NULL,
  `imagen_destacada` varchar(255) DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `autor_id` int(11) DEFAULT NULL,
  `meta_titulo` varchar(255) DEFAULT NULL,
  `meta_descripcion` text DEFAULT NULL,
  `meta_keywords` varchar(500) DEFAULT NULL,
  `og_image` varchar(255) DEFAULT NULL,
  `vistas` int(11) DEFAULT 0,
  `destacado` tinyint(1) DEFAULT 0,
  `activo` tinyint(1) DEFAULT 1,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `categoria_id` (`categoria_id`),
  KEY `autor_id` (`autor_id`),
  KEY `activo` (`activo`),
  KEY `destacado` (`destacado`),
  KEY `published_at` (`published_at`),
  CONSTRAINT `articulos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL,
  CONSTRAINT `articulos_ibfk_2` FOREIGN KEY (`autor_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: etiquetas (Tags para artículos)
-- =====================================================
CREATE TABLE `etiquetas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: articulo_etiqueta (Relación muchos-a-muchos)
-- =====================================================
CREATE TABLE `articulo_etiqueta` (
  `articulo_id` int(11) NOT NULL,
  `etiqueta_id` int(11) NOT NULL,
  PRIMARY KEY (`articulo_id`, `etiqueta_id`),
  KEY `etiqueta_id` (`etiqueta_id`),
  CONSTRAINT `articulo_etiqueta_ibfk_1` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `articulo_etiqueta_ibfk_2` FOREIGN KEY (`etiqueta_id`) REFERENCES `etiquetas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: imagenes (Biblioteca multimedia)
-- =====================================================
CREATE TABLE `imagenes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `archivo` varchar(255) NOT NULL,
  `ruta` varchar(500) NOT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `tamano` int(11) DEFAULT NULL,
  `ancho` int(11) DEFAULT NULL,
  `alto` int(11) DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `imagenes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: portafolio (Proyectos)
-- =====================================================
CREATE TABLE `portafolio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `contenido` longtext DEFAULT NULL,
  `imagen` varchar(255) NOT NULL,
  `tecnologias` varchar(500) DEFAULT NULL,
  `url_proyecto` varchar(500) DEFAULT NULL,
  `url_demo` varchar(500) DEFAULT NULL,
  `orden` int(11) DEFAULT 0,
  `destacado` tinyint(1) DEFAULT 0,
  `activo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `activo` (`activo`),
  KEY `destacado` (`destacado`),
  KEY `orden` (`orden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: visitas (Estadísticas de visitas)
-- =====================================================
CREATE TABLE `visitas` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tipo` enum('articulo','portafolio','pagina') DEFAULT 'articulo',
  `elemento_id` int(11) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `referer` varchar(500) DEFAULT NULL,
  `pais` varchar(50) DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `visited_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tipo_elemento` (`tipo`, `elemento_id`),
  KEY `visited_at` (`visited_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: configuracion (Configuración del sitio)
-- =====================================================
CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clave` varchar(100) NOT NULL,
  `valor` text DEFAULT NULL,
  `tipo` enum('texto','numero','booleano','json') DEFAULT 'texto',
  `descripcion` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clave` (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Configuración inicial
INSERT INTO `configuracion` (`clave`, `valor`, `tipo`, `descripcion`) VALUES
('site_title', 'Santiago Urquieta - Ingeniero en Informática', 'texto', 'Título del sitio para SEO'),
('site_description', 'Ingeniero en Informática especializado en Inteligencia Artificial, Ciberseguridad y Marketing Digital', 'texto', 'Descripción del sitio'),
('site_keywords', 'ingeniero informatica, inteligencia artificial, ciberseguridad, marketing digital, desarrollo web', 'texto', 'Palabras clave del sitio'),
('google_analytics', '', 'texto', 'Código de Google Analytics'),
('facebook_pixel', '', 'texto', 'Código de Facebook Pixel'),
('contact_email', 'contacto@santiagourquieta.com', 'texto', 'Email de contacto'),
('telefono', '', 'texto', 'Teléfono de contacto'),
('whatsapp', '', 'texto', 'Número de WhatsApp'),
('linkedin', '', 'texto', 'URL de LinkedIn'),
('github', '', 'texto', 'URL de GitHub'),
('twitter', '', 'texto', 'URL de Twitter/X'),
('instagram', '', 'texto', 'URL de Instagram'),
('slides_home', '5', 'numero', 'Número de slides en el home'),
('posts_por_pagina', '10', 'numero', 'Posts por página en el blog');

-- =====================================================
-- TABLA: contactos (Mensajes del formulario)
-- =====================================================
CREATE TABLE `contactos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `asunto` varchar(255) DEFAULT NULL,
  `mensaje` text NOT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `leido` tinyint(1) DEFAULT 0,
  `respondido` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `leido` (`leido`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;
