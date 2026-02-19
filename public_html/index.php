<?php

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Mi Sitio Web</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                        <a class="nav-link active" href="index">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="nosotros">Nosotros</a>
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
        <div class="jumbotron bg-light p-5 rounded">
            <h1 class="display-4">Bienvenido a Nuestra Organización</h1>
            <p class="lead">Trabajamos para mejorar la vida de comunidades vulnerables a través de proyectos educativos, ambientales y sociales. Únete a nuestra causa y haz la diferencia.</p>
            <hr class="my-4">
            <p>Descubre más sobre nosotros y cómo puedes ayudar.</p>
            <a class="btn btn-primary btn-lg" href="nosotros" role="button">Saber Más</a>
        </div>

        <div class="row mt-4">
            <div class="col-md-4">
                <h3>Educación</h3>
                <p>Proporcionamos recursos educativos a niños en zonas rurales, incluyendo libros, computadoras y talleres.</p>
            </div>
            <div class="col-md-4">
                <h3>Medio Ambiente</h3>
                <p>Realizamos campañas de reforestación y concienciación sobre el cambio climático en comunidades locales.</p>
            </div>
            <div class="col-md-4">
                <h3>Ayuda Social</h3>
                <p>Ofrecemos apoyo alimentario y médico a familias en situación de pobreza extrema.</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>