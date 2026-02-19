<?php
// DEBUG ACTIVADO
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

require_once __DIR__ . '/../fundacion_core/config/bootstrap.php';





use App\Controllers\AuthController;
use App\Helpers\AuthHelper;


$authController = new AuthController();
$authHelper = new AuthHelper();

$error_message = '';
$error_type = $_GET['error'] ?? '';
if ($error_type === 'timeout') {
    $error_message = 'Su sesión ha expirado por inactividad. Por favor, ingrese de nuevo.';
} elseif ($error_type === 'no_permission') {
    $error_message = 'No tiene permisos para acceder a esa sección.';
}

// Si ya está logeado, redirigir al dashboard
if ($authHelper->isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

// Procesar el formulario POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        if ($authController->login($email, $password)) {
            header('Location: dashboard.php');
            exit;
        } else {
            $error_message = 'Credenciales inválidas o cuenta inactiva.';
        }
    } else {
        $error_message = 'Por favor, ingrese email y contraseña.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .login-container {
            max-width: 400px;
            margin-top: 100px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 login-container">
                <div class="card shadow-lg">
                    <div class="card-header bg-dark text-white text-center">
                        <h4>Acceso al Panel de Administración</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="index.php">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                            <div class="mt-3 text-center">
                                <a href="recuperar.php" class="text-decoration-none small">¿Olvidaste tu contraseña?</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>