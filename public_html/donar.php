<?php

// 1. INICIALIZACIÓN DE PHP (Rutas, Autoload, Conexión, etc.)
require_once __DIR__ . '/../fundacion_core/config/bootstrap.php';

//  capturar el ID del proyecto si se presiona el boton de donar de un proyecto específico
$proyectoId = filter_input(INPUT_GET, 'proyecto_id', FILTER_VALIDATE_INT);
$proyectoInfo = null;

// Lógica para cargar el nombre del proyecto si existe el ID:
use App\Models\Proyecto;

if ($proyectoId) {
    $proyectoModel = new Proyecto();
    $proyectoInfo = $proyectoModel->find($proyectoId);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donar - Mi Sitio Web</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <script type="text/javascript" src="https://checkout.wompi.co/widget.js"></script>


</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Mi Sitio</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="nosotros">Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="proyectos">Proyectos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="donar">Donar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contactanos">Contactanos</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">

        <?php if ($proyectoInfo): ?>
            <h1 class="text-success">Donación para: <?php echo htmlspecialchars($proyectoInfo['nombre']); ?></h1>
            <p class="lead">Tu donación se destinará directamente a esta iniciativa específica. ¡Gracias por tu apoyo!</p>
        <?php else: ?>
            <h1>Haz una Donación a la Fundación</h1>
            <p class="lead">Tu contribución nos ayuda a continuar nuestros proyectos en general. Ingresa el monto y dona de forma segura.</p>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ingresa tu Donación</h5>

                        <form id="formulario-monto" onsubmit="event.preventDefault(); iniciarDonacionAutomatica();">
                            <div class="mb-3">
                                <label for="montoDonacion" class="form-label">Monto a Donar (en COP)</label>
                                <input type="number" class="form-control" id="montoDonacion" placeholder="Ej: 50000" min="1500" required>
                                <div class="form-text">Mínimo: $1.500 COP</div>
                            </div>
                            <button type="submit" class="btn btn-success btn-lg w-100" id="botonDonar">
                                Donar Ahora
                            </button>
                        </form>

                        <div id="contenedor-widget" class="mt-3 text-center" style="display: none;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            
    
    <script>
        const URL_BASE = '<?php echo URL_BASE; ?>';
        const PROYECTO_ID_URL = <?php echo $proyectoId ?: 'null'; ?>;
        
    </script>

    <script src="<?php echo URL_BASE; ?>/assets/js/app.js?v=<?php echo time(); ?>"></script>
</body>

</html>