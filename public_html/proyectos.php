<?php

// 1. INICIALIZACIÓN: Cargar todas las configuraciones y el Autoload
require_once __DIR__ . '/../fundacion_core/config/bootstrap.php';

use App\Models\Proyecto;

$proyectos = [];
$error_message = null; 
try {
    $proyectoModel = new Proyecto();
    $proyectos = $proyectoModel->findAllActive(); 
} catch (\Exception $e) {
    error_log("Error al cargar proyectos: " . $e->getMessage());
    $error_message = 'No pudimos cargar los proyectos en este momento. Inténtalo más tarde.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyectos - Mi Sitio Web</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo URL_BASE; ?>/index">Mi Sitio</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="<?php echo URL_BASE; ?>/index">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo URL_BASE; ?>/nosotros">Nosotros</a></li>
                    <li class="nav-item"><a class="nav-link active" href="<?php echo URL_BASE; ?>/proyectos">Proyectos</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo URL_BASE; ?>/donar">Donar</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo URL_BASE; ?>/contactanos">Contactanos</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Nuestros Proyectos en Acción</h1>
        <p class="lead">Descubre las iniciativas que estamos llevando a cabo y cómo tu apoyo se transforma en un impacto real.</p>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="row mt-4">
            <?php if (empty($proyectos)): ?>
                <div class="col-12">
                    <div class="alert alert-info" role="alert">
                        Actualmente no hay proyectos activos. ¡Vuelve pronto!
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($proyectos as $proyecto): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <?php if (!empty($proyecto['imagen_principal'])): ?>
                                <img src="<?php echo URL_BASE; ?>/uploads/<?php echo htmlspecialchars($proyecto['imagen_principal']); ?>" 
                                     class="card-img-top" 
                                     alt="<?php echo htmlspecialchars($proyecto['nombre']); ?>"
                                     style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-light text-center p-5" style="height: 200px;">
                                    <p class="text-muted">Sin Imagen</p>
                                </div>
                            <?php endif; ?>

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?php echo htmlspecialchars($proyecto['nombre']); ?></h5>
                                <p class="card-text flex-grow-1">
                                    <?php echo nl2br(htmlspecialchars(substr($proyecto['descripcion'], 0, 150))) . '...'; ?>
                                </p>
                                
                                <?php 
                                    $badge_class = '';
                                    if ($proyecto['estado'] === 'activo') {
                                        $badge_class = 'bg-success';
                                    } elseif ($proyecto['estado'] === 'en_progreso') {
                                        $badge_class = 'bg-warning text-dark';
                                    } 
                                ?>
                                <span class="badge <?php echo $badge_class; ?> mb-3 text-capitalize">
                                    <?php echo htmlspecialchars(str_replace('_', ' ', $proyecto['estado'])); ?>
                                </span>
                                
                                <div class="d-flex justify-content-between mt-auto">
                                    <a href="<?php echo URL_BASE; ?>/ver_mas.php?id=<?php echo $proyecto['id']; ?>" 
                                       class="btn btn-sm btn-primary">
                                        Ver Más
                                    </a>
                                    
                                    <a href="<?php echo URL_BASE; ?>/donar.php?proyecto_id=<?php echo $proyecto['id']; ?>" 
                                       class="btn btn-sm btn-success">
                                        Donar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>