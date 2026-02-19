<?php

namespace App\Controllers;


// El bootstrap cargó el autoload de Composer

use App\Models\Admin;
use App\Helpers\AuthHelper;

class AdminController {
    private $adminModel;
    private $authHelper;

    public function __construct() {
        $this->adminModel = new Admin();
        $this->authHelper = new AuthHelper();
    }

    /**
     * Lista de todos los administradores.
     * Solo accesible por el Superadmin.
     */
    public function index() {
        // ✅ requireRole ya sabe redirigir a URL_BASE si falla
        $this->authHelper->requireRole('superadmin');
        return $this->adminModel->findAll('id DESC'); 
    }

    /**
     * Crea un nuevo administrador.
     */
    public function store(array $data) {
        $this->authHelper->requireRole('superadmin');
        
        $nombre = trim($data['nombre'] ?? '');
        $email = filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $password = $data['password'] ?? '';
        $rol = $data['rol'] ?? 'admin';

        if (!$email || empty($nombre) || empty($password) || !in_array($rol, ['admin', 'superadmin'])) {
            return "Datos incompletos o rol inválido.";
        }
        
        if (strlen($password) < 8) {
             return "La contraseña debe tener al menos 8 caracteres.";
        }
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $adminData = [
            'nombre' => $nombre,
            'email' => $email,
            'password' => $hashedPassword,
            'rol' => $rol,
            'estado' => 1,
        ];
        
        $id = $this->adminModel->create($adminData);

        return $id ? true : "Error al guardar. El email podría estar ya registrado.";
    }

    /**
     * Busca un administrador por ID.
     */
    public function find(int $id) {
        $this->authHelper->requireRole('superadmin');
        return $this->adminModel->find($id);
    }

    /**
     * Actualiza datos del administrador.
     */
    public function update(int $id, array $data) {
        $this->authHelper->requireRole('superadmin');

        $nombre = trim($data['nombre'] ?? '');
        $email = filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $rol = $data['rol'] ?? 'admin';
        $estado = isset($data['estado']) ? (int)$data['estado'] : 1;
        $password = $data['password'] ?? '';

        if (!$email || empty($nombre) || !in_array($rol, ['admin', 'superadmin'])) {
            return "Datos inválidos.";
        }

        $adminData = [
            'nombre' => $nombre,
            'email' => $email,
            'rol' => $rol,
            'estado' => $estado,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Solo actualizar password si se proporcionó una nueva
        if (!empty($password)) {
            if (strlen($password) < 8) {
                return "La nueva contraseña debe tener al menos 8 caracteres.";
            }
            $adminData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $success = $this->adminModel->update($id, $adminData);
        return $success ? true : "Error al actualizar. Posiblemente el email ya está en uso.";
    }
}