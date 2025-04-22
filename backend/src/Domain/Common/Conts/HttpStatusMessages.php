<?php

namespace App\Domain\Common\Conts;

class HttpStatusMessages
{
    private static array $messages = [
        HttpStatusCode::OK => 'OK',
        HttpStatusCode::BAD_REQUEST => 'Solicitud Incorrecta',
        HttpStatusCode::UNAUTHORIZED => 'No cuenta con los permisos para dicha solicitud',
        HttpStatusCode::FORBIDDEN => 'Acceso no permitido',
        HttpStatusCode::NOT_FOUND => 'Item no encontrado',
        HttpStatusCode::NOT_ACCEPTABLE => 'Not Acceptable',
        HttpStatusCode::INTERNAL_SERVER_ERROR => 'Error inesperado, por favor intente nuevamente mas tarde',
        HttpStatusCode::CREATED => 'Item creado',
        HttpStatusCode::METHOD_NOT_ALLOWED => 'Método no permitido o no disponible',
        ];

    public static function getMessage(int $statusCode): string
    {
        return self::$messages[$statusCode] ?? "Unknown status code - Codigo obtenido: {$statusCode}";
    }
}