<?php
require_once __DIR__ . '/../../fundacion_core/config/bootstrap.php';

use App\Helpers\AuthHelper;
use App\Controllers\AdminController;

$authHelper = new AuthHelper();
// 1. Requerir login y rol 'superadmin'
$authHelper->requireRole('superadmin'); 

$controller = new AdminController();
$admins = $controller->index();

$status = $_GET['status'] ?? null;
$message = $_GET['message'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Administradores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Gestión de Administradores</h1>
            <a href="../dashboard.php" class="btn btn-secondary">← Volver al Dashboard</a>
        </div>

        <?php if ($status === 'success'): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php elseif ($status === 'error'): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <div class="mb-4">
            <a href="crear.php" class="btn btn-primary">Crear Nuevo Administrador</a>
        </div>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($admins)): ?>
                    <tr><td colspan="6" class="text-center">No hay administradores registrados.</td></tr>
                <?php else: ?>
                    <?php foreach ($admins as $a): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($a['id']); ?></td>
                            <td><?php echo htmlspecialchars($a['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($a['email']); ?></td>
                            <td><span class="badge text-capitalize <?php echo ($a['rol'] === 'superadmin' ? 'bg-danger' : 'bg-info'); ?>"><?php echo htmlspecialchars($a['rol']); ?></span></td>
                            <td><?php echo ($a['estado'] == 1 ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>'); ?></td>
                            <td>
                                <a href="editar.php?id=<?php echo $a['id']; ?>" class="btn btn-sm btn-info">Editar</a>
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