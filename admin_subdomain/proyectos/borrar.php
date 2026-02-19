<?php
require_once __DIR__ . '/../../fundacion_core/config/bootstrap.php';

use App\Helpers\AuthHelper;
use App\Controllers\ProyectoController;

$authHelper = new AuthHelper();
$authHelper->requireLogin();

$controller = new ProyectoController();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: index.php?status=error&message=' . urlencode('ID de proyecto no válido para eliminar.'));
    exit;
}

// Ejecutar el borrado suave
$result = $controller->destroy($id);

if ($result === true) {
    header('Location: index.php?status=success&message=' . urlencode('Proyecto eliminado (marcado como finalizado) con éxito.'));
    exit;
} else {
    header('Location: index.php?status=error&message=' . urlencode('Error al eliminar el proyecto: ' . $result));
    exit;
}