<?php
/**
 * Modelo Contacto - Gestión de mensajes del formulario
 * Santiago Urquieta - Sitio Web Profesional
 */

require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/functions.php';

class Contacto {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Crear nuevo mensaje de contacto
     * @param array $data Datos del mensaje
     * @return int|false ID del mensaje o false
     */
    public function create($data) {
        $sql = "INSERT INTO contactos (nombre, email, asunto, mensaje, ip) VALUES (?, ?, ?, ?, ?)";
        
        return $this->db->execute($sql, [
            sanitize($data['nombre']),
            sanitize($data['email']),
            sanitize($data['asunto'] ?? ''),
            sanitize($data['mensaje']),
            getClientIP()
        ]);
    }
    
    /**
     * Obtener mensaje por ID
     * @param int $id ID del mensaje
     * @return array|false Datos del mensaje o false
     */
    public function getById($id) {
        $sql = "SELECT * FROM contactos WHERE id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    /**
     * Obtener todos los mensajes
     * @param string $orderBy Ordenamiento
     * @return array Lista de mensajes
     */
    public function getAll($orderBy = 'created_at DESC') {
        $sql = "SELECT * FROM contactos ORDER BY {$orderBy}";
        return $this->db->query($sql);
    }
    
    /**
     * Marcar mensaje como leído
     * @param int $id ID del mensaje
     * @return bool True si se actualizó, false si no
     */
    public function markAsRead($id) {
        return $this->db->execute("UPDATE contactos SET leido = 1 WHERE id = ?", [$id]) !== false;
    }
    
    /**
     * Marcar mensaje como respondido
     * @param int $id ID del mensaje
     * @return bool True si se actualizó, false si no
     */
    public function markAsReplied($id) {
        return $this->db->execute("UPDATE contactos SET leido = 1, respondido = 1 WHERE id = ?", [$id]) !== false;
    }
    
    /**
     * Eliminar mensaje
     * @param int $id ID del mensaje
     * @return bool True si se eliminó, false si no
     */
    public function delete($id) {
        return $this->db->execute("DELETE FROM contactos WHERE id = ?", [$id]) !== false;
    }
    
    /**
     * Contar mensajes no leídos
     * @return int Número de mensajes no leídos
     */
    public function countUnread() {
        return $this->db->count('contactos', 'leido = 0');
    }
    
    /**
     * Contar total de mensajes
     * @return int Número total de mensajes
     */
    public function count() {
        return $this->db->count('contactos');
    }
}
