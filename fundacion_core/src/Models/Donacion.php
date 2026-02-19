<?php

namespace App\Models;

use PDO;
use PDOException;
class Donacion extends Model {
    protected $table = 'donaciones';

    /**
     * Registra una donación en estado pendiente (usado antes de Wompi).
     * Nota: En el flujo actual, lo más lógico es crear el registro al recibir el webhook
     * con el estado 'aprobada' o 'fallida', usando la referencia generada.
     * * Por ahora, solo extenderemos el método create de la clase base.
     * El campo 'referencia_pago' es CRÍTICO para buscar y actualizar el estado.
     */

     /**
      * Actualiza el estado de la donación usando la referencia de Wompi.
      * @param string $reference La referencia única de pago de Wompi.
      * @param string $status El nuevo estado ('aprobada', 'fallida', etc.).
      * @param string $metodo_pago El método de pago.
      * @return bool
      */
     public function updateStatusByReference(string $reference, string $status, string $metodo_pago): bool {
        $sql = "UPDATE {$this->table} 
                SET estado = :status, metodo_pago = :metodo, fecha_pago = NOW(), updated_at = NOW() 
                WHERE referencia_pago = :reference";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':metodo', $metodo_pago);
            $stmt->bindParam(':reference', $reference);
            
            $stmt->execute();

            // 💡 Corrección: Devuelve true solo si se afectó al menos 1 fila.
            return $stmt->rowCount() > 0;

        } catch (PDOException $e) {
            error_log("Error al actualizar donación por referencia: " . $e->getMessage());
            return false;
        }
     }
     
    /**
     * Busca un registro por su referencia de pago (Wompi).
     * @param string $reference
     * @return array|false
     */
    public function findByReference(string $reference) {
        $sql = "SELECT * FROM {$this->table} WHERE referencia_pago = :reference";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':reference', $reference);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error al buscar donación por referencia: " . $e->getMessage());
            return false;
        }
    }

}