<?php
// Página de Nosotros
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nosotros - Mi Sitio Web</title>
    <!-- Bootstrap CSS -->
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
        <h1>Sobre Nosotros</h1>
        <p class="lead">Somos una organización sin fines de lucro fundada en 2010, dedicada a transformar vidas en comunidades desfavorecidas. Nuestro equipo está compuesto por voluntarios apasionados y profesionales comprometidos.</p>

        <div class="row mt-4">
            <div class="col-md-6">
                <h3>Nuestra Misión</h3>
                <p>Promover el desarrollo sostenible y equitativo, enfocándonos en educación, salud y protección del medio ambiente.</p>
            </div>
            <div class="col-md-6">
                <h3>Nuestra Visión</h3>
                <p>Un mundo donde todas las personas tengan acceso a oportunidades justas y vivan en armonía con la naturaleza.</p>
            </div>
        </div>

        <h3 class="mt-4">Nuestro Equipo</h3>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ana García</h5>
                        <p class="card-text">Directora Ejecutiva. Experta en gestión de proyectos sociales con más de 15 años de experiencia.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Carlos López</h5>
                        <p class="card-text">Coordinador de Voluntarios. Encargado de reclutar y organizar equipos en el terreno.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">María Rodríguez</h5>
                        <p class="card-text">Especialista en Educación. Diseña programas para mejorar el acceso a la enseñanza.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>