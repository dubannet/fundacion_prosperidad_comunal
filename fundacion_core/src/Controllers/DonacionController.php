<?php

namespace App\Controllers;


use App\Models\Donacion;
use App\Helpers\AuthHelper;

class DonacionController {
    private $donacionModel;
    private $authHelper;

    public function __construct() {
        $this->donacionModel = new Donacion();
        $this->authHelper = new AuthHelper();
    }

    /**
     * Obtiene la lista de todas las donaciones.
     * Solo accesible para administradores logeados.
     */
    public function index() {
        // requireLogin() ya sabe redirigir a URL_BASE/index.php 
        // gracias a authhelper
        $this->authHelper->requireLogin();
        
        return $this->donacionModel->findAll('created_at DESC'); 
    }
}