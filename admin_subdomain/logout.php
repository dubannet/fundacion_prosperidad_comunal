<?php
require_once __DIR__ . '/../fundacion_core/config/bootstrap.php';



use App\Controllers\AuthController; 

$authController = new AuthController();
$authController->logout();
