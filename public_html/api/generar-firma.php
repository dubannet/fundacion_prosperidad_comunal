<?php
// 1. Cargar el núcleo
require_once __DIR__ . '/../../fundacion_core/config/bootstrap.php';

// 2. Ejecutar el controlador
$controller = new \App\Controllers\WompiController();
$controller->generarFirma();