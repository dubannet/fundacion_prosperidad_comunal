<?php

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactanos - Mi Sitio Web</title>
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
                        <a class="nav-link" href="index">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="nosotros">Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="proyectos">Proyectos</a> </li>
                    <li class="nav-item">
                        <a class="nav-link" href="donar">Donar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="contactanos">Contactanos</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">
        <h1>Contactanos</h1>
        <p class="lead">¿Tienes preguntas, quieres unirte como voluntario o necesitas más información? ¡Estamos aquí para ayudarte!</p>

        <div class="row">
            <div class="col-md-6">
                <h3>Información de Contacto</h3>
                <ul class="list-unstyled">
                    <li><strong>Email:</strong> info@nuestraorganizacion.org</li>
                    <li><strong>Teléfono:</strong> +57 1 234 5678</li>
                    <li><strong>Dirección:</strong> Calle Principal 456, Bogotá, Colombia</li>
                    <li><strong>Horario:</strong> Lunes a Viernes, 9:00 AM - 5:00 PM</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h3>Envíanos un Mensaje</h3>
                <?php
                if (isset($_GET['status'])) {
                    if ($_GET['status'] === 'success') {
                        echo '<div class="alert alert-success" role="alert">¡Mensaje enviado con éxito! Pronto nos pondremos en contacto contigo.</div>';
                    } elseif ($_GET['status'] === 'error') {
                        echo '<div class="alert alert-danger" role="alert">Ocurrió un error al enviar el mensaje. Por favor, inténtalo de nuevo.</div>';
                    }
                }
                ?>
                <form action="contacto_submit.php" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Tu nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="tuemail@ejemplo.com" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Mensaje</label>
                        <textarea class="form-control" id="message" name="message" rows="3" placeholder="Escribe tu mensaje aquí" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </form>
            </div>
        </div>

        <h3 class="mt-4">Síguenos en Redes Sociales</h3>
        <p>Facebook: @NuestraOrganizacion | Twitter: @OrgEjemplo | Instagram: @org_ejemplo</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>