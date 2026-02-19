<?php
require_once __DIR__ . '/../../fundacion_core/config/bootstrap.php';

use App\Helpers\AuthHelper;
use App\Controllers\ProyectoController;

$authHelper = new AuthHelper();
$authHelper->requireLogin(); 

$controller = new ProyectoController();
$proyectos = $controller->index();

$status = $_GET['status'] ?? null;
$message = $_GET['message'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Proyectos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Gestión de Proyectos</h1>
            <a href="../dashboard.php" class="btn btn-secondary">← Volver al Dashboard</a>
        </div>

        <?php if ($status === 'success'): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php elseif ($status === 'error'): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <div class="mb-4">
            <a href="crear.php" class="btn btn-primary">Crear Nuevo Proyecto</a>
        </div>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Estado</th>
                    <th>Creado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($proyectos)): ?>
                    <tr><td colspan="6" class="text-center">No hay proyectos registrados.</td></tr>
                <?php else: ?>
                    <?php foreach ($proyectos as $p): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($p['id']); ?></td>
                            <td>
                                <?php if (!empty($p['imagen_principal'])): ?>
                                    <img src="/fundacionfinal/public/uploads/<?php echo htmlspecialchars($p['imagen_principal']); ?>" 
                                         alt="Imagen" style="width: 50px; height: 50px; object-fit: cover;">
                                <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($p['nombre']); ?></td>
                            <td>
                                <span class="badge text-capitalize <?php 
                                    echo ($p['estado'] === 'activo' ? 'bg-success' : 
                                         ($p['estado'] === 'en_progreso' ? 'bg-warning text-dark' : 'bg-secondary')); 
                                ?>">
                                    <?php echo htmlspecialchars(str_replace('_', ' ', $p['estado'])); ?>
                                </span>
                            </td>
                            <td><?php echo date('Y-m-d', strtotime($p['created_at'])); ?></td>
                            <td>
                                <a href="editar.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-info">Editar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>