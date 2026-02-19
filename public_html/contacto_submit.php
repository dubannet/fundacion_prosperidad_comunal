<?php
require_once __DIR__ . '/../fundacion_core/config/bootstrap.php';

use App\Controllers\ContactoController;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $controller = new ContactoController();
    $resultado = $controller->handleContactForm($_POST);
    
    if ($resultado === true) {
        header("Location: contactanos.php?status=success");
    } else {
        header("Location: contactanos.php?status=error");
    }
    exit;
}