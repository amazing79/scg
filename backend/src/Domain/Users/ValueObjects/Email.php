<?php

namespace App\Domain\Users\ValueObjects;

class Email
{
    private string $mail;

    public function __construct (string $mail)
    {
        if(!filter_var($mail, FILTER_VALIDATE_EMAIL)){
            throw new \InvalidArgumentException("El mail ingresado: {$mail}, no es válido");
        }
        $this->mail = $mail;
    }

    /**
     * @return string
     */
    public function getMail(): string
    {
        return $this->mail;
    }
}