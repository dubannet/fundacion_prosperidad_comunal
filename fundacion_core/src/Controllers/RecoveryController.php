<?php

namespace App\Controllers;


use App\Models\Admin;
use App\Helpers\MailHelper;

class RecoveryController
{
    private $adminModel;
    private $mailHelper;

    public function __construct()
    {
        $this->adminModel = new Admin();
        $this->mailHelper = new MailHelper(); 
    }

    /**
     * Genera el token y solicita al MailHelper el envío.
     */
    public function sendResetEmail(string $email)
    {
        $admin = $this->adminModel->findByEmail($email);

        // Seguridad: no dar pistas si el correo existe
        if (!$admin) {
            return true; 
        }

        $token = bin2hex(random_bytes(32));
        
        // Usamos la zona horaria definida en bootstrap.php automáticamente
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Guardamos el token en la BD 
        $this->adminModel->update($admin['id'], [
            'reset_token' => $token,
            'reset_expires_at' => $expires
        ]);

        // El MailHelper ya usa URL_BASE para construir el link
        return $this->mailHelper->sendPasswordReset($admin['email'], $admin['nombre'], $token);
    }

    /**
     * Procesa el cambio de contraseña real.
     */
    public function resetPassword(string $token, string $password)
    {
        // El modelo debe verificar que el token exista y no haya expirado
        $admin = $this->adminModel->findByResetToken($token);

        if (!$admin) {
            return "El enlace es inválido o ha expirado.";
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $data = [
            'password' => $hashedPassword,
            'reset_token' => null,
            'reset_expires_at' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        return $this->adminModel->update($admin['id'], $data);
    }
}