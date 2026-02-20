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
    <title>Fundación Prosperidad Comunal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/CSS/Style.css">
</head>


<body>


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



    <!-- ========================= INICIO ========================= -->

    <section class="hero-inicio">

        <div class="container hero-container">

            <div class="row align-items-center">

                <!-- TEXTO IZQUIERDA -->
                <div class="col-lg-6 hero-texto">

                    <div class="badge-hero">
                        Transformando vidas desde 2015
                    </div>

                    <h1 class="hero-titulo">
                        Construyendo Futuro,
                        <span>Tejiendo Comunidad</span>
                    </h1>

                    <p class="hero-descripcion">
                        En la Fundación Prosperidad Comunal trabajamos cada día para crear
                        oportunidades reales de desarrollo, educación y bienestar para las
                        comunidades que más lo necesitan.
                    </p>

                    <!-- BOTONES -->
                    <div class="hero-botones">

                        <a href="donar.php" class="btn btn-hero-primary">
                            Únete a la Causa →
                        </a>

                        <a href="proyectos.php" class="btn btn-hero-secondary">
                            Conoce Nuestros Proyectos
                        </a>

                    </div>

                    <!-- ESTADISTICAS -->
                    <div class="hero-stats">

                        <div class="stat">
                            <div class="stat-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <h3>500+</h3>
                            <p>Personas Beneficiadas</p>
                        </div>

                        <div class="stat">
                            <div class="stat-icon">
                                <i class="bi bi-heart"></i>
                            </div>
                            <h3>3+</h3>
                            <p>Proyectos Activos</p>
                        </div>

                        <div class="stat">
                            <div class="stat-icon">
                                <i class="bi bi-award"></i>
                            </div>
                            <h3>5 Años</h3>
                            <p>De Experiencia</p>
                        </div>

                    </div>

                </div>


                <!-- IMAGEN DERECHA -->
                <div class="col-lg-6 hero-imagen">

                    <div class="imagen-principal">

                        <img src="assets/img/imagen-inicio.jpeg" alt="Comunidad">

                        <div class="impacto-card">
                            <h5>Impacto Real</h5>
                            <p>Miles de familias transformadas</p>
                        </div>

                        <div class="badge-transparente">
                            <h4>100%</h4>
                            <span>Transparente</span>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- ONDA -->
        <div class="hero-wave">
            <svg viewBox="0 0 1440 120">
                <path fill="#ffffff"
                    d="M0,64L48,69.3C96,75,192,85,288,80C384,75,480,53,576,48C672,43,768,53,864,58.7C960,64,1056,64,1152,58.7C1248,53,1344,43,1392,37.3L1440,32L1440,120L0,120Z">
                </path>
            </svg>
        </div>

    </section>


    <!-- ========================= NOSOTROS ========================= -->
    <section id="nosotros" class="section-gray">
        <div class="container text-center">
            <h2 class="section-title">Hacemos comunidad con compromiso y amor.</h2>

            <p class="section-desc">
                Trabajamos por el bienestar de niños, adolescentes, adultos y familias vulnerables,
                fortaleciendo la unión comunitaria y promoviendo el desarrollo integral.
            </p>

            <a href="nosotros" class="btn btn-primary-custom mt-4">Conoce más sobre nosotros</a>
        </div>
    </section>

    <!-- ========================= PROYECTOS ========================= -->
    <section id="proyectos" class="py-5">
        <div class="container">
            <h2 class="section-title mb-5">Proyectos en marcha</h2>

            <div class="row g-4">

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
        </div>
    </section>

    <!-- ========================= SECCIÓN DONAR ========================= -->
    <section id="donar" class="donar-section text-center">
        <div class="container">
            <h2 class="donar-title">Tu donación transforma vidas. Aporta desde donde estés.</h2>

            <a href="donar" class="btn btn-donar">
                Donar ahora
            </a>
        </div>
    </section>

    <!-- ========================= FOOTER ========================= -->
    <section id="contacto">
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
                        <a href="contactanos" class="btn btn-donar">
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
            <hr class="footer-divider">

            <div class="footer-bottom">
                <p>
                    © <span id="year"></span> Fundación Prosperidad Comunal.
                    Todos los derechos reservados.
                </p>
            </div>
        </footer>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById("year").textContent = new Date().getFullYear();
    </script>

</body>

</html>