<?php
// /opt/lampp/htdocs/fundacionfinal/public/admin/donaciones/index.php

require_once __DIR__ . '/../../fundacion_core/config/bootstrap.php';

use App\Helpers\AuthHelper;
use App\Controllers\DonacionController;

$authHelper = new AuthHelper();
// 1. Proteger la página (solo requiere estar logeado)
$authHelper->requireLogin(); 

$controller = new DonacionController();
$donaciones = $controller->index();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Donaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Donaciones Recibidas</h1>
            <a href="../dashboard.php" class="btn btn-secondary">← Volver al Dashboard</a>
        </div>

        <p class="lead">Listado de transacciones de pago notificadas por Wompi.</p>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>Ref. Pago</th>
                        <th>Monto (COP)</th>
                        <th>Estado</th>
                        <th>Donante</th>
                        <th>Correo</th>
                        <th>Proyecto ID</th>
                        <th>Método</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($donaciones)): ?>
                        <tr><td colspan="8" class="text-center">Aún no se han registrado donaciones.</td></tr>
                    <?php else: ?>
                        <?php foreach ($donaciones as $d): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($d['referencia_pago']); ?></td>
                                <td>$<?php echo number_format($d['monto'], 0, ',', '.'); ?></td>
                                <td>
                                    <?php 
                                        $badgeClass = 'bg-secondary';
                                        if ($d['estado'] === 'aprobada') $badgeClass = 'bg-success';
                                        if ($d['estado'] === 'pendiente') $badgeClass = 'bg-warning text-dark';
                                        if ($d['estado'] === 'fallida') $badgeClass = 'bg-danger';
                                    ?>
                                    <span class="badge text-capitalize <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($d['estado']); ?></span>
                                </td>
                                <td><?php echo htmlspecialchars($d['nombre_donante']); ?></td>
                                <td><?php echo htmlspecialchars($d['correo']); ?></td>
                                <td><?php echo htmlspecialchars($d['proyecto_id']); ?></td>
                                <td><?php echo htmlspecialchars($d['metodo_pago']); ?></td>
                                <td><?php echo date('Y-m-d H:i', strtotime($d['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>