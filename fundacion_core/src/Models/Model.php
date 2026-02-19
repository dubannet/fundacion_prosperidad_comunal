<?php

namespace App\Models;

use App\Config\Database;
use PDO;
use PDOException;
use Exception;

class Model
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct()
    {
        try {
            
            $this->db = Database::getInstance()->getConnection();
        } catch (Exception $e) {
            error_log("Error de conexión: " . $e->getMessage());
            throw new Exception("Error de conexión a la base de datos.");
        }
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function getDbConnection(): PDO
    {
        return $this->db;
    }

    /**
     * Obtiene todos los registros de la tabla.
     * @param string $orderBy Campo para ordenar.
     * @return array
     */
    public function findAll(string $orderBy = 'id'): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy}";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error al obtener todos los registros de {$this->table}: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca un registro por su clave primaria.
     * @param int $id
     * @return array|false
     */
    public function find(int $id): ?array
    { 
        if (empty($this->table)) {

            throw new Exception("Error: La tabla no está definida en el modelo.");
        }

        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Usamos PDO::FETCH_ASSOC explícitamente y manejo de null
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ?: null;

        } catch (PDOException $e) {
            error_log("Error al buscar registro en {$this->table}: " . $e->getMessage());
            throw new Exception("Fallo en la consulta al buscar ID {$id}: " . $e->getMessage());
        }
    }

    /**
     * Crea un nuevo registro.
     * @param array $data Array asociativo con los datos (columna => valor).
     * @return int|false El ID del nuevo registro o false si falla.
     */
    public function create(array $data)
    {
        $fields = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholders})";

        try {
            $stmt = $this->db->prepare($sql);
            foreach ($data as $key => &$value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error al crear registro en {$this->table}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza un registro por su clave primaria.
     * @param int $id El ID del registro a actualizar.
     * @param array $data Los datos (columna => valor) a establecer.
     * @return bool True si la actualización fue exitosa, false en caso contrario.
     */
    public function update(int $id, array $data): bool
    {
        if (empty($data)) {
            return false;
        }

        $setClauses = [];
        foreach (array_keys($data) as $key) {
            $setClauses[] = "{$key} = :{$key}";
        }
        $setClause = implode(', ', $setClauses);

        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = :id";

        try {
            $stmt = $this->db->prepare($sql);

            foreach ($data as $key => $value) {
                $stmt->bindValue(":{$key}", $value);
            }

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar en la tabla {$this->table}: " . $e->getMessage());
            return false;
        }
    }
}
