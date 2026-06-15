<?php
/**
 * Clase Database - Conexión segura a MySQL usando PDO
 * Santiago Urquieta - Sitio Web Profesional
 */

class Database {
    private static $instance = null;
    private $connection;
    private $config;
    
    /**
     * Constructor privado para patrón Singleton
     */
    private function __construct() {
        $this->config = require __DIR__ . '/../config/database.php';
        $this->connect();
    }
    
    /**
     * Prevenir clonación de la instancia
     */
    private function __clone() {}
    
    /**
     * Prevenir deserialización
     */
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
    
    /**
     * Obtener instancia única de Database
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Establecer conexión con la base de datos
     */
    private function connect() {
        try {
            $dsn = "mysql:host={$this->config['host']};dbname={$this->config['dbname']};charset={$this->config['charset']}";
            
            $this->connection = new PDO($dsn, $this->config['username'], $this->config['password'], $this->config['options']);
            
        } catch (PDOException $e) {
            error_log("Error de conexión a la base de datos: " . $e->getMessage());
            die("Error de conexión. Por favor, contacte al administrador.");
        }
    }
    
    /**
     * Obtener conexión PDO
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Ejecutar consulta preparada (SELECT)
     * @param string $sql Consulta SQL
     * @param array $params Parámetros para la consulta preparada
     * @return array|false Resultados o false si no hay resultados
     */
    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en consulta: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Ejecutar consulta preparada (INSERT, UPDATE, DELETE)
     * @param string $sql Consulta SQL
     * @param array $params Parámetros para la consulta preparada
     * @return int|false Último ID insertado o número de filas afectadas, false si falla
     */
    public function execute($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $this->connection->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error en ejecución: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener un solo registro
     * @param string $sql Consulta SQL
     * @param array $params Parámetros para la consulta preparada
     * @return array|false Registro o false si no existe
     */
    public function fetchOne($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error en fetchOne: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Contar registros
     * @param string $table Nombre de la tabla
     * @param string $where Condición WHERE opcional
     * @param array $params Parámetros para la consulta preparada
     * @return int Número de registros
     */
    public function count($table, $where = '', $params = []) {
        $sql = "SELECT COUNT(*) as total FROM {$table}";
        if (!empty($where)) {
            $sql .= " WHERE {$where}";
        }
        
        $result = $this->fetchOne($sql, $params);
        return $result ? (int)$result['total'] : 0;
    }
    
    /**
     * Iniciar transacción
     */
    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }
    
    /**
     * Confirmar transacción
     */
    public function commit() {
        return $this->connection->commit();
    }
    
    /**
     * Revertir transacción
     */
    public function rollback() {
        return $this->connection->rollBack();
    }
}
