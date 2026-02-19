<?php
// /public/admin/proyectos/editar.php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
require_once __DIR__ . '/../../fundacion_core/config/bootstrap.php';

use App\Helpers\AuthHelper;
use App\Controllers\ProyectoController;

$authHelper = new AuthHelper();
$authHelper->requireLogin();

$controller = new ProyectoController();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$error = '';
$success = '';

if (!$id) {
    header('Location: index.php?status=error&message=' . urlencode('ID de proyecto no válido.'));
    exit;
}

// Lógica para Borrar Multimedia Específica
$delete_media_id = filter_input(INPUT_GET, 'delete_media_id', FILTER_VALIDATE_INT);
if ($delete_media_id) {
    $result = $controller->deleteMedia($delete_media_id);
    if ($result === true) {
        $success = 'Archivo multimedia eliminado lógicamente con éxito.';
    } else {
        $error = "Error al eliminar multimedia: " . $result;
    }
    // Redirigir para limpiar el GET y evitar re-ejecución
    header('Location: editar.php?id=' . $id . '&status=' . ($success ? 'success' : 'error'));
    exit;
}

// 1. Obtener proyecto y multimedia
$proyectoData = $controller->find($id);

if (!$proyectoData) {
    header('Location: index.php?status=error&message=' . urlencode('Proyecto no encontrado.'));
    exit;
}

// Separar los datos
$proyecto = $proyectoData['proyecto'];
$multimedia = $proyectoData['multimedia']; // <-- Nueva variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 2. Procesar POST (Actualización)
    $data = $_POST;
    // Pasar todo $_FILES, incluyendo 'imagen' y 'multimedia'
    $files = $_FILES; 

    $result = $controller->update($id, $data, $files);

    if ($result === true) {
        $success = 'Proyecto y archivos principales actualizados con éxito.';
        // Recargar datos actualizados para el formulario y multimedia
        $proyectoData = $controller->find($id); 
        $proyecto = $proyectoData['proyecto'];
        $multimedia = $proyectoData['multimedia'];
    } else {
        $error = $result;
    }
} else {
    // Si se viene de una redirección por éxito/error de eliminación de media
    $status = $_GET['status'] ?? null;
    if ($status === 'success') {
        $success = 'Operación de multimedia realizada con éxito.';
    } elseif ($status === 'error') {
        // Podrías intentar recuperar el mensaje de error de la URL si se pasó
        $error = $_GET['message'] ?? 'Ocurrió un error en la operación de multimedia.';
    }
}

// Rellenar variables con los datos del proyecto
$nombre = $proyecto['nombre'];
$descripcion = $proyecto['descripcion'];
$estado = $proyecto['estado'];
$imagen_principal = $proyecto['imagen_principal'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Proyecto: <?php echo htmlspecialchars($nombre); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Editar Proyecto: <?php echo htmlspecialchars($nombre); ?></h1>
            <a href="index.php" class="btn btn-secondary">← Volver al Listado</a>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" action="editar.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
            
            <fieldset class="mb-5 p-3 border rounded">
                <legend class="float-none w-auto px-2 fs-5">Datos Principales</legend>
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
            </fieldset>

            <fieldset class="mb-5 p-3 border rounded">
                <legend class="float-none w-auto px-2 fs-5">Imagen Principal</legend>
                <div class="mb-3">
                    <label for="imagen" class="form-label">Imagen Principal</label>
                    <?php if ($imagen_principal): ?>
                        <div class="mb-2">
                            <p class="text-muted">Imagen Actual:</p>
                            <img src="<?php echo URL_BASE; ?>/uploads/<?php echo htmlspecialchars($imagen_principal); ?>" 
                                 alt="Imagen actual" style="width: 150px; height: 100px; object-fit: cover;">
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                    <div class="form-text">Sube una nueva imagen para reemplazar la actual.</div>
                </div>
            </fieldset>

            <fieldset class="mb-5 p-3 border rounded">
                <legend class="float-none w-auto px-2 fs-5">Galería de Multimedia (Carrusel)</legend>

                <h5 class="mt-3">Archivos Actuales (<?php echo count($multimedia); ?>)</h5>
                <?php if (!empty($multimedia)): ?>
                    <div class="row row-cols-2 row-cols-md-4 g-3 mb-4">
                        <?php foreach ($multimedia as $media): ?>
                            <div class="col">
                                <div class="card h-100 position-relative">
                                    <?php 
                                        $media_url = URL_BASE . '/uploads/' . htmlspecialchars($media['ruta']);
                                        $is_video = $media['tipo'] === 'video';
                                    ?>
                                    <?php if ($is_video): ?>
                                        <video controls class="card-img-top" style="height: 120px; object-fit: cover;">
                                            <source src="<?php echo $media_url; ?>">
                                            Tu navegador no soporta videos.
                                        </video>
                                        <span class="badge bg-info text-dark position-absolute top-0 start-0 m-1">Video</span>
                                    <?php else: ?>
                                        <img src="<?php echo $media_url; ?>" class="card-img-top" style="height: 120px; object-fit: cover;" alt="Multimedia">
                                        <span class="badge bg-success position-absolute top-0 start-0 m-1">Imagen</span>
                                    <?php endif; ?>

                                    <div class="card-body p-2">
                                        <a href="editar.php?id=<?php echo $id; ?>&delete_media_id=<?php echo $media['id']; ?>" 
                                           class="btn btn-danger btn-sm w-100"
                                           onclick="return confirm('¿Estás seguro de que deseas eliminar este archivo multimedia?')">
                                            Eliminar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">Este proyecto no tiene multimedia adicional.</div>
                <?php endif; ?>

                <h5 class="mt-4">Añadir Nueva Multimedia</h5>
                <div class="mb-3">
                    <label for="multimedia" class="form-label">Subir Nuevas Imágenes/Videos</label>
                    <input type="file" class="form-control" id="multimedia" name="multimedia[]" accept="image/*,video/*" multiple>
                    <div class="form-text">Los nuevos archivos se añadirán a la galería existente al guardar los cambios.</div>
                </div>
            </fieldset>

            <button type="submit" class="btn btn-primary btn-lg">Guardar Todos los Cambios</button>
            <a href="borrar.php?id=<?php echo $id; ?>" class="btn btn-danger float-end" 
               onclick="return confirm('¿Estás seguro de que deseas ELIMINAR este proyecto? Se marcará como finalizado.')">
                Eliminar Proyecto (Lógico)
            </a>
        </form>
    </div>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</body>
</html>