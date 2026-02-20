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
    <title>Donar | Fundación Prosperidad Comunal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script type="text/javascript" src="https://checkout.wompi.co/widget.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/CSS/Style.css">
</head>

<body>

    <!-- ========================= HEADER ========================= -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="inicio.html">
                <img src="assets/img/log1.png" alt="Logo" class="logo">
                <span class="brand-text">
                    <span class="brand-title">Fundación Prosperidad</span>
                    <span class="brand-subtitle">Comunal</span>
                </span>
            </a>

            <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="menu">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link menu-link" href="index">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link menu-link" href="nosotros">Nosotros</a></li>
                    <li class="nav-item"><a class="nav-link menu-link" href="proyectos">Proyectos</a></li>
                    <li class="nav-item"><a class="nav-link menu-link" href="donar">Donar</a></li>
                    <li class="nav-item"><a class="nav-link menu-link" href="contactanos">Contacto</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ========================= HERO DONAR ========================= -->
    <section class="hero-donar">
        <div class="hero-overlay2">
            <i class="fa-regular fa-heart mb-3" style="font-size:48px;"></i>
            <h1>Haz la Diferencia Hoy</h1>
            <p>
                Tu generosidad transforma vidas y construye un futuro mejor para nuestra comunidad.
                Cada donación crea un impacto real y duradero.
            </p>
        </div>
    </section>

    <!-- ========================= FORMULARIO DONACIÓN ========================= -->
    <section class="container my-5">

        <h2 class="page-title" id="tituloDonacion">Realizar Donación</h2>


        <!-- =========================
       MONTO DONACIÓN
  ========================= -->
        <form id="formulario-monto" onsubmit="event.preventDefault(); iniciarDonacionAutomatica();">
            <h4>Selecciona el monto</h4>

            <div class="row g-3 mb-3">
                <div class="col-4">
                    <button type="button" class="btn btn-outline-primary w-100 monto-btn" data-valor="10000">$10,000</button>
                </div>
                <div class="col-4">
                    <button type="button" class="btn btn-outline-primary w-100 monto-btn" data-valor="25000">$25,000</button>
                </div>
                <div class="col-4">
                    <button type="button" class="btn btn-outline-primary w-100 monto-btn" data-valor="50000">$50,000</button>
                </div>
                <div class="col-4">
                    <button type="button" class="btn btn-outline-primary w-100 monto-btn" data-valor="100000">$100,000</button>
                </div>
                <div class="col-4">
                    <button type="button" class="btn btn-outline-primary w-100 monto-btn" data-valor="250000">$250,000</button>
                </div>
                <div class="col-4">
                    <button type="button" class="btn btn-outline-primary w-100 monto-btn" data-valor="500000">$500,000</button>
                </div>
            </div>

            <p>O ingresa otro monto</p>
            <input type="number" id="montoDonacion" class="form-control mb-4" placeholder="$ 3.000">



            <!-- =========================BOTÓN FINAL========================= -->
            <button type="submit" class="donar-cta w-100">
                <i class="bi bi-heart-fill"></i> Donar ahora
            </button>
            <div id="contenedor-widget" class="mt-3 text-center" style="display: none;">
            </div>

        </form>

    </section>

    <!-- ========================= ¿POR QUÉ DONAR? ========================= -->
    <section class="section-gray">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">

                    <h3 class="section-title text-start mb-4">¿Por qué donar?</h3>

                    <ul class="list-unstyled why-donate-list">
                        <li>
                            <span class="check-icon">✓</span>
                            100% de tu donación va directamente a los proyectos
                        </li>
                        <li>
                            <span class="check-icon">✓</span>
                            Aportas tu granito de arena para construir una mejor comunidad
                        </li>
                        <li>
                            <span class="check-icon">✓</span>
                            Pagos seguros y protegidos
                        </li>
                        <li>
                            <span class="check-icon">✓</span>
                            Tu donación puede ser el salvavidas de una familia necesitada
                        </li>
                    </ul>

                </div>
            </div>
        </div>
    </section>

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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo URL_BASE; ?>/assets/js/Validaciones.js?v=<?php echo time(); ?>"></script>
    <script>
        document.getElementById("year").textContent = new Date().getFullYear();
    </script>
    <script>
        const URL_BASE = '<?php echo URL_BASE; ?>';
        const PROYECTO_ID_URL = <?php echo $proyectoId ?: 'null'; ?>;
    </script>

    <script src="<?php echo URL_BASE; ?>/assets/js/app.js?v=<?php echo time(); ?>"></script>

</body>

</html>