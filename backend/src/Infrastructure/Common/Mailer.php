<?php

namespace App\Infrastructure\Common;

use PHPMailer\PHPMailer\Exception as PHPMailerException;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    private PHPMailer $mailer;

    public function __construct()
    {
        $this->mailer = $this->createPHPMailer();
    }

    public function sendMailWithNewPassword($userEmail, $newPassword): bool
    {
        $success =false;
        try{
            $this->mailer->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
            $this->mailer->addAddress($userEmail);
            $this->mailer->Subject = $_ENV['MAIL_SUBJECT'];
            $this->mailer->Body = $this->getBodyMessage($newPassword);

            $success = $this->mailer->send();
        } catch (PHPMailerException $e) {
            echo "Message could not be sent. Mailer Error: {$this->mailer->ErrorInfo}";
        }

        return $success;
    }

    private function getBodyMessage($clave):string
    {
        $mensaje = "El proceso de blanqueo de cuenta ha sido exitoso. Su nueva clave es: <strong>" . $clave ."</strong>";
        $mensaje .= "<br />Atentamente " ;
        $mensaje .= "<br />Informatica Team - Facultad de Ingenier&iacute;a " ;

        return $mensaje;
    }


    private function getPHPMailerConfig(): array
    {
        //todo: Se puede llevar config a un archivo config.ini
        $config = [];
        $config['host'] = $_ENV['MAILER_HOST'];
        $config['security'] = PHPMailer::ENCRYPTION_STARTTLS; //valores posibles ssl | tls
        $config['port'] = $_ENV['MAILER_PORT'];
        $config['user'] = $_ENV['MAILER_USER'];
        $config['password'] = $_ENV['MAILER_PASSWORD'];
        $config['useAutentication'] = true;
        $config['isHTML'] = true;

        return $config;
    }

    private function createPHPMailer(): PHPMailer
    {
        $config = $this->getPHPMailerConfig();
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->Host = $config['host'];
        $mail->Port = $config['port'];
        $mail->SMTPSecure = $config['security'];
        $mail->SMTPAuth = $config['useAutentication'];
        $mail->Username = $config['user'];
        $mail->Password = $config['password'];
        $mail->From = $config['user'];
        $mail->IsHTML($config['isHTML']);

        return $mail;
    }
}