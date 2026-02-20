<?php
require_once __DIR__ . '/../fundacion_core/config/bootstrap.php';

use App\Models\Proyecto;

$proyectos = [];
$error_message = null;

try {
    $proyectoModel = new Proyecto();
    $proyectos = $proyectoModel->findAllActive();
} catch (\Exception $e) {
    error_log("Error al cargar proyectos: " . $e->getMessage());
    $error_message = 'No pudimos cargar los proyectos en este momento.';
}
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Proyectos - Fundación Prosperidad Comunal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URL_BASE; ?>/assets/CSS/Style.css">
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index">
                <img src="assets/img/log1.png" class="logo" alt="Logo Fundación">
                <div class="brand-text ms-2">
                    <span class="brand-title">Fundación Prosperidad</span>
                    <span class="brand-subtitle">Comunal</span>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link menu-link" href="index">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link menu-link" href="nosotros">Nosotros</a></li>
                    <li class="nav-item"><a class="nav-link menu-link" href="proyectos">Proyectos</a></li>
                    <li class="nav-item"><a class="nav-link menu-link" href="donar">Donar</a></li>
                    <li class="nav-item"><a class="nav-link menu-link" href="contactanos">Contacto</a></li>

                </ul>
            </div>
        </div>
    </nav>

    <div style="height:88px;"></div>

    <main class="container my-5">

        <h1 class="page-title text-center mb-5">Nuestros Proyectos</h1>

        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="row g-4">

            <?php if (empty($proyectos)): ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        Actualmente no hay proyectos activos.
                    </div>
                </div>
            <?php else: ?>

                <?php foreach ($proyectos as $proyecto): ?>

                    <?php
                    $estadoClass = 'estado-pendiente';
                    if ($proyecto['estado'] === 'en_progreso') {
                        $estadoClass = 'estado-enprogreso';
                    } elseif ($proyecto['estado'] === 'finalizado') {
                        $estadoClass = 'estado-finalizado';
                    }
                    ?>

                    <div class="col-md-4">
                        <div class="card project-card h-100">

                            <?php if (!empty($proyecto['imagen_principal'])): ?>
                                <img src="<?php echo URL_BASE; ?>/uploads/<?php echo htmlspecialchars($proyecto['imagen_principal']); ?>" alt="">
                            <?php endif; ?>

                            <div class="card-body d-flex flex-column">

                                <h5 class="fw-bold">
                                    <?php echo htmlspecialchars($proyecto['nombre']); ?>
                                </h5>

                                <span class="estado-badge <?php echo $estadoClass; ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $proyecto['estado'])); ?>
                                </span>

                                <p class="mt-2 flex-grow-1">
                                    <?php echo htmlspecialchars(substr($proyecto['descripcion'], 0, 120)); ?>...
                                </p>

                                <a href="<?php echo URL_BASE; ?>/ver_mas.php?id=<?php echo $proyecto['id']; ?>"
                                    class="btn btn-primary-custom mt-3">
                                    Ver Proyecto
                                </a>

                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>
    </main>
    <!-- ========================= BOTÓN VOLVER ========================= -->
    <div class="text-center my-5">
        <a href="index" class="btn btn-primary-custom">
            <i class="fa-solid fa-arrow-left"></i> Volver al inicio
        </a>
    </div>

    <!-- ========================= FOOTER ========================= -->
    <footer class="footer">
        <div class="container">
            <div class="row">

                <!-- CONTACTO -->
                <div class="col-md-4 mb-4">
                    <h5 class="footer-title">Contacto</h5>

                    <p>
                        <i class="bi bi-geo-alt"></i>
                        <a class="footer-link"
                            href="https://www.google.com/maps/search/?api=1&query=Calle+11f+%231c+66+Malambo+Atlantico"
                            target="_blank" rel="noopener noreferrer">
                            Calle 11f # 1c 66 Malambo, Atlántico
                        </a>
                    </p>
                    <p><i class="bi bi-telephone"></i> +57 302 2861822</p>
                    <p><i class="bi bi-envelope"></i> . Fundaciónprosperidadcomunal@gmail.com</p>
                    <a href="contactanos.php" class="btn btn-donar">
                        Contactanos ahora
                    </a>
                </div>

                <!-- SOBRE NOSOTROS -->
                <div class="col-md-4 mb-4">
                    <h5 class="footer-title">Sobre Nosotros</h5>
                    <p>
                        Fundación Prosperidad Comunal trabaja por el bienestar y desarrollo
                        integral de comunidades vulnerables.
                    </p>
                </div>

                <!-- REDES SOCIALES -->
                <div class="col-md-4 mb-4">
                    <h5 class="footer-title">Redes Sociales</h5>

                    <div class="social-icons">
                        <a href="https://www.facebook.com/profile.php?id=61583813890771&locale=es_LA"><i
                                class="bi bi-facebook"></i></a>

                        <a
                            href="https://api.whatsapp.com/send?phone=%2B573022861822&fbclid=IwY2xjawOmBnxleHRuA2FlbQIxMABicmlkETF3Y2pSSzVXV1VDR2tlQWpoc3J0YwZhcHBfaWQQMjIyMDM5MTc4ODIwMDg5MgABHvPYQFECXks4ijNb60v3PWQVV6Xek4oT7l8280_Kl2BanDWTh-lqhaPPSZsc_aem_i-AZoMlbM6lRky-b3H-fyA"><i
                                class="bi bi-whatsapp"></i></a>
                    </div>
                </div>

            </div>
        </div>
        </div>
        <hr class="footer-divider">

        <div class="footer-bottom">
            <p>
                © <span id="year"></span> Fundación Prosperidad Comunal.
                Todos los derechos reservados.
            </p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById("year").textContent = new Date().getFullYear();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>