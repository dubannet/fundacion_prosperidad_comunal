<?php

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - Fundación Prosperidad Comunal</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="assets/CSS/Style.css">


    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body>


    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
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




    <!-- ================= HERO CONTACTO ================= -->
    <section class="hero-contacto">
        <div class="hero-overlay2">
            <i class="bi bi-chat-left-dots" style="font-size:48px;"></i>
            <h1>Contactanos</h1>
            <p>
                Estamos aquí para escucharte.ponte en contacto con nosotros y unete a nuetra misión.
            </p>
        </div>
    </section>

    <!-- ================= INFO CONTACTO ================= -->
    <section class="contact-cards-wrapper">
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-md-4 mb-4">
                    <div class="contact-card">
                        <i class="bi bi-telephone"></i>
                        <h5>Teléfono</h5>
                        <p>+57 302 286 1822</p>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="contact-card">
                        <i class="bi bi-envelope"></i>
                        <h5>Correo Electrónico</h5>
                        <p>fundacionprosperidadcomunal@gmail.com</p>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="contact-card">
                        <i class="bi bi-geo-alt"></i>
                        <h5>Ubicación</h5>
                        <a
                            href="https://www.google.com/maps?q=Calle+11f+%231c-66+Malambo+Atlantico"
                            target="_blank">
                            Calle 11f #1c-66<br>Malambo, Atlántico
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= FORMULARIO ================= -->
    <section class="contact-form container">
        <h2>Envíanos un Mensaje</h2>
        <?php
        if (isset($_GET['status'])) {
            if ($_GET['status'] === 'success') {
                echo '<div class="alert alert-success" role="alert">¡Mensaje enviado con éxito! Pronto nos pondremos en contacto contigo.</div>';
            } elseif ($_GET['status'] === 'error') {
                echo '<div class="alert alert-danger" role="alert">Ocurrió un error al enviar el mensaje. Por favor, inténtalo de nuevo.</div>';
            }
        }
        ?>
        <p>Completa el formulario y nos pondremos en contacto contigo.</p>

        <form id="formContacto" action="contacto_submit.php" method="POST" novalidate>

            <div class="mb-3">
                <label>Nombre Completo *</label>
                <input type="text" class="form-control" id="nombre" required>
                <small class="error-text"></small>
            </div>

            <div class="mb-3">
                <label>Correo Electrónico *</label>
                <input type="email" class="form-control" id="email" required>
                <small class="error-text"></small>
            </div>

            <div class="mb-3">
                <label>Teléfono</label>
                <input type="text" class="form-control" id="telefono">
                <small class="error-text"></small>
            </div>

            <div class="mb-3">
                <label>Asunto *</label>
                <select class="form-control" id="asunto" required>
                    <option value="">Selecciona una opción</option>
                    <option>Donaciones</option>
                    <option>Voluntariado</option>
                    <option>Información</option>
                </select>
                <small class="error-text"></small>
            </div>

            <div class="mb-3">
                <label>Mensaje *</label>
                <textarea class="form-control" id="mensaje" rows="4" required></textarea>
                <small class="error-text"></small>
            </div>

            <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
        </form>
    </section>

    <!-- ================= REDES ================= -->
    <section class="contact-redes">
        <h3>Síguenos en Redes</h3>
        <div class="social-icons">
            <a href="https://www.facebook.com/profile.php?id=61583813890771" target="_blank"><i class="bi bi-facebook"></i></a>
            <a href="#" target="_blank"><i class="bi bi-instagram"></i></a>
            <a href="https://api.whatsapp.com/send?phone=573022861822" target="_blank"><i class="bi bi-whatsapp"></i></a>
        </div>
    </section>

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
    <script src="/assets/js/Validaciones.js"></script>


</body>

</html>