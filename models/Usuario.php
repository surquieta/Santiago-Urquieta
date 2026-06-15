<?php
/**
 * Modelo Usuario - Gestión de administradores
 * Santiago Urquieta - Sitio Web Profesional
 */

require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/functions.php';

class Usuario {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Autenticar usuario
     * @param string $email Email del usuario
     * @param string $password Contraseña (en texto plano)
     * @return array|false Datos del usuario o false si falla
     */
    public function authenticate($email, $password) {
        $sql = "SELECT * FROM usuarios WHERE email = ? AND activo = 1";
        $usuario = $this->db->fetchOne($sql, [$email]);
        
        if ($usuario && password_verify($password, $usuario['password'])) {
            // Actualizar último acceso
            $this->db->execute(
                "UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = ?",
                [$usuario['id']]
            );
            
            return $usuario;
        }
        
        return false;
    }
    
    /**
     * Obtener usuario por ID
     * @param int $id ID del usuario
     * @return array|false Datos del usuario o false
     */
    public function getById($id) {
        $sql = "SELECT id, nombre, email, avatar, rol, activo, created_at FROM usuarios WHERE id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    /**
     * Obtener usuario por email
     * @param string $email Email del usuario
     * @return array|false Datos del usuario o false
     */
    public function getByEmail($email) {
        $sql = "SELECT id, nombre, email, avatar, rol, activo FROM usuarios WHERE email = ?";
        return $this->db->fetchOne($sql, [$email]);
    }
    
    /**
     * Crear nuevo usuario
     * @param array $data Datos del usuario
     * @return int|false ID del nuevo usuario o false
     */
    public function create($data) {
        $sql = "INSERT INTO usuarios (nombre, email, password, rol, activo) VALUES (?, ?, ?, ?, ?)";
        return $this->db->execute($sql, [
            sanitize($data['nombre']),
            sanitize($data['email']),
            password_hash($data['password'], PASSWORD_BCRYPT),
            $data['rol'] ?? 'editor',
            $data['activo'] ?? 1
        ]);
    }
    
    /**
     * Actualizar usuario
     * @param int $id ID del usuario
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
        
        if (isset($data['email'])) {
            $fields[] = "email = ?";
            $params[] = sanitize($data['email']);
        }
        
        if (isset($data['password']) && !empty($data['password'])) {
            $fields[] = "password = ?";
            $params[] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        
        if (isset($data['avatar'])) {
            $fields[] = "avatar = ?";
            $params[] = sanitize($data['avatar']);
        }
        
        if (isset($data['rol'])) {
            $fields[] = "rol = ?";
            $params[] = $data['rol'];
        }
        
        if (isset($data['activo'])) {
            $fields[] = "activo = ?";
            $params[] = (int)$data['activo'];
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $params[] = $id;
        $sql = "UPDATE usuarios SET " . implode(', ', $fields) . " WHERE id = ?";
        
        return $this->db->execute($sql, $params) !== false;
    }
    
    /**
     * Eliminar usuario (soft delete)
     * @param int $id ID del usuario
     * @return bool True si se eliminó, false si no
     */
    public function delete($id) {
        return $this->update($id, ['activo' => 0]);
    }
    
    /**
     * Obtener todos los usuarios
     * @return array Lista de usuarios
     */
    public function getAll() {
        $sql = "SELECT id, nombre, email, avatar, rol, activo, ultimo_acceso, created_at 
                FROM usuarios ORDER BY created_at DESC";
        return $this->db->query($sql);
    }
    
    /**
     * Contar usuarios activos
     * @return int Número de usuarios
     */
    public function count() {
        return $this->db->count('usuarios', 'activo = 1');
    }
}
