<?php

namespace App\Controllers;


use App\Models\Admin;
use App\Helpers\AuthHelper;

class AuthController {
    private $adminModel;
    private $authHelper;

    public function __construct() {
        $this->adminModel = new Admin();
        $this->authHelper = new AuthHelper();
    }

    /**
     * Intenta autenticar un usuario administrador.
     */
    public function login(string $email, string $password): bool {
        // 1. Buscar administrador por email
        $admin = $this->adminModel->findByEmail($email);

        // Verificamos si existe y si está activo (estado = 1)
        if (!$admin || $admin['estado'] != 1) {
            return false; 
        }

        // 2. Verificar la contraseña hasheada
        if (password_verify($password, $admin['password'])) {
            // 3. Login exitoso: Iniciar sesión y guardar datos
            $this->authHelper->startSession($admin);
            return true;
        }

        return false; 
    }

    /**
     * Cierra la sesión del administrador.
     */
    public function logout() {
        $this->authHelper->destroySession();
        
        // REDIRECCIÓN DINÁMICA:
        // Como URL_BASE ya es "https://admin.fundacion.org", 
        // simplemente lo mandamos al index.php de la raíz del subdominio.
        header('Location: ' . URL_BASE . '/index.php'); 
        exit;
    }
}