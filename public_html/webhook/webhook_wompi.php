<?php
// /public/webhook/webhook_wompi.php (CORREGIDO)

// 1. INICIALIZACIÓN Y CARGA DE CLASES
// Subir 2 niveles (webhook, public) para llegar a /config
require_once __DIR__ . '/../../config/bootstrap.php'; 

use App\Models\Donacion;
use App\Models\Proyecto; 
use App\Helpers\MailHelper; 

// Las variables de entorno ya están cargadas por el bootstrap.php
$WOMPI_INTEGRITY_KEY = $_ENV['WOMPI_INTEGRITYKEY']; 

// 2. CAPTURA DE DATOS Y RESPUESTA INICIAL

// CORRECCIÓN CRÍTICA: Priorizar la variable global de simulación
if (isset($GLOBALS['WOMPI_SIMULATED_INPUT'])) {
    $input = $GLOBALS['WOMPI_SIMULATED_INPUT'];
} else {
    // Si no es simulación, lee del flujo normal (Wompi real)
    $input = file_get_contents('php://input');
}

$event = json_decode($input, true);

// Las cabeceras DEBEN ir antes de cualquier 'echo' (incluso el JSON de error)
header('Content-Type: application/json');
http_response_code(200); 

if (!$event || !isset($event['data']['transaction'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Wompi payload']);
    exit;
}

$transaction = $event['data']['transaction'];
$metadata = $transaction['metadata'] ?? []; 
$customerEmail = $transaction['customer_email'] ?? 'correo@desconocido.com'; 

// Datos relevantes de la transacción
$reference = $transaction['reference'];
$status = $transaction['status']; 
$amountInCents = $transaction['amount_in_cents'];
$currency = $transaction['currency'];
$integrityHash = $transaction['signature']['checksum'] ?? ''; 

// CLAVE: Obtener el ID del proyecto desde los metadatos
$proyectoId = $metadata['proyecto_id'] ?? null; 
$customerName = $metadata['customer_name'] ?? explode('@', $customerEmail)[0];


// 3. VERIFICAR EL HASH DE INTEGRIDAD (Seguridad)
$chain = "{$reference}{$amountInCents}{$currency}{$WOMPI_INTEGRITY_KEY}";
$localSignature = hash('sha256', $chain);

if ($localSignature !== $integrityHash) {
    error_log("Alerta de Seguridad: Firma de integridad inválida para referencia {$reference}");
    echo json_encode(['status' => 'error', 'message' => 'Integrity signature check failed']);
    exit;
}

// 4. PREPARACIÓN DE DATOS Y MODELOS
$donacionModel = new Donacion();
$metodoPago = $transaction['payment_method_type'] ?? 'Desconocido';
$dbStatus = 'fallida';

// Determinar el estado final
if ($status === 'APPROVED') {
    $dbStatus = 'aprobada';
} elseif ($status === 'VOIDED' || $status === 'DECLINED') {
    $dbStatus = 'fallida';
}

// 5. ACTUALIZAR BD Y BUSCAR NOMBRE DE PROYECTO
$updateSuccess = $donacionModel->updateStatusByReference($reference, $dbStatus, $metodoPago, $proyectoId);

// 6. MANEJO DE ÉXITO (APROBADA) Y CORREO DE AGRADECIMIENTO
if ($updateSuccess) {
    
    if ($status === 'APPROVED') {
        
        // 6.1. Buscar Nombre del Proyecto para el Correo
        $proyectoNombre = 'Fondos Generales';
        if ($proyectoId) {
            $proyectoModel = new Proyecto();
            $proyectoInfo = $proyectoModel->find($proyectoId); 
            if ($proyectoInfo && isset($proyectoInfo['nombre'])) {
                $proyectoNombre = $proyectoInfo['nombre'];
            }
        }

        // 6.2. Enviar Correo de Agradecimiento
        $mailHelper = new MailHelper(); 
        
        $emailData = [
            'nombre' => $customerName,
            'email' => $customerEmail,
            'monto_centavos' => $amountInCents,
            'moneda' => $currency,
            'referencia' => $reference,
            'proyecto_nombre' => $proyectoNombre
        ];
        
        $mailHelper->sendDonationThankYou($emailData);

        echo json_encode(['status' => 'success', 'message' => 'Donation approved, status updated, and email sent.']);

    } else {
        echo json_encode(['status' => 'success', 'message' => 'Donation status updated.']);
    }
} else {
    // 7. MANEJO DE ERRORES DE BASE DE DATOS
    error_log("Error al actualizar la donación con referencia: {$reference} en la BD.");
    echo json_encode(['status' => 'error', 'message' => 'Error updating donation in DB or reference not found.']);
}