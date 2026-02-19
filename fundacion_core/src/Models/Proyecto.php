<?php

namespace App\Models;



use PDOException;
use PDO;

class Proyecto extends Model
{
    protected $table = 'proyectos';

    /**
     * Obtiene todos los proyectos activos (no eliminados lógicamente).
     * @return array
     */
    public function findAllActive(): array
    {
        
        $sql = "SELECT id, nombre, descripcion, estado, imagen_principal, created_at 
            FROM {$this->table} 
            WHERE deleted_at IS NULL 
            ORDER BY created_at DESC";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Usamos FETCH_ASSOC por consistencia
        } catch (PDOException $e) {
            error_log("Error al obtener proyectos: " . $e->getMessage());
            return [];
        }
    }


    public function findWithMedia(int $id)
    {
        $sql = "SELECT p.id, p.nombre, p.descripcion, p.estado, p.imagen_principal, 
                       pm.ruta AS media_ruta, pm.tipo AS media_tipo
                FROM proyectos p
                LEFT JOIN proyecto_media pm ON p.id = pm.proyecto_id
                WHERE p.id = :id AND p.deleted_at IS NULL";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            // Esto devolvería múltiples filas si hay varias imágenes.
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Usar FETCH_ASSOC para claridad
        } catch (PDOException $e) {
            error_log("Error al buscar proyecto con media: " . $e->getMessage());
            return false;
        }
    }

    public function findWithAllData(int $id): ?array
    {
        // 1. Obtener datos principales del proyecto
        $proyecto = $this->find($id); // Usamos el find simple de Model.php
        if (!$proyecto) {
            return null;
        }

        // 2. Obtener la multimedia asociada (usando ProyectoMedia)
        $sqlMedia = "SELECT id, ruta, tipo FROM proyecto_media 
                     WHERE proyecto_id = :id AND deleted_at IS NULL 
                     ORDER BY created_at ASC";

        try {
            $stmt = $this->db->prepare($sqlMedia);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $multimedia = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener multimedia asociada para ID {$id}: " . $e->getMessage());
            $multimedia = [];
        }

        // 3. Devolver los datos combinados
        return [
            'proyecto' => $proyecto,
            'multimedia' => $multimedia
        ];
    }
}
