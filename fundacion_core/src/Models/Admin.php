<?php

namespace App\Models;

use PDO;

use PDOException;
class Admin extends Model {
    protected $table = 'admin';

    /**
     * Busca un administrador por email (usado para el login).
     * @param string $email
     * @return array|false
     */
    public function findByEmail(string $email) {
        $sql = "SELECT id, nombre, email, password, rol, estado 
                FROM {$this->table} 
                WHERE email = :email AND deleted_at IS NULL";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }
    
    public function findByResetToken(string $token) {
        // Buscamos el token y verificamos que la fecha de expiración sea mayor a 'ahora'
        $sql = "SELECT id, nombre, email 
                FROM {$this->table} 
                WHERE reset_token = :token 
                AND reset_expires_at > NOW() 
                AND deleted_at IS NULL 
                LIMIT 1";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':token' => $token]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al buscar admin por token: " . $e->getMessage());
            return false;
        }
    }
}