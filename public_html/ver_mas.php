<?php
require_once __DIR__ . '/../fundacion_core/config/bootstrap.php';

use App\Models\Proyecto;

$proyecto = null;
$multimedia = [];
$error_message = null;

$proyecto_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$proyecto_id) {
    $error_message = "Proyecto inválido.";
} else {
    try {
        $proyectoModel = new Proyecto();
        $raw_data = $proyectoModel->findWithMedia($proyecto_id);

        if (!empty($raw_data)) {
            $proyecto = $raw_data[0];

            if (!empty($proyecto['imagen_principal'])) {
                $multimedia[] = $proyecto['imagen_principal'];
            }

            foreach ($raw_data as $row) {
                if (!empty($row['media_ruta'])) {
                    $multimedia[] = $row['media_ruta'];
                }
            }
        } else {
            $error_message = "Proyecto no encontrado.";
        }
    } catch (\Exception $e) {
        $error_message = "Error al cargar proyecto.";
    }
}
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo htmlspecialchars($proyecto['nombre'] ?? 'Proyecto'); ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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

    <main class="container my-4">

        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php else: ?>

            <h1 class="page-title">
                <?php echo htmlspecialchars($proyecto['nombre']); ?>
            </h1>

            <?php
            $estadoClass = 'estado-pendiente';
            if ($proyecto['estado'] === 'en_progreso') {
                $estadoClass = 'estado-enprogreso';
            } elseif ($proyecto['estado'] === 'finalizado') {
                $estadoClass = 'estado-finalizado';
            }
            ?>

            <span class="estado-badge <?php echo $estadoClass; ?>">
                <?php echo ucfirst(str_replace('_', ' ', $proyecto['estado'])); ?>
            </span>

            <!-- Imagen principal -->
            <div class="img-principal-container mt-3">
                <img id="imgPrincipal"
                    class="img-principal"
                    src="<?php echo URL_BASE; ?>/uploads/<?php echo htmlspecialchars($multimedia[0]); ?>">
            </div>

            <!-- Galería -->
            <div class="galeria">
                <?php foreach ($multimedia as $index => $img): ?>
                    <img src="<?php echo URL_BASE; ?>/uploads/<?php echo htmlspecialchars($img); ?>"
                        class="thumb <?php echo $index === 0 ? 'active' : ''; ?>"
                        onclick="cambiarImagen(this)">
                <?php endforeach; ?>
            </div>

            <!-- Sobre el proyecto -->
            <section class="sobre-proyecto mt-5">
                <h3>Sobre el Proyecto</h3>
                <div id="sobreContenido">
                    <p><?php echo nl2br(htmlspecialchars($proyecto['descripcion'])); ?></p>
                </div>
            </section>

            <!-- Caja donar -->
            <section class="mt-5">
                <div class="donar-box">
                    <div class="donar-info">
                        <h4>Ayuda a este proyecto</h4>
                        <p>Tu donación hace la diferencia.</p>
                        <a href="<?php echo URL_BASE; ?>/donar.php?proyecto_id=<?php echo $proyecto['id']; ?>"
                            class="donar-cta">
                            <i class="bi bi-heart"></i> Donar a este proyecto
                        </a>
                    </div>

                    <div class="donar-img">
                        <img src="<?php echo URL_BASE; ?>/assets/img/donar-proyecto.jpg">
                    </div>
                </div>
            </section>

        <?php endif; ?>
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
    <script>
        function cambiarImagen(elemento) {
            document.getElementById("imgPrincipal").src = elemento.src;

            document.querySelectorAll(".thumb").forEach(img => img.classList.remove("active"));
            elemento.classList.add("active");
        }
    </script>

</body>

</html>