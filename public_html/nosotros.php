<?php
// Página de Nosotros
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nosotros - Fundación Prosperidad Comunal</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="assets/CSS/Style.css">


    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
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



    <!-- ========================= HERO ========================= -->
    <section class="hero-nosotros">
        <div class="hero-overlay2">
            <h1>Sobre Nosotros</h1>
            <p>De una tradición navideña a un proyecto que transforma vidas. Conoce nuestra historia y compromiso con la comunidad.</p>
        </div>
    </section>

    <!-- ========================= HISTORIA ========================= -->
    <section class="container py-5">
        <h2 class="section-title mb-4">Nuestra Historia</h2>

        <div class="row align-items-center">

            <!-- TEXTO -->
            <div class="col-md-6">
                <p class="section-desc text-start">
                    La Fundación nació de un gesto sencillo pero lleno de amor por la comunidad: la realización del pesebre navideño que, año tras año, reunía a las familias del barrio y fortalecía los lazos de solidaridad. Lo que comenzó como una actividad tradicional pronto se convirtió en una oportunidad para ayudar más y llegar más lejos.<br>
                    Con el paso del tiempo, la Fundación dio su primer gran paso social al organizar entregas de alimentos no perecederos para las familias más necesitadas. Estas jornadas solidarias confirmaron que, con voluntad y unión, era posible transformar realidades.<br>

                </p>

                <p class="section-desc text-start mt-3">
                    Hoy, la Fundación ha evolucionado y ampliado su misión. Además de las actividades comunitarias, brinda orientación a niños, adolescentes y adultos en temas fundamentales para su desarrollo y bienestar: sexualidad, prevención de la drogadicción, cuidado del medio ambiente, convivencia y protección del entorno.


                </p>

                <p class="section-desc text-start mt-3">
                    Lo que empezó como una tradición se transformó en un proyecto de impacto social que crece cada día, impulsado por el compromiso de servir y construir un futuro más consciente, informado y solidario para toda la comunidad.

                </p>
            </div>

            <!-- IMAGEN -->
            <div class="col-md-6 text-center">
                <img src="assets/img/historia.jpeg" class="img-fluid rounded-4 shadow" alt="Historia Fundación">
            </div>

        </div>
    </section>

    <!-- ========================= MISIÓN Y VISIÓN ========================= -->
    <section class="container py-5">
        <div class="row g-4">

            <!-- MISIÓN -->
            <div class="col-md-6">
                <div class="card-mv card-mision p-4">
                    <div class="icon-mv">
                        <i class="bi bi-bullseye"></i>
                    </div>
                    <h2 class="mv-title">Misión</h2>
                    <p class="mv-text">
                        Trabajar de manera comprometida y transparente para diseñar, gestionar y ejecutar programas
                        sociales, educativos y comunitarios que fortalezcan las capacidades de las personas, fomenten
                        la inclusión y generen oportunidades reales de progreso, articulando esfuerzos con aliados
                        institucionales, comunitarios y privados para maximizar el impacto social.
                    </p>
                </div>
            </div>

            <!-- VISIÓN -->
            <div class="col-md-6">
                <div class="card-mv card-vision p-4">
                    <div class="icon-mv">
                        <i class="bi bi-globe"></i>
                    </div>
                    <h2 class="mv-title">Visión</h2>
                    <p class="mv-text">
                        Ser una organización referente en la promoción del desarrollo integral y la igualdad de
                        oportunidades, impactando positivamente la calidad de vida de las comunidades más vulnerables y
                        contribuyendo a la construcción de una sociedad más justa, solidaria y sostenible.
                    </p>
                </div>
            </div>

        </div>
    </section>

    <!-- ========================= VALORES ========================= -->
    <section class="py-5">
        <div class="container text-center">
            <h2 class="section-title mb-5">Nuestros Valores</h2>

            <div class="row g-4">
                <div class="col-md-3">
                    <i class="fa-solid fa-hand-holding-heart icon-value"></i>
                    <h5 class="fw-bold mt-3">Solidaridad</h5>
                </div>

                <div class="col-md-3">
                    <i class="fa-solid fa-people-group icon-value"></i>
                    <h5 class="fw-bold mt-3">Comunidad</h5>
                </div>

                <div class="col-md-3">
                    <i class="fa-solid fa-scale-balanced icon-value"></i>
                    <h5 class="fw-bold mt-3">Transparencia</h5>
                </div>

                <div class="col-md-3">
                    <i class="fa-solid fa-handshake-angle icon-value"></i>
                    <h5 class="fw-bold mt-3">Compromiso</h5>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================= IMPACTO ========================= -->
    <section class="section-gray py-5">
        <div class="container text-center">
            <h2 class="section-title">Nuestro Impacto</h2>

            <div class="row mt-4 g-4">
                <div class="col-md-4">
                    <div class="impact-box">
                        <h2 class="number">+100</h2>
                        <p>Familias beneficiadas</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="impact-box">
                        <h2 class="number">+20</h2>
                        <p>Voluntarios activos</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="impact-box">
                        <h2 class="number">+3</h2>
                        <p>Proyectos en marcha</p>
                    </div>
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
    <script>
        document.getElementById("year").textContent = new Date().getFullYear();
    </script>
</body>

</html>