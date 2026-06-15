<?php
/**
 * Modelo Imagen - Gestión de biblioteca multimedia
 * Santiago Urquieta - Sitio Web Profesional
 */

require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/functions.php';

class Imagen {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Obtener imagen por ID
     * @param int $id ID de la imagen
     * @return array|false Datos de la imagen o false
     */
    public function getById($id) {
        $sql = "SELECT * FROM imagenes WHERE id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    /**
     * Obtener todas las imágenes
     * @param int|null $usuarioId Filtro por usuario
     * @param string $orderBy Ordenamiento
     * @return array Lista de imágenes
     */
    public function getAll($usuarioId = null, $orderBy = 'created_at DESC') {
        $where = '';
        $params = [];
        
        if ($usuarioId) {
            $where = 'WHERE usuario_id = ?';
            $params[] = $usuarioId;
        }
        
        $sql = "SELECT * FROM imagenes {$where} ORDER BY {$orderBy}";
        return $this->db->query($sql, $params);
    }
    
    /**
     * Crear registro de imagen
     * @param array $data Datos de la imagen
     * @return int|false ID de la imagen o false
     */
    public function create($data) {
        $sql = "INSERT INTO imagenes 
                (nombre, archivo, ruta, mime_type, tamano, ancho, alto, alt_text, descripcion, usuario_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        return $this->db->execute($sql, [
            sanitize($data['nombre']),
            sanitize($data['archivo']),
            sanitize($data['ruta']),
            sanitize($data['mime_type'] ?? ''),
            $data['tamano'] ?? 0,
            $data['ancho'] ?? 0,
            $data['alto'] ?? 0,
            sanitize($data['alt_text'] ?? ''),
            sanitize($data['descripcion'] ?? ''),
            $data['usuario_id'] ?? null
        ]);
    }
    
    /**
     * Actualizar imagen
     * @param int $id ID de la imagen
     * @param array $data Datos a actualizar
     * @return bool True si se actualizó, false si no
     */
    public function update($id, $data) {
        $fields = [];
        $params = [];
        
        if (isset($data['nombre'])) {
            $fields[] = "nombre = ?";
            $params[] = sanitize($data['nombre']);
        }
        
        if (isset($data['alt_text'])) {
            $fields[] = "alt_text = ?";
            $params[] = sanitize($data['alt_text']);
        }
        
        if (isset($data['descripcion'])) {
            $fields[] = "descripcion = ?";
            $params[] = sanitize($data['descripcion']);
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $params[] = $id;
        $sql = "UPDATE imagenes SET " . implode(', ', $fields) . " WHERE id = ?";
        
        return $this->db->execute($sql, $params) !== false;
    }
    
    /**
     * Eliminar imagen (registro y archivo)
     * @param int $id ID de la imagen
     * @param string $uploadDir Directorio de uploads
     * @return bool True si se eliminó, false si no
     */
    public function delete($id, $uploadDir) {
        $imagen = $this->getById($id);
        
        if (!$imagen) {
            return false;
        }
        
        // Eliminar archivo físico
        deleteImage($imagen['archivo'], $uploadDir);
        
        // Eliminar registro
        return $this->db->execute("DELETE FROM imagenes WHERE id = ?", [$id]) !== false;
    }
    
    /**
     * Buscar imágenes por nombre
     * @param string $search Término de búsqueda
     * @return array Lista de imágenes
     */
    public function search($search) {
        $searchTerm = "%{$search}%";
        $sql = "SELECT * FROM imagenes 
                WHERE nombre LIKE ? OR alt_text LIKE ? OR descripcion LIKE ?
                ORDER BY created_at DESC";
        
        return $this->db->query($sql, [$searchTerm, $searchTerm, $searchTerm]);
    }
    
    /**
     * Contar imágenes
     * @return int Número de imágenes
     */
    public function count() {
        return $this->db->count('imagenes');
    }
}
