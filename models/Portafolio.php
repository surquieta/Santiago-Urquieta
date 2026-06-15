<?php
/**
 * Modelo Portafolio - Gestión de proyectos del portafolio
 * Santiago Urquieta - Sitio Web Profesional
 */

require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/functions.php';

class Portafolio {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Obtener proyecto por ID
     * @param int $id ID del proyecto
     * @param bool $includeInactive Incluir proyectos inactivos
     * @return array|false Datos del proyecto o false
     */
    public function getById($id, $includeInactive = false) {
        $where = $includeInactive ? '' : 'AND activo = 1';
        
        $sql = "SELECT * FROM portafolio WHERE id = ? {$where}";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    /**
     * Obtener proyecto por slug
     * @param string $slug Slug del proyecto
     * @return array|false Datos del proyecto o false
     */
    public function getBySlug($slug) {
        $sql = "SELECT * FROM portafolio WHERE slug = ? AND activo = 1";
        return $this->db->fetchOne($sql, [$slug]);
    }
    
    /**
     * Obtener todos los proyectos
     * @param bool $onlyActive Solo activos
     * @param string|null $orderBy Ordenamiento
     * @return array Lista de proyectos
     */
    public function getAll($onlyActive = true, $orderBy = 'orden ASC, created_at DESC') {
        $where = $onlyActive ? 'WHERE activo = 1' : '';
        $sql = "SELECT * FROM portafolio {$where} ORDER BY {$orderBy}";
        
        return $this->db->query($sql);
    }
    
    /**
     * Obtener proyectos destacados
     * @param int $limit Número máximo de proyectos
     * @return array Lista de proyectos
     */
    public function getDestacados($limit = 6) {
        $sql = "SELECT * FROM portafolio 
                WHERE activo = 1 AND destacado = 1 
                ORDER BY orden ASC, created_at DESC 
                LIMIT ?";
        
        return $this->db->query($sql, [$limit]);
    }
    
    /**
     * Crear nuevo proyecto
     * @param array $data Datos del proyecto
     * @return int|false ID del nuevo proyecto o false
     */
    public function create($data) {
        $sql = "INSERT INTO portafolio 
                (titulo, slug, descripcion, contenido, imagen, tecnologias, url_proyecto, url_demo, orden, destacado, activo)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        return $this->db->execute($sql, [
            sanitize($data['titulo']),
            generateSlug($data['titulo']),
            sanitize($data['descripcion'] ?? ''),
            cleanHTML($data['contenido'] ?? ''),
            sanitize($data['imagen']),
            sanitize($data['tecnologias'] ?? ''),
            sanitize($data['url_proyecto'] ?? null),
            sanitize($data['url_demo'] ?? null),
            $data['orden'] ?? 0,
            $data['destacado'] ?? 0,
            $data['activo'] ?? 1
        ]);
    }
    
    /**
     * Actualizar proyecto
     * @param int $id ID del proyecto
     * @param array $data Datos a actualizar
     * @return bool True si se actualizó, false si no
     */
    public function update($id, $data) {
        $fields = [];
        $params = [];
        
        if (isset($data['titulo'])) {
            $fields[] = "titulo = ?";
            $params[] = sanitize($data['titulo']);
            $params[] = generateSlug($data['titulo']);
            $fields[] = "slug = ?";
        }
        
        if (isset($data['descripcion'])) {
            $fields[] = "descripcion = ?";
            $params[] = sanitize($data['descripcion']);
        }
        
        if (isset($data['contenido'])) {
            $fields[] = "contenido = ?";
            $params[] = cleanHTML($data['contenido']);
        }
        
        if (isset($data['imagen'])) {
            $fields[] = "imagen = ?";
            $params[] = sanitize($data['imagen']);
        }
        
        if (isset($data['tecnologias'])) {
            $fields[] = "tecnologias = ?";
            $params[] = sanitize($data['tecnologias']);
        }
        
        if (isset($data['url_proyecto'])) {
            $fields[] = "url_proyecto = ?";
            $params[] = sanitize($data['url_proyecto']);
        }
        
        if (isset($data['url_demo'])) {
            $fields[] = "url_demo = ?";
            $params[] = sanitize($data['url_demo']);
        }
        
        if (isset($data['orden'])) {
            $fields[] = "orden = ?";
            $params[] = (int)$data['orden'];
        }
        
        if (isset($data['destacado'])) {
            $fields[] = "destacado = ?";
            $params[] = (int)$data['destacado'];
        }
        
        if (isset($data['activo'])) {
            $fields[] = "activo = ?";
            $params[] = (int)$data['activo'];
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $params[] = $id;
        $sql = "UPDATE portafolio SET " . implode(', ', $fields) . " WHERE id = ?";
        
        return $this->db->execute($sql, $params) !== false;
    }
    
    /**
     * Eliminar proyecto
     * @param int $id ID del proyecto
     * @return bool True si se eliminó, false si no
     */
    public function delete($id) {
        return $this->db->execute("DELETE FROM portafolio WHERE id = ?", [$id]) !== false;
    }
    
    /**
     * Contar proyectos
     * @param bool $onlyActive Solo activos
     * @return int Número de proyectos
     */
    public function count($onlyActive = true) {
        $where = $onlyActive ? 'activo = 1' : '';
        return $this->db->count('portafolio', $where);
    }
}
