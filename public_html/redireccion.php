<?php
require_once __DIR__ . '/../fundacion_core/config/bootstrap.php';

$status = $_GET['status'] ?? 'ERROR';
$referencia = $_GET['ref'] ?? 'N/A';

// Configuramos los mensajes según el estado de Wompi
$config = [
    'APPROVED' => [
        'titulo' => '¡Donación Exitosa!',
        'clase' => 'success',
        'icono' => '✅',
        'mensaje' => 'Muchas gracias por tu generosidad. Tu apoyo nos ayuda a seguir creciendo.'
    ],
    'DECLINED' => [
        'titulo' => 'Transacción Declinada',
        'clase' => 'danger',
        'icono' => '❌',
        'mensaje' => 'Lo sentimos, la entidad financiera rechazó la transacción. Intenta con otro medio de pago.'
    ],
    'ERROR' => [
        'titulo' => 'Error en el Pago',
        'clase' => 'warning',
        'icono' => '⚠️',
        'mensaje' => 'Ocurrió un error inesperado durante el proceso. Por favor, intenta más tarde.'
    ],
    'VOIDED' => [
        'titulo' => 'Transacción Anulada',
        'clase' => 'secondary',
        'icono' => '🚫',
        'mensaje' => 'La transacción ha sido cancelada.'
    ]
];

// Si el estado no existe en nuestra lista, usamos el de ERROR por defecto
$resultado = $config[$status] ?? $config['ERROR'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $resultado['titulo']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .status-icon { font-size: 4rem; }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg text-center border-0">
                    <div class="card-header bg-<?php echo $resultado['clase']; ?> text-white p-4">
                        <div class="status-icon"><?php echo $resultado['icono']; ?></div>
                        <h2><?php echo $resultado['titulo']; ?></h2>
                    </div>
                    <div class="card-body p-5">
                        <p class="lead"><?php echo $resultado['mensaje']; ?></p>
                        <div class="bg-light p-3 rounded mb-4">
                            <small class="text-muted d-block">Referencia de pago:</small>
                            <strong><?php echo htmlspecialchars($referencia); ?></strong>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <?php if($status === 'APPROVED'): ?>
                                <a href="index" class="btn btn-primary btn-lg">Volver al Inicio</a>
                            <?php else: ?>
                                <a href="donar" class="btn btn-<?php echo $resultado['clase']; ?> btn-lg">Reintentar Donación</a>
                                <a href="index" class="btn btn-link text-muted">Ir al inicio</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>