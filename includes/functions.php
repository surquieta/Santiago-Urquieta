<?php
/**
 * Funciones de utilidad y helpers
 * Santiago Urquieta - Sitio Web Profesional
 */

/**
 * Generar slug amigable para URLs
 * @param string $string Texto original
 * @return string Slug generado
 */
function generateSlug($string) {
    // Convertir a minúsculas
    $string = strtolower($string);
    
    // Reemplazar caracteres especiales
    $replacements = [
        'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
        'ñ' => 'n', 'ü' => 'u',
        ' ' => '-',
        '_' => '-'
    ];
    
    $string = strtr($string, $replacements);
    
    // Eliminar caracteres no alfanuméricos
    $string = preg_replace('/[^a-z0-9\-]/', '', $string);
    
    // Eliminar guiones múltiples
    $string = preg_replace('/-+/', '-', $string);
    
    // Eliminar guiones al inicio y final
    $string = trim($string, '-');
    
    return $string;
}

/**
 * Sanitizar entrada de usuario (XSS Protection)
 * @param string $data Datos a sanitizar
 * @return string Datos sanitizados
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Limpiar HTML permitiendo tags seguros (para contenido de artículos)
 * @param string $html Contenido HTML
 * @return string HTML limpio
 */
function cleanHTML($html) {
    // Permitir solo tags seguros
    $allowed_tags = '<p><br><strong><em><u><h1><h2><h3><h4><h5><h6><ul><ol><li><a><img><table><thead><tbody><tr><td><th><blockquote><code><pre><hr><div><span>';
    return strip_tags($html, $allowed_tags);
}

/**
 * Generar token CSRF
 * @return string Token CSRF
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verificar token CSRF
 * @param string $token Token a verificar
 * @return bool True si es válido, false si no
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Redireccionar a una URL
 * @param string $url URL de destino
 */
function redirect($url) {
    header("Location: {$url}");
    exit;
}

/**
 * Obtener configuración del sitio
 * @param string|null $key Clave específica o null para todas
 * @return mixed Valor de configuración
 */
function getConfig($key = null) {
    static $config = null;
    
    if ($config === null) {
        $config = require __DIR__ . '/../config/config.php';
    }
    
    if ($key === null) {
        return $config;
    }
    
    return isset($config[$key]) ? $config[$key] : null;
}

/**
 * Formatear fecha
 * @param string $date Fecha en formato MySQL
 * @param string $format Formato de salida
 * @return string Fecha formateada
 */
function formatDate($date, $format = 'd/m/Y') {
    if (empty($date)) return '';
    return date($format, strtotime($date));
}

/**
 * Formatear fecha larga en español
 * @param string $date Fecha en formato MySQL
 * @return string Fecha formateada
 */
function formatDateLong($date) {
    if (empty($date)) return '';
    
    $months = [
        1 => 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];
    
    $timestamp = strtotime($date);
    $day = date('d', $timestamp);
    $month = $months[(int)date('n', $timestamp)];
    $year = date('Y', $timestamp);
    
    return "{$day} de {$month} de {$year}";
}

/**
 * Formatear número con separador de miles
 * @param int|float $number Número a formatear
 * @return string Número formateado
 */
function formatNumber($number) {
    return number_format($number, 0, ',', '.');
}

/**
 * Truncar texto a una longitud máxima
 * @param string $text Texto original
 * @param int $length Longitud máxima
 * @param string $suffix Sufijo para indicar truncamiento
 * @return string Texto truncado
 */
function truncateText($text, $length = 150, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . $suffix;
}

/**
 * Obtener la IP del visitante
 * @return string IP del visitante
 */
function getClientIP() {
    $ip = '';
    
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    }
    
    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
}

/**
 * Obtener user agent del visitante
 * @return string User agent
 */
function getUserAgent() {
    return $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
}

/**
 * Verificar si el usuario está logueado en el admin
 * @return bool True si está logueado
 */
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_email']);
}

/**
 * Verificar sesión y redireccionar al login si no está autenticado
 */
function requireAdmin() {
    if (!isAdminLoggedIn()) {
        redirect(getConfig('admin_url') . '/login.php');
    }
}

/**
 * Subir archivo de imagen
 * @param array $file Archivo de $_FILES
 * @param string $uploadDir Directorio de subida
 * @return array Resultado con success, message y filename
 */
function uploadImage($file, $uploadDir) {
    $result = [
        'success' => false,
        'message' => '',
        'filename' => ''
    ];
    
    // Validar que no haya errores
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $result['message'] = 'Error al subir el archivo';
        return $result;
    }
    
    // Validar tipo de archivo
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    if (!in_array($file['type'], $allowedTypes)) {
        $result['message'] = 'Tipo de archivo no permitido';
        return $result;
    }
    
    // Validar tamaño (5MB max)
    $maxSize = getConfig('max_upload_size');
    if ($file['size'] > $maxSize) {
        $result['message'] = 'El archivo excede el tamaño máximo permitido';
        return $result;
    }
    
    // Generar nombre único
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . strtolower($extension);
    $filepath = $uploadDir . $filename;
    
    // Mover archivo
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        $result['success'] = true;
        $result['message'] = 'Archivo subido correctamente';
        $result['filename'] = $filename;
        
        // Obtener dimensiones de la imagen
        $imageInfo = getimagesize($filepath);
        if ($imageInfo) {
            $result['width'] = $imageInfo[0];
            $result['height'] = $imageInfo[1];
        }
    } else {
        $result['message'] = 'Error al guardar el archivo';
    }
    
    return $result;
}

/**
 * Eliminar archivo de imagen
 * @param string $filename Nombre del archivo
 * @param string $uploadDir Directorio de uploads
 * @return bool True si se eliminó, false si no
 */
function deleteImage($filename, $uploadDir) {
    $filepath = $uploadDir . $filename;
    if (file_exists($filepath)) {
        return unlink($filepath);
    }
    return false;
}

/**
 * Registrar visita
 * @param Database $db Instancia de Database
 * @param string $tipo Tipo de elemento (articulo, portafolio, pagina)
 * @param int $elementoId ID del elemento
 */
function registerVisit($db, $tipo, $elementoId = null) {
    $sql = "INSERT INTO visitas (tipo, elemento_id, ip, user_agent) VALUES (?, ?, ?, ?)";
    $db->execute($sql, [$tipo, $elementoId, getClientIP(), getUserAgent()]);
    
    // Actualizar contador de visitas
    if ($elementoId && $tipo === 'articulo') {
        $db->execute("UPDATE articulos SET vistas = vistas + 1 WHERE id = ?", [$elementoId]);
    } elseif ($elementoId && $tipo === 'portafolio') {
        $db->execute("UPDATE portafolio SET vistas = COALESCE(vistas, 0) + 1 WHERE id = ?", [$elementoId]);
    }
}

/**
 * Enviar email (usando mail() nativo o SMTP)
 * @param string $to Destinatario
 * @param string $subject Asunto
 * @param string $message Mensaje
 * @param array $headers Headers adicionales
 * @return bool True si se envió, false si no
 */
function sendEmail($to, $subject, $message, $headers = []) {
    $defaultHeaders = [
        'From: ' . getConfig('smtp')['from_name'] . ' <' . getConfig('smtp')['from_email'] . '>',
        'Reply-To: ' . getConfig('smtp')['from_email'],
        'X-Mailer: PHP/' . phpversion(),
        'MIME-Version: 1.0',
        'Content-Type: text/html; charset=UTF-8'
    ];
    
    $allHeaders = array_merge($defaultHeaders, $headers);
    
    return mail($to, $subject, $message, implode("\r\n", $allHeaders));
}

/**
 * Debuggear variable (solo en desarrollo)
 * @param mixed $variable Variable a debuggear
 */
function debug($variable) {
    echo '<pre>' . print_r($variable, true) . '</pre>';
}

/**
 * Obturar URL actual
 * @return string URL actual
 */
function getCurrentURL() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}
