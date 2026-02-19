<?php
require_once __DIR__ . '/../fundacion_core/config/bootstrap.php';
use App\Controllers\RecoveryController;

$message = '';
$alertClass = 'alert-info';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if ($email) {
        $controller = new RecoveryController();
        $controller->sendResetEmail($email);
        // Mensaje genérico por seguridad
        $message = "Si el correo está registrado, recibirás un enlace en unos minutos.";
    } else {
        $message = "Por favor, ingresa un correo válido.";
        $alertClass = 'alert-danger';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h3>¿Olvidaste tu clave?</h3>
                        <p class="text-muted">Ingresa tu correo y te enviaremos un enlace para recuperarla.</p>
                        
                        <?php if ($message): ?>
                            <div class="alert <?php echo $alertClass; ?>"><?php echo $message; ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Enviar Enlace</button>
                        </form>
                        <div class="mt-3 text-center">
                            <a href="index.php" class="text-decoration-none small">Volver al Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>