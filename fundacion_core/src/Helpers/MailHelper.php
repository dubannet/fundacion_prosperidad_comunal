<?php
// /src/Helpers/MailHelper.php

namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Asume que las variables de entorno ya están cargadas en bootstrap.php
// (Eliminamos el llamado a Dotenv aquí si ya está en bootstrap.php)

class MailHelper
{
    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);

        // CRÍTICO: Asegurarse de que el bootstrap.php cargó las variables de entorno
        if (empty($_ENV['SMTP_HOST'])) {
            // Esto puede ser una excepción o un error fatal si la configuración falla
            error_log("FALTA CONFIGURACIÓN: Las variables de entorno SMTP no están cargadas.");
            // Puedes lanzar una excepción si es crítico: throw new Exception("Configuración de correo incompleta.");
        }

        try {
            // Configuración SMTP
            $this->mail->isSMTP();
            $this->mail->Host = $_ENV['SMTP_HOST'];
            $this->mail->SMTPAuth = true;
            $this->mail->Username = $_ENV['SMTP_USER'];
            $this->mail->Password = $_ENV['SMTP_PASS'];
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $this->mail->Port = $_ENV['SMTP_PORT'];
            $this->mail->SMTPOptions = [ // Añadimos esto por si estás en un entorno de desarrollo local
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];

            // Remitente por defecto
            $this->mail->setFrom($_ENV['MAIL_FROM_EMAIL'], $_ENV['MAIL_FROM_NAME']);
            $this->mail->isHTML(true);
            $this->mail->CharSet = 'UTF-8';
        } catch (Exception $e) {
            error_log("Error de configuración de PHPMailer: " . $e->getMessage());
        }
    }

    /**
     * Envía un correo de agradecimiento por donación.
     * @param array $data Datos completos de la donación (email, nombre, monto_centavos, referencia, proyecto_nombre)
     * @return bool
     */
    public function sendDonationThankYou(array $data): bool
    {
        // Extraer datos clave
        $toEmail = $data['email'] ?? null;
        $toName = $data['nombre'] ?? 'Donante';
        $amountInCents = $data['monto_centavos'] ?? 0;
        $reference = $data['referencia'] ?? 'N/A';
        $proyectoNombre = $data['proyecto_nombre'] ?? 'Fondos Generales';

        if (!$toEmail) {
            error_log("Error al enviar correo de donación: Email no proporcionado.");
            return false;
        }

        $amount = $amountInCents / 100; // Convertir a pesos

        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($toEmail, $toName);

            $this->mail->Subject = '¡Gracias por tu donación a la Fundación!';

            $body = $this->buildThankYouBody($toName, $amount, $reference, $proyectoNombre);

            $this->mail->Body = $body;
            $this->mail->AltBody = "Gracias {$toName} por tu donación de $" . number_format($amount, 0) . " COP para el proyecto {$proyectoNombre}.";

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Error al enviar correo de donación a {$toEmail}: " . $this->mail->ErrorInfo);
            return false;
        }
    }

    /**
     * Construye el cuerpo HTML del correo de agradecimiento, incluyendo el proyecto.
     */
    private function buildThankYouBody(string $name, float $amount, string $reference, string $proyectoNombre): string
    {
        $formattedAmount = number_format($amount, 0, ',', '.');

        return "
            <div style='font-family: Arial, sans-serif; line-height: 1.6;'>
                <h2>¡Tu donación ha sido aprobada!</h2>
                <p>Estimado/a {$name},</p>
                <p>Queremos expresarte nuestra más sincera gratitud por tu generosa contribución a la Fundación.</p>
                <p>Tu donación de **$ {$formattedAmount} COP** se destinará al proyecto: <strong>{$proyectoNombre}</strong>.</p>
                <p><strong>Detalles de tu donación:</strong></p>
                <ul>
                    <li>Monto: <strong>$ {$formattedAmount} COP</strong></li>
                    <li>Destino: <strong>{$proyectoNombre}</strong></li>
                    <li>Referencia de Pago: <strong>{$reference}</strong></li>
                    <li>Fecha: " . date('Y-m-d H:i') . "</li>
                </ul>
                <p>Con tu ayuda, estamos logrando un impacto real. ¡Gracias por ser parte de nuestra causa!</p>
                <p>Atentamente,<br>El equipo de la Fundación.</p>
            </div>
        ";
    }

    /**
     * Envía el correo con el enlace para restablecer la contraseña.
     */
    public function sendPasswordReset(string $toEmail, string $toName, string $token): bool
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($toEmail, $toName);

            $this->mail->Subject = 'Restablecer tu contraseña - Fundación';

            // USAMOS URL_BASE que definimos en bootstrap.php
            // Como el archivo restablecer.php ahora está en la raíz del subdominio admin,
            // el enlace queda limpio.
            $resetLink = URL_BASE . "/restablecer.php?token=" . $token;

            $body = "
        <div style='font-family: Arial, sans-serif; border: 1px solid #ddd; padding: 20px; max-width: 600px;'>
            <h2 style='color: #333;'>Hola, {$toName}</h2>
            <p>Has solicitado restablecer tu contraseña para el panel de administración.</p>
            <p>Haz clic en el siguiente botón para continuar. Este enlace expirará en 1 hora.</p>
            <div style='text-align: center; margin: 30px 0;'>
                <a href='{$resetLink}' style='background-color: #007bff; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Restablecer Contraseña</a>
            </div>
            <p style='color: #777; font-size: 12px;'>Si no solicitaste este cambio, puedes ignorar este correo de forma segura.</p>
            <hr style='border: 0; border-top: 1px solid #eee;'>
            <p style='font-size: 11px; color: #999;'>Si el botón no funciona, copia y pega esta URL en tu navegador:<br>{$resetLink}</p>
        </div>
        ";

            $this->mail->Body = $body;
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Error al enviar recuperación a {$toEmail}: " . $this->mail->ErrorInfo);
            return false;
        }
    }

    /**
     * Envía un mensaje de contacto al administrador.
     */
    public function sendContactMessage(string $fromName, string $fromEmail, string $phone, string $subject, string $message): bool
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($_ENV['SMTP_USER'], 'Administrador');
            $this->mail->addReplyTo($fromEmail, $fromName);

            $this->mail->Subject = "Nuevo Mensaje de Contacto - " . $subject;

            $this->mail->Body = "
            <h2>Nuevo Mensaje de Contacto</h2>
            <p><strong>Nombre:</strong> {$fromName}</p>
            <p><strong>Email:</strong> {$fromEmail}</p>
            <p><strong>Teléfono:</strong> {$phone}</p>
            <p><strong>Asunto:</strong> {$subject}</p>
            <hr>
            <p><strong>Mensaje:</strong></p>
            <p>" . nl2br(htmlspecialchars($message)) . "</p>
        ";

            return $this->mail->send();
        } catch (Exception $e) {
            error_log("Error en MailHelper (Contacto): " . $this->mail->ErrorInfo);
            return false;
        }
    }
}
