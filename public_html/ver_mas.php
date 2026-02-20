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

    <style>
        /* ── Responsive fixes for ver_mas.php ── */

        /* Prevent any element from overflowing its container */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        /* Root overflow guard */
        html,
        body {
            overflow-x: hidden;
            max-width: 100%;
        }

        /* Main container spacing on mobile */
        main.container {
            padding-left: 16px;
            padding-right: 16px;
        }

        /* ── Page title ── */
        .page-title {
            font-size: clamp(24px, 5vw, 40px);
            /* scales down on small screens */
            font-weight: 700;
            color: #17324a;
            margin: 12px 0 18px;
            word-break: break-word;
            /* prevent long words from overflowing */
            overflow-wrap: break-word;
        }

        /* ── Estado badge ── */
        .estado-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 700;
            margin-top: 6px;
            margin-bottom: 18px;
            font-size: clamp(13px, 2.5vw, 15px);
        }

        /* ── Main image container ── */
        .img-principal-container {
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .08);
            width: 100%;
        }

        .img-principal {
            width: 100%;
            max-height: 420px;
            height: auto;
            /* auto on mobile so it never crops weirdly */
            object-fit: cover;
            display: block;
        }

        /* ── Gallery ── */
        .galeria {
            display: flex;
            flex-wrap: wrap;
            /* wrap on small screens instead of overflow */
            gap: 10px;
            margin-top: 14px;
        }

        .galeria .thumb {
            width: calc(33.333% - 10px);
            /* 3 per row by default */
            min-width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
            cursor: pointer;
            border: 3px solid transparent;
            transition: transform 0.15s, border-color 0.15s;
            flex-shrink: 0;
        }

        .galeria .thumb.active {
            border-color: #1e64c9;
            transform: translateY(-3px);
        }

        .galeria .thumb:hover {
            opacity: 1;
            transform: scale(1.03);
        }

        /* On larger screens restore the nicer fixed-height look */
        @media (min-width: 576px) {
            .galeria .thumb {
                width: 160px;
                height: 100px;
            }

            .img-principal {
                height: 420px;
            }
        }

        /* ── "Sobre el proyecto" section ── */
        .sobre-proyecto {
            width: 100%;
        }

        .sobre-proyecto h3 {
            font-size: clamp(20px, 4vw, 28px);
            margin-bottom: 14px;
            font-weight: 700;
            color: #17324a;
        }

        /* THE KEY FIX: prevent description text from overflowing */
        .sobre-proyecto p,
        #sobreContenido p {
            font-size: clamp(14px, 2.5vw, 16.5px);
            line-height: 1.7;
            color: #333;
            margin-bottom: 16px;

            /* Critical overflow fixes */
            word-break: break-word;
            overflow-wrap: break-word;
            white-space: pre-wrap;
            /* respect newlines from nl2br without clipping */
            max-width: 100%;
        }

        /* Guard against deeply nested inline elements (links, spans, etc.) */
        #sobreContenido * {
            max-width: 100%;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        /* ── Donate box ── */
        .donar-box {
            border-radius: 18px;
            padding: 28px 20px;
            color: white;
            background: linear-gradient(120deg, #1e88e5 0%, #47a5e8 40%, #68b9e9 100%);
            box-shadow: 0 12px 30px rgba(34, 110, 200, .12);
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
            align-items: center;
        }

        .donar-info {
            flex: 1 1 220px;
            min-width: 0;
            /* lets flex item shrink below its content size */
        }

        .donar-info h4 {
            font-size: clamp(18px, 3.5vw, 28px);
            font-weight: 800;
            margin-bottom: 10px;
            color: white;
            word-break: break-word;
        }

        .donar-info p {
            font-size: clamp(13px, 2.5vw, 16px);
            opacity: .95;
            margin-bottom: 18px;
            color: white;
        }

        .donar-cta {
            display: inline-block;
            background: #ffd54f;
            color: #17324a;
            padding: 11px 22px;
            border-radius: 12px;
            font-weight: 700;
            border: none;
            font-size: clamp(13px, 2.5vw, 16px);
            transition: filter 0.2s;
            text-decoration: none;
            white-space: nowrap;
        }

        .donar-cta i {
            font-size: 16px;
            margin-right: 6px;
        }

        .donar-cta:hover {
            filter: brightness(0.95);
        }

        .donar-img {
            flex: 0 1 280px;
            text-align: right;
        }

        .donar-img img {
            max-width: 100%;
            border-radius: 12px;
        }

        /* Stack on very small screens */
        @media (max-width: 480px) {
            .donar-box {
                flex-direction: column-reverse;
                text-align: center;
                padding: 22px 16px;
            }

            .donar-img {
                text-align: center;
                flex: unset;
                width: 100%;
            }

            .donar-img img {
                max-height: 160px;
                object-fit: cover;
            }
        }

        /* ── Back button ── */
        .text-center.my-5 {
            padding: 0 16px;
        }

        /* ── Spacer below navbar ── */
        .navbar-spacer {
            height: 88px;
        }

        @media (max-width: 768px) {
            .navbar-spacer {
                height: 70px;
            }
        }
    </style>
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

    <div class="navbar-spacer"></div>

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
                    src="<?php echo URL_BASE; ?>/uploads/<?php echo htmlspecialchars($multimedia[0] ?? ''); ?>"
                    alt="<?php echo htmlspecialchars($proyecto['nombre']); ?>">
            </div>

            <!-- Galería -->
            <?php if (count($multimedia) > 1): ?>
                <div class="galeria">
                    <?php foreach ($multimedia as $index => $img): ?>
                        <img src="<?php echo URL_BASE; ?>/uploads/<?php echo htmlspecialchars($img); ?>"
                            class="thumb <?php echo $index === 0 ? 'active' : ''; ?>"
                            alt="Imagen <?php echo $index + 1; ?>"
                            onclick="cambiarImagen(this)">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Sobre el proyecto -->
            <section class="sobre-proyecto mt-5">
                <h3>Sobre el Proyecto</h3>
                <div id="sobreContenido">
                    <p><?php echo nl2br(htmlspecialchars($proyecto['descripcion'])); ?></p>
                </div>
            </section>

            <!-- Caja donar -->
            <section class="mt-5 mb-5">
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
                        <img src="<?php echo URL_BASE; ?>/assets/img/donar-proyecto.jpg"
                            alt="Donación">
                    </div>
                </div>
            </section>

        <?php endif; ?>
    </main>

    <!-- BOTÓN VOLVER -->
    <div class="text-center my-5">
        <a href="index" class="btn btn-primary-custom">
            <i class="bi bi-arrow-left"></i> Volver al inicio
        </a>
    </div>

    <!-- FOOTER -->
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
                    <p><i class="bi bi-envelope"></i> Fundaciónprosperidadcomunal@gmail.com</p>
                    <a href="contactanos" class="btn btn-donar">Contactanos ahora</a>
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
                        <a href="https://www.facebook.com/profile.php?id=61583813890771&locale=es_LA">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="https://api.whatsapp.com/send?phone=%2B573022861822">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <hr class="footer-divider">

        <div class="footer-bottom">
            <p>© <span id="year"></span> Fundación Prosperidad Comunal. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById("year").textContent = new Date().getFullYear();

        function cambiarImagen(elemento) {
            document.getElementById("imgPrincipal").src = elemento.src;
            document.querySelectorAll(".thumb").forEach(img => img.classList.remove("active"));
            elemento.classList.add("active");
        }
    </script>

</body>

</html>