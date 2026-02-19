<?php

namespace App\Controllers;



use App\Helpers\MailHelper;

class ContactoController
{

    private $mailHelper;

    public function __construct()
    {
        $this->mailHelper = new MailHelper();
    }

    public function handleContactForm()
    {
        // Solo aceptamos POST
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: " . URL_BASE . "/contactanos.php");
            exit;
        }

        $name    = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $email   = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $phone   = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS);
        $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_SPECIAL_CHARS);
        $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);

        if (!$name || !$email || !$subject || !$message) {
            header("Location: " . URL_BASE . "/contactanos.php?status=error_datos");
            exit;
        }

        $sent = $this->mailHelper->sendContactMessage($name, $email, $phone, $subject, $message);

        if ($sent) {
            header("Location: " . URL_BASE . "/contactanos.php?status=success");
        } else {
            header("Location: " . URL_BASE . "/contactanos.php?status=error");
        }
        exit;
    }
}
