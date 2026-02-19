<?php
require_once __DIR__ . '/../fundacion_core/config/bootstrap.php';
use App\Controllers\RecoveryController;
use App\Models\Admin;

$token = $_GET['token'] ?? '';
$error = '';
$success = false;

// 1. Validar que el token exista y sea válido antes de mostrar el formulario
$adminModel = new Admin();
$admin = $adminModel->findByResetToken($token);

if (!$admin && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("El enlace es inválido o ha expirado. <a href='recuperar.php'>Solicita uno nuevo aquí</a>.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm) {
        $error = "Las contraseñas no coinciden.";
    } elseif (strlen($password) < 8) {
        $error = "La contraseña debe tener al menos 8 caracteres.";
    } else {
        $controller = new RecoveryController();
        $result = $controller->resetPassword($token, $password);
        
        if ($result === true) {
            $success = true;
        } else {
            $error = $result;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h3>Nueva Contraseña</h3>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success">Contraseña actualizada con éxito. Ya puedes iniciar sesión.</div>
                            <a href="index.php" class="btn btn-primary w-100">Ir al Login</a>
                        <?php else: ?>
                            <?php if ($error): ?> <div class="alert alert-danger"><?php echo $error; ?></div> <?php endif; ?>

                            <form method="POST">
                                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                                
                                <div class="mb-3">
                                    <label class="form-label">Nueva Contraseña</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Confirmar Contraseña</label>
                                    <input type="password" name="confirm_password" class="form-control" required>
                                </div>
                                
                                <button type="submit" class="btn btn-success w-100">Cambiar Contraseña</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>