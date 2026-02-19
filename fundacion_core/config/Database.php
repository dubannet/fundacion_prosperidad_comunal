<?php
// fundacion_core/config/db.php

namespace App\Config; // Añadimos namespace para orden
use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        // Ya no cargamos Dotenv aquí porque bootstrap.php ya lo hizo
        $host = $_ENV['BD_HOST'] ?? 'localhost';
        $db_name = $_ENV['BD_NAME'] ?? '';
        $username = $_ENV['BD_USER'] ?? '';
        $password = $_ENV['BD_PASSWORD'] ?? '';

        try {
            $dsn = "mysql:host={$host};dbname={$db_name};charset=utf8mb4";
            $this->connection = new PDO($dsn, $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error de Conexión a la BD: " . $e->getMessage());
            die("Error de Conexión a la Base de Datos."); 
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    private function __clone() {}
    public function __wakeup() {
        throw new \Exception("Cannot unserialize a singleton.");
    }
}