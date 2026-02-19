<?php

namespace App\Controllers;



use App\Helpers\MailHelper;

class ContactoController {
    
    private $mailHelper;

    public function __construct() {
        $this->mailHelper = new MailHelper();
    }

    public function handleContactForm() {
        // Solo aceptamos POST
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: " . URL_BASE . "/contactanos.php"); 
            exit;
        }

        // 1. Sanitización
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);

        if (!$name || !$email || !$message) {
            header("Location: " . URL_BASE . "/contactanos.php?status=error_datos"); 
            exit;
        }

        // 2. Intentar enviar usando el Helper
        $sent = $this->mailHelper->sendContactMessage($name, $email, $message);

        if ($sent) {
            header("Location: " . URL_BASE . "/contactanos.php?status=success");
        } else {
            header("Location: " . URL_BASE . "/contactanos.php?status=error");
        }
        exit;
    }
}

