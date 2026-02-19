<?php
require_once __DIR__ . '/../../fundacion_core/config/bootstrap.php';

use App\Helpers\AuthHelper;
use App\Controllers\AdminController;

$authHelper = new AuthHelper();
$authHelper->requireRole('superadmin'); 

$controller = new AdminController();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$error = '';
$success = '';

if (!$id) {
    header('Location: index.php');
    exit;
}

$admin = $controller->find($id);

if (!$admin) {
    header('Location: index.php?status=error&message=Admin+no+encontrado');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->update($id, $_POST);
    if ($result === true) {
        $success = 'Administrador actualizado con éxito.';
        $admin = $controller->find($id);
    } else {
        $error = $result;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Editar: <?php echo htmlspecialchars($admin['nombre']); ?></h2>
                <a href="index.php" class="btn btn-outline-secondary btn-sm">Volver</a>
            </div>
            <div class="card-body">
                <?php if ($error): ?> <div class="alert alert-danger"><?php echo $error; ?></div> <?php endif; ?>
                <?php if ($success): ?> <div class="alert alert-success"><?php echo $success; ?></div> <?php endif; ?>

                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($admin['nombre']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($admin['email']); ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Rol</label>
                            <select name="rol" class="form-select">
                                <option value="admin" <?php echo ($admin['rol'] === 'admin' ? 'selected' : ''); ?>>Admin</option>
                                <option value="superadmin" <?php echo ($admin['rol'] === 'superadmin' ? 'selected' : ''); ?>>Superadmin</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select">
                                <option value="1" <?php echo ($admin['estado'] == 1 ? 'selected' : ''); ?>>Activo</option>
                                <option value="0" <?php echo ($admin['estado'] == 0 ? 'selected' : ''); ?>>Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Nueva Contraseña</label>
                        <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para no cambiar">
                        <small class="text-muted">Solo rellena este campo si deseas cambiar la contraseña del usuario.</small>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>