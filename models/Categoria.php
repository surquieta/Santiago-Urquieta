<?php
/**
 * Modelo Categoria - Gestión de categorías del blog
 * Santiago Urquieta - Sitio Web Profesional
 */

require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/functions.php';

class Categoria {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Obtener categoría por ID
     * @param int $id ID de la categoría
     * @return array|false Datos de la categoría o false
     */
    public function getById($id) {
        $sql = "SELECT * FROM categorias WHERE id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    /**
     * Obtener categoría por slug
     * @param string $slug Slug de la categoría
     * @return array|false Datos de la categoría o false
     */
    public function getBySlug($slug) {
        $sql = "SELECT * FROM categorias WHERE slug = ? AND activo = 1";
        return $this->db->fetchOne($sql, [$slug]);
    }
    
    /**
     * Obtener todas las categorías
     * @param bool $onlyActive Solo activas
     * @return array Lista de categorías
     */
    public function getAll($onlyActive = true) {
        $where = $onlyActive ? 'WHERE activo = 1' : '';
        $sql = "SELECT *, (SELECT COUNT(*) FROM articulos WHERE categoria_id = categorias.id AND activo = 1) as total_articulos
                FROM categorias {$where} ORDER BY orden ASC, nombre ASC";
        
        return $this->db->query($sql);
    }
    
    /**
     * Crear nueva categoría
     * @param array $data Datos de la categoría
     * @return int|false ID de la nueva categoría o false
     */
    public function create($data) {
        $sql = "INSERT INTO categorias (nombre, slug, descripcion, imagen, orden, activo) VALUES (?, ?, ?, ?, ?, ?)";
        
        return $this->db->execute($sql, [
            sanitize($data['nombre']),
            generateSlug($data['nombre']),
            sanitize($data['descripcion'] ?? ''),
            sanitize($data['imagen'] ?? null),
            $data['orden'] ?? 0,
            $data['activo'] ?? 1
        ]);
    }
    
    /**
     * Actualizar categoría
     * @param int $id ID de la categoría
     * @param array $data Datos a actualizar
     * @return bool True si se actualizó, false si no
     */
    public function update($id, $data) {
        $fields = [];
        $params = [];
        
        if (isset($data['nombre'])) {
            $fields[] = "nombre = ?";
            $params[] = sanitize($data['nombre']);
            $params[] = generateSlug($data['nombre']);
            $fields[] = "slug = ?";
        }
        
        if (isset($data['descripcion'])) {
            $fields[] = "descripcion = ?";
            $params[] = sanitize($data['descripcion']);
        }
        
        if (isset($data['imagen'])) {
            $fields[] = "imagen = ?";
            $params[] = sanitize($data['imagen']);
        }
        
        if (isset($data['orden'])) {
            $fields[] = "orden = ?";
            $params[] = (int)$data['orden'];
        }
        
        if (isset($data['activo'])) {
            $fields[] = "activo = ?";
            $params[] = (int)$data['activo'];
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $params[] = $id;
        $sql = "UPDATE categorias SET " . implode(', ', $fields) . " WHERE id = ?";
        
        return $this->db->execute($sql, $params) !== false;
    }
    
    /**
     * Eliminar categoría
     * @param int $id ID de la categoría
     * @return bool True si se eliminó, false si no
     */
    public function delete($id) {
        // Verificar si tiene artículos asociados
        $count = $this->db->count('articulos', "categoria_id = ?", [$id]);
        if ($count > 0) {
            return false; // No eliminar si tiene artículos
        }
        
        return $this->db->execute("DELETE FROM categorias WHERE id = ?", [$id]) !== false;
    }
    
    /**
     * Contar categorías
     * @param bool $onlyActive Solo activas
     * @return int Número de categorías
     */
    public function count($onlyActive = true) {
        $where = $onlyActive ? 'activo = 1' : '';
        return $this->db->count('categorias', $where);
    }
}
