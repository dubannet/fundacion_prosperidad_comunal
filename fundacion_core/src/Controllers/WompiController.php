<?php

namespace App\Controllers;


class WompiController {

    private $integrityKey;

    public function __construct() {
        
        $this->integrityKey = $_ENV["WOMPI_INTEGRITYKEY"] ?? '';
    }

    /**
     * Genera una referencia única para la transacción.
     */
    private function generarReferenciaUnica(?int $proyectoId): string {
        $timestamp = time();
        $randomHex = bin2hex(random_bytes(4));
        $proyectoStr = $proyectoId ?: 0;
        
        return "FD-{$proyectoStr}-{$timestamp}-{$randomHex}";
    }
    
    /**
     * Genera la firma de integridad para el Checkout de Wompi.
     */
    public function generarFirma() {
        // Importante: Asegurar que la respuesta sea JSON
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }
        
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido.']);
            return;
        }

        // 1. Recibir datos
        $amountInCents = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_INT);
        $currency = filter_input(INPUT_POST, 'currency', FILTER_SANITIZE_SPECIAL_CHARS) ?: 'COP';
        $proyectoId = filter_input(INPUT_POST, 'proyecto_id', FILTER_VALIDATE_INT); 
        $publicKey = $_ENV['WOMPI_PUBLIC_KEY'] ?? '';

        // Validación mínima (Wompi pide mínimo 1500 COP usualmente, tú tienes 1000)
        if (!$amountInCents || $amountInCents < 100000) {
            http_response_code(400);
            echo json_encode(['error' => 'Monto inválido (mínimo $1.000 COP).']);
            return;
        }

        // 2. Generar Referencia
        $reference = $this->generarReferenciaUnica($proyectoId);

        // 3. Crear Firma de Integridad (SHA256)
        // El orden es: referencia + monto_en_centavos + moneda + llave_secreta
        $cadena = $reference . (string)$amountInCents . $currency . $this->integrityKey;
        $signature = hash('sha256', $cadena);

        // 4. Respuesta para el Frontend
        echo json_encode([
            'signature' => $signature,
            'reference' => $reference,
            'publicKey' => $publicKey,
            'amountInCents' => $amountInCents,
            'currency' => $currency,
            'proyecto_id' => $proyectoId
        ]);
    }
}