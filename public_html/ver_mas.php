<?php
// /opt/lampp/htdocs/fundacionfinal/public/ver_mas.php

// 1. INICIALIZACIÓN: Cargar configuraciones y Autoload
require_once __DIR__ . '/../fundacion_core/config/bootstrap.php'; 

use App\Models\Proyecto;

// 2. LÓGICA DE DATOS
$proyecto = null;
$multimedia = [];
$error_message = null; 
$proyecto_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Verificar ID
if (!$proyecto_id) {
    $error_message = "ID de proyecto no especificado o inválido.";
} else {
    try {
        $proyectoModel = new Proyecto();
        
        // Usamos el método findWithMedia() del modelo Proyecto.php
        $raw_data = $proyectoModel->findWithMedia($proyecto_id); 

        if (empty($raw_data)) {
            $error_message = "El proyecto solicitado no existe o fue eliminado.";
        } else {
            // 3. PROCESAR DATOS: Reestructurar la respuesta para separar el proyecto principal de la multimedia
            // El primer elemento contiene los datos principales del proyecto
            $proyecto = array_slice($raw_data[0], 0, 5); // Tomamos solo los primeros 5 campos (id, nombre, descripcion, estado, imagen_principal)

            // Inicializamos la lista de media con la imagen principal
            $multimedia = [];
            if (!empty($proyecto['imagen_principal'])) {
                $multimedia[] = [
                    'ruta' => $proyecto['imagen_principal'],
                    'tipo' => 'imagen_principal'
                ];
            }
            
            // Recorremos el resto de las filas (si hay JOINs) para obtener la media adicional
            foreach ($raw_data as $row) {
                // Solo si la ruta existe y no es la imagen principal (asumiendo que imagen_principal no se repite en proyecto_media)
                if (!empty($row['media_ruta'])) {
                    $multimedia[] = [
                        'ruta' => $row['media_ruta'],
                        'tipo' => $row['media_tipo']
                    ];
                }
            }
            
            // Si el proyecto tenía una imagen principal, la ponemos al inicio del array multimedia (si no la pusimos arriba)
            // Ya la pusimos arriba, este paso no es necesario si se hizo correctamente.
        }
    } catch (\Exception $e) {
        error_log("Error al cargar detalles del proyecto ID {$proyecto_id}: " . $e->getMessage());
        $error_message = 'Ocurrió un error al cargar los detalles. Inténtalo más tarde.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $proyecto ? htmlspecialchars($proyecto['nombre']) : 'Detalle del Proyecto'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo URL_BASE; ?>/index.php">Mi Sitio</a>
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

    <div class="container mt-5">
        <?php if ($error_message): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error_message); ?>
                <p class="mt-2"><a href="<?php echo URL_BASE; ?>/proyectos" class="btn btn-sm btn-info">Volver a Proyectos</a></p>
            </div>
        <?php else: ?>
            
            <div class="row">
                <div class="col-md-7 mb-4">
                    <h2>Galería de Imágenes</h2>
                    <?php if (!empty($multimedia)): ?>
                        <div id="proyectoCarrusel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <?php $i = 0; foreach ($multimedia as $media): ?>
                                    <div class="carousel-item <?php echo ($i === 0 ? 'active' : ''); ?>">
                                        <img src="<?php echo URL_BASE; ?>/uploads/<?php echo htmlspecialchars($media['ruta']); ?>" 
                                             class="d-block w-100 rounded" 
                                             alt="Imagen del proyecto"
                                             style="height: 450px; object-fit: cover;">
                                    </div>
                                <?php $i++; endforeach; ?>
                            </div>
                            
                            <?php if (count($multimedia) > 1): ?>
                                <button class="carousel-control-prev" type="button" data-bs-target="#proyectoCarrusel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#proyectoCarrusel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Siguiente</span>
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">No hay imágenes disponibles para este proyecto.</div>
                    <?php endif; ?>
                </div>

                <div class="col-md-5 mb-4">
                    <h1><?php echo htmlspecialchars($proyecto['nombre']); ?></h1>
                    
                    <?php 
                        $badge_class = 'bg-secondary';
                        if ($proyecto['estado'] === 'activo') {
                            $badge_class = 'bg-success';
                        } elseif ($proyecto['estado'] === 'en_progreso') {
                            $badge_class = 'bg-warning text-dark';
                        } 
                    ?>
                    <span class="badge <?php echo $badge_class; ?> mb-3 text-capitalize fs-6">
                        Estado: <?php echo htmlspecialchars(str_replace('_', ' ', $proyecto['estado'])); ?>
                    </span>
                    
                    <hr>
                    
                    <h3>Descripción Completa</h3>
                    <p><?php echo nl2br(htmlspecialchars($proyecto['descripcion'])); ?></p>
                    
                    <hr>

                    <a href="<?php echo URL_BASE; ?>/donar.php?proyecto_id=<?php echo $proyecto['id']; ?>" 
                       class="btn btn-success btn-lg w-100 mt-3">
                        ¡Dona a este Proyecto Ahora!
                    </a>
                    
                    <a href="<?php echo URL_BASE; ?>/proyectos.php" class="btn btn-secondary w-100 mt-2">Volver a Proyectos</a>
                </div>
            </div>

        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>