<?php


namespace App\Helpers;

class AuthHelper {
    private $session_key = 'admin_logged_in';
    private $timeout_duration = 900; 

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($this->isLoggedIn()) {
            $this->checkInactivity();
        }
    }

    private function checkInactivity() {
        if (isset($_SESSION['last_activity'])) {
            $elapsed_time = time() - $_SESSION['last_activity'];

            if ($elapsed_time > $this->timeout_duration) {
                $this->destroySession();
                // USAMOS URL_BASE AQUÍ:
                header("Location: " . URL_BASE . "/index.php?error=timeout");
                exit;
            }
        }
        $_SESSION['last_activity'] = time();
    }

    public function startSession(array $admin) {
        $_SESSION[$this->session_key] = true;
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['nombre'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['admin_rol'] = $admin['rol'];
        $_SESSION['last_activity'] = time(); // Registrar tiempo inicial
    }

    

    

    /**
     * Verifica si el usuario actual es un administrador logeado.
     * @return bool
     */
    public function isLoggedIn(): bool {
        return isset($_SESSION[$this->session_key]) && $_SESSION[$this->session_key] === true;
    }

    /**
     * Verifica si el usuario actual tiene un rol específico.
     * @param string $rol El rol a verificar ('admin', 'superadmin').
     * @return bool
     */
    public function isRole(string $rol): bool {
        return $this->isLoggedIn() && $_SESSION['admin_rol'] === $rol;
    }

    /**
     * Cierra y destruye la sesión.
     */
    public function destroySession() {
        session_unset();
        session_destroy();
    }

    /**
     * Redirige al login si no está autenticado, o redirige a la página principal del admin.
     * @param string $redirectUrl URL a donde redirigir si no está logeado.
     */
    public function requireLogin(string $redirectUrl = null) {
        if (!$this->isLoggedIn()) {
            $url = $redirectUrl ?? (URL_BASE . '/index.php');
            header("Location: " . $url);
            exit;
        }
    }

    /**
     * Redirige a una página de error o inicio si no tiene el rol requerido.
     * @param string $rol Rol requerido.
     * @param string $redirectUrl URL a donde redirigir si no tiene el rol.
     */
    public function requireRole(string $rol, string $redirectUrl = null) {
        if (!$this->isRole($rol)) {
            $url = $redirectUrl ?? (URL_BASE . '/dashboard.php');
            header("Location: " . $url . "?error=no_permission");
            exit;
        }
    }
}