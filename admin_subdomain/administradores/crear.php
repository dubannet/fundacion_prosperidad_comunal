<?php
require_once __DIR__ . '/../../fundacion_core/config/bootstrap.php';

use App\Helpers\AuthHelper;
use App\Controllers\AdminController;

$authHelper = new AuthHelper();
// 1. Requerir login y rol 'superadmin'
$authHelper->requireRole('superadmin'); 

$controller = new AdminController();
$error = '';
$nombre = '';
$email = '';
$rol = 'admin';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    
    // 2. Llamar al controlador para guardar
    $result = $controller->store($data);

    if ($result === true) {
        // Éxito: Redirigir al listado con mensaje de éxito
        header('Location: index.php?status=success&message=' . urlencode('Administrador creado con éxito.'));
        exit;
    } else {
        // Fallo: Mostrar el mensaje de error del controlador
        $error = $result;
        // Mantener datos para rellenar el formulario (excepto password)
        $nombre = $data['nombre'] ?? '';
        $email = $data['email'] ?? '';
        $rol = $data['rol'] ?? 'admin';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Crear Nuevo Administrador</h1>
            <a href="index.php" class="btn btn-secondary">← Volver al Listado</a>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="crear.php">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <div class="form-text">Mínimo 8 caracteres.</div>
            </div>

            <div class="mb-3">
                <label for="rol" class="form-label">Rol</label>
                <select class="form-select" id="rol" name="rol" required>
                    <option value="admin" <?php echo ($rol === 'admin' ? 'selected' : ''); ?>>Admin</option>
                    <option value="superadmin" <?php echo ($rol === 'superadmin' ? 'selected' : ''); ?>>Superadmin</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Guardar Administrador</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>