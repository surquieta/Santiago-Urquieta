<?php
/**
 * Modelo Articulo - Gestión de artículos del blog
 * Santiago Urquieta - Sitio Web Profesional
 */

require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/functions.php';

class Articulo {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Obtener artículo por ID
     * @param int $id ID del artículo
     * @param bool $includeInactive Incluir artículos inactivos
     * @return array|false Datos del artículo o false
     */
    public function getById($id, $includeInactive = false) {
        $where = $includeInactive ? '' : 'AND a.activo = 1';
        
        $sql = "SELECT a.*, c.nombre as categoria_nombre, c.slug as categoria_slug,
                       u.nombre as autor_nombre
                FROM articulos a
                LEFT JOIN categorias c ON a.categoria_id = c.id
                LEFT JOIN usuarios u ON a.autor_id = u.id
                WHERE a.id = ? {$where}";
        
        return $this->db->fetchOne($sql, [$id]);
    }
    
    /**
     * Obtener artículo por slug
     * @param string $slug Slug del artículo
     * @return array|false Datos del artículo o false
     */
    public function getBySlug($slug) {
        $sql = "SELECT a.*, c.nombre as categoria_nombre, c.slug as categoria_slug,
                       u.nombre as autor_nombre
                FROM articulos a
                LEFT JOIN categorias c ON a.categoria_id = c.id
                LEFT JOIN usuarios u ON a.autor_id = u.id
                WHERE a.slug = ? AND a.activo = 1 AND a.published_at <= NOW()";
        
        return $this->db->fetchOne($sql, [$slug]);
    }
    
    /**
     * Obtener todos los artículos (paginados)
     * @param int $page Página actual
     * @param int $perPage Artículos por página
     * @param int|null $categoriaId Filtro por categoría
     * @return array ['articulos' => [...], 'total' => int, 'pages' => int]
     */
    public function getAll($page = 1, $perPage = 10, $categoriaId = null) {
        $offset = ($page - 1) * $perPage;
        
        $where = 'WHERE a.activo = 1 AND a.published_at <= NOW()';
        $params = [];
        
        if ($categoriaId) {
            $where .= ' AND a.categoria_id = ?';
            $params[] = $categoriaId;
        }
        
        // Obtener total
        $countSql = "SELECT COUNT(*) as total FROM articulos a {$where}";
        $totalResult = $this->db->fetchOne($countSql, $params);
        $total = $totalResult['total'];
        
        // Obtener artículos
        $sql = "SELECT a.*, c.nombre as categoria_nombre, c.slug as categoria_slug,
                       u.nombre as autor_nombre
                FROM articulos a
                LEFT JOIN categorias c ON a.categoria_id = c.id
                LEFT JOIN usuarios u ON a.autor_id = u.id
                {$where}
                ORDER BY a.published_at DESC, a.created_at DESC
                LIMIT ? OFFSET ?";
        
        $params[] = $perPage;
        $params[] = $offset;
        
        $articulos = $this->db->query($sql, $params);
        
        return [
            'articulos' => $articulos ?: [],
            'total' => $total,
            'pages' => ceil($total / $perPage)
        ];
    }
    
    /**
     * Obtener artículos destacados
     * @param int $limit Número máximo de artículos
     * @return array Lista de artículos
     */
    public function getDestacados($limit = 3) {
        $sql = "SELECT a.*, c.nombre as categoria_nombre, c.slug as categoria_slug
                FROM articulos a
                LEFT JOIN categorias c ON a.categoria_id = c.id
                WHERE a.activo = 1 AND a.destacado = 1 AND a.published_at <= NOW()
                ORDER BY a.published_at DESC
                LIMIT ?";
        
        return $this->db->query($sql, [$limit]);
    }
    
    /**
     * Obtener últimos artículos
     * @param int $limit Número máximo de artículos
     * @return array Lista de artículos
     */
    public function getRecientes($limit = 5) {
        $sql = "SELECT a.*, c.nombre as categoria_nombre
                FROM articulos a
                LEFT JOIN categorias c ON a.categoria_id = c.id
                WHERE a.activo = 1 AND a.published_at <= NOW()
                ORDER BY a.published_at DESC
                LIMIT ?";
        
        return $this->db->query($sql, [$limit]);
    }
    
    /**
     * Crear nuevo artículo
     * @param array $data Datos del artículo
     * @return int|false ID del nuevo artículo o false
     */
    public function create($data) {
        $sql = "INSERT INTO articulos 
                (titulo, slug, extracto, contenido, imagen_destacada, categoria_id, 
                 autor_id, meta_titulo, meta_descripcion, meta_keywords, destacado, activo, published_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        return $this->db->execute($sql, [
            sanitize($data['titulo']),
            generateSlug($data['titulo']),
            sanitize($data['extracto'] ?? ''),
            cleanHTML($data['contenido']),
            sanitize($data['imagen_destacada'] ?? null),
            $data['categoria_id'] ?? null,
            $data['autor_id'] ?? null,
            sanitize($data['meta_titulo'] ?? ''),
            sanitize($data['meta_descripcion'] ?? ''),
            sanitize($data['meta_keywords'] ?? ''),
            $data['destacado'] ?? 0,
            $data['activo'] ?? 1,
            $data['published_at'] ?? date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Actualizar artículo
     * @param int $id ID del artículo
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
        
        if (isset($data['extracto'])) {
            $fields[] = "extracto = ?";
            $params[] = sanitize($data['extracto']);
        }
        
        if (isset($data['contenido'])) {
            $fields[] = "contenido = ?";
            $params[] = cleanHTML($data['contenido']);
        }
        
        if (isset($data['imagen_destacada'])) {
            $fields[] = "imagen_destacada = ?";
            $params[] = sanitize($data['imagen_destacada']);
        }
        
        if (isset($data['categoria_id'])) {
            $fields[] = "categoria_id = ?";
            $params[] = $data['categoria_id'];
        }
        
        if (isset($data['meta_titulo'])) {
            $fields[] = "meta_titulo = ?";
            $params[] = sanitize($data['meta_titulo']);
        }
        
        if (isset($data['meta_descripcion'])) {
            $fields[] = "meta_descripcion = ?";
            $params[] = sanitize($data['meta_descripcion']);
        }
        
        if (isset($data['meta_keywords'])) {
            $fields[] = "meta_keywords = ?";
            $params[] = sanitize($data['meta_keywords']);
        }
        
        if (isset($data['destacado'])) {
            $fields[] = "destacado = ?";
            $params[] = (int)$data['destacado'];
        }
        
        if (isset($data['activo'])) {
            $fields[] = "activo = ?";
            $params[] = (int)$data['activo'];
        }
        
        if (isset($data['published_at'])) {
            $fields[] = "published_at = ?";
            $params[] = $data['published_at'];
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $params[] = $id;
        $sql = "UPDATE articulos SET " . implode(', ', $fields) . " WHERE id = ?";
        
        return $this->db->execute($sql, $params) !== false;
    }
    
    /**
     * Eliminar artículo
     * @param int $id ID del artículo
     * @return bool True si se eliminó, false si no
     */
    public function delete($id) {
        // Eliminar relaciones con etiquetas
        $this->db->execute("DELETE FROM articulo_etiqueta WHERE articulo_id = ?", [$id]);
        
        // Eliminar artículo
        return $this->db->execute("DELETE FROM articulos WHERE id = ?", [$id]) !== false;
    }
    
    /**
     * Contar artículos
     * @param bool $onlyActive Solo activos
     * @return int Número de artículos
     */
    public function count($onlyActive = true) {
        $where = $onlyActive ? 'activo = 1 AND published_at <= NOW()' : '';
        return $this->db->count('articulos', $where);
    }
    
    /**
     * Buscar artículos
     * @param string $search Término de búsqueda
     * @param int $page Página actual
     * @param int $perPage Artículos por página
     * @return array Resultados de búsqueda
     */
    public function search($search, $page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        $searchTerm = "%{$search}%";
        
        // Obtener total
        $countSql = "SELECT COUNT(*) as total FROM articulos 
                     WHERE activo = 1 AND (titulo LIKE ? OR extracto LIKE ? OR contenido LIKE ?)";
        $totalResult = $this->db->fetchOne($countSql, [$searchTerm, $searchTerm, $searchTerm]);
        $total = $totalResult['total'];
        
        // Obtener artículos
        $sql = "SELECT a.*, c.nombre as categoria_nombre
                FROM articulos a
                LEFT JOIN categorias c ON a.categoria_id = c.id
                WHERE a.activo = 1 AND (a.titulo LIKE ? OR a.extracto LIKE ? OR a.contenido LIKE ?)
                ORDER BY a.published_at DESC
                LIMIT ? OFFSET ?";
        
        $articulos = $this->db->query($sql, [$searchTerm, $searchTerm, $searchTerm, $perPage, $offset]);
        
        return [
            'articulos' => $articulos ?: [],
            'total' => $total,
            'pages' => ceil($total / $perPage)
        ];
    }
    
    /**
     * Gestionar etiquetas de un artículo
     * @param int $articuloId ID del artículo
     * @param array $etiquetas Lista de etiquetas
     */
    public function setEtiquetas($articuloId, $etiquetas) {
        // Eliminar etiquetas existentes
        $this->db->execute("DELETE FROM articulo_etiqueta WHERE articulo_id = ?", [$articuloId]);
        
        if (empty($etiquetas)) {
            return;
        }
        
        // Insertar nuevas etiquetas
        foreach ($etiquetas as $etiqueta) {
            $etiqueta = trim($etiqueta);
            if (empty($etiqueta)) continue;
            
            // Buscar o crear etiqueta
            $slug = generateSlug($etiqueta);
            $existingEtiqueta = $this->db->fetchOne("SELECT id FROM etiquetas WHERE slug = ?", [$slug]);
            
            if ($existingEtiqueta) {
                $etiquetaId = $existingEtiqueta['id'];
            } else {
                $etiquetaId = $this->db->execute(
                    "INSERT INTO etiquetas (nombre, slug) VALUES (?, ?)",
                    [sanitize($etiqueta), $slug]
                );
            }
            
            // Relacionar artículo con etiqueta
            $this->db->execute(
                "INSERT INTO articulo_etiqueta (articulo_id, etiqueta_id) VALUES (?, ?)",
                [$articuloId, $etiquetaId]
            );
        }
    }
    
    /**
     * Obtener etiquetas de un artículo
     * @param int $articuloId ID del artículo
     * @return array Lista de etiquetas
     */
    public function getEtiquetas($articuloId) {
        $sql = "SELECT e.* FROM etiquetas e
                INNER JOIN articulo_etiqueta ae ON e.id = ae.etiqueta_id
                WHERE ae.articulo_id = ?";
        
        return $this->db->query($sql, [$articuloId]);
    }
}
