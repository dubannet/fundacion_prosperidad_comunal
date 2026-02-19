<?php

namespace App\Models;


use PDOException;
use PDO;

class ProyectoMedia extends Model {
    protected $table = 'proyecto_media';

    /**
     * Guarda una nueva entrada de multimedia.
     * @param int $proyectoId ID del proyecto al que pertenece.
     * @param string $ruta Nombre del archivo/ruta.
     * @param string $tipo 'imagen' o 'video'.
     * @return int|false El ID del nuevo registro o false si falla.
     */
    public function saveMedia(int $proyectoId, string $ruta, string $tipo) {
        $data = [
            'proyecto_id' => $proyectoId,
            'ruta' => $ruta,
            'tipo' => $tipo,
        ];
        
        // Usamos el método create heredado de Model.php
        return $this->create($data); 
    }
    
    /**
     * Obtiene toda la multimedia asociada a un proyecto (excluyendo soft-deleted).
     * @param int $proyectoId
     * @return array
     */
    public function findByProyectoId(int $proyectoId): array {
        $sql = "SELECT id, ruta, tipo FROM {$this->table} 
                WHERE proyecto_id = :proyecto_id AND deleted_at IS NULL
                ORDER BY created_at ASC";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':proyecto_id', $proyectoId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener multimedia del proyecto {$proyectoId}: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Realiza un borrado lógico masivo de toda la multimedia asociada a un proyecto.
     * @param int $proyectoId
     * @return bool
     */
    public function softDeleteByProyectoId(int $proyectoId): bool {
        $sql = "UPDATE {$this->table} SET deleted_at = NOW() WHERE proyecto_id = :id AND deleted_at IS NULL";
        
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $proyectoId]);
        } catch (PDOException $e) {
            error_log("Error al borrar media masiva para proyecto ID {$proyectoId}: " . $e->getMessage());
            return false;
        }
    }

    
}