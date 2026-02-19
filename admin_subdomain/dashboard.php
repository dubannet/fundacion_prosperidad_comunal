<?php
/*ini_set('display_errors', 1);
error_reporting(E_ALL);*/

require_once __DIR__ . '/../fundacion_core/config/bootstrap.php';
use App\Helpers\AuthHelper;

$authHelper = new AuthHelper();
$authHelper->requireLogin();

$adminName = $_SESSION['admin_name'] ?? 'Usuario';
$adminRol = $_SESSION['admin_rol'] ?? 'admin';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Bienvenido, <?php echo htmlspecialchars($adminName); ?> (<?php echo htmlspecialchars($adminRol); ?>)</h1>
            <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>

        <p class="lead">Panel de Administración de la Fundación.</p>

        <div class="row">
            <div class="col-md-6 mb-3">
                <a href="proyectos/index.php" class="btn btn-primary w-100 p-3">
                    Administrar Proyectos
                </a>
            </div>

            <div class="col-md-6 mb-3">
                <a href="donaciones/index.php" class="btn btn-success w-100 p-3">
                    Ver Donaciones
                </a>
            </div>

            <?php if ($authHelper->isRole('superadmin')): ?>
            <div class="col-md-6 mb-3">
                <a href="administradores/index.php" class="btn btn-warning text-dark w-100 p-3">
                    Gestionar Administradores
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>