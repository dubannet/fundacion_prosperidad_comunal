<?php
// MUESTRA ERRORES EN PANTALLA
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

require_once __DIR__ . '/../../fundacion_core/config/bootstrap.php';

use App\Helpers\AuthHelper;
use App\Controllers\ProyectoController;
// ...

$authHelper = new AuthHelper();
$authHelper->requireLogin();

$controller = new ProyectoController();
$error = '';
$nombre = '';
$descripcion = '';
$estado = 'activo';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Recoger datos
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $estado = $_POST['estado'] ?? 'activo';
    // La imagen principal y la multimedia adicional están en $_FILES
   
    $data = ['nombre' => $nombre, 'descripcion' => $descripcion, 'estado' => $estado];

    // 2. Llamar al controlador para guardar (pasamos todo $_FILES)
    // El controlador ahora espera las claves 'imagen' y 'multimedia' dentro de $_FILES
    $result = $controller->store($data, $_FILES); 

    if ($result === true) {
        header('Location: index.php?status=success&message=' . urlencode('Proyecto creado con éxito.'));
        exit;
    } else {
        $error = $result;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Proyecto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Crear Nuevo Proyecto</h1>
            <a href="index.php" class="btn btn-secondary"> Volver al Listado</a>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="crear.php" enctype="multipart/form-data">
            
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Proyecto</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="5" required><?php echo htmlspecialchars($descripcion); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado" required>
                    <option value="activo" <?php echo ($estado === 'activo' ? 'selected' : ''); ?>>Activo</option>
                    <option value="en_progreso" <?php echo ($estado === 'en_progreso' ? 'selected' : ''); ?>>En Progreso</option>
                    <option value="finalizado" <?php echo ($estado === 'finalizado' ? 'selected' : ''); ?>>Finalizado</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen Principal</label>
                <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                <div class="form-text">JPG, PNG o GIF permitidos. Máximo 2MB (ajustar en php.ini).</div>
            </div>
            
            <hr>
            
            <div class="mb-3">
                <label for="multimedia" class="form-label">Multimedia Adicional (Carrusel)</label>
                <input type="file" class="form-control" id="multimedia" name="multimedia[]" accept="image/*,video/*" multiple>
                <div class="form-text">Permite subir múltiples imágenes (JPG, PNG, GIF) o videos (MP4, WebM).</div>
            </div>

            <button type="submit" class="btn btn-primary">Guardar Proyecto</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>