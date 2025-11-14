<?php

namespace App\Application\Users;

use App\Domain\Common\Conts\HttpStatusCode;
use App\Domain\Common\Conts\HttpStatusMessages;
use App\Domain\Users\Exceptions\UserNotFoundException;
use App\Domain\Users\UsersRepository;
use App\Infrastructure\Common\Mailer;

class ResetUserPasswordCommandHandler
{
    public function __construct(
        private UsersRepository $repository,
    )
    {
    }

    public function handle(array $values): array
    {
        $response = [];
        $response['code'] = HttpStatusCode::INTERNAL_SERVER_ERROR;
        $response['message'] = HttpStatusMessages::getMessage(HttpStatusCode::INTERNAL_SERVER_ERROR);
        try {
            $user = $this->repository->getUserByCredentials($values);
            if (!$user) {
                throw new UserNotFoundException();
            }
            $password = $this->generateSecurePassword();
            $passwordWithPepper = $this->addPeeperToPassword($password, $values['pepper']);
            $hasedPassword = password_hash($passwordWithPepper, PASSWORD_BCRYPT);
            $this->repository->updateUserPassword($user, $hasedPassword);
            $mailer = new Mailer();
            $mailer->sendMailWithNewPassword($user->getEmail(), $password);
            $response['code'] = HttpStatusCode::OK;
            $response['message'] = "Se envio la nueva password al mail indicado.";
        } catch (\Exception $e) {
            $response['code'] = $e->getCode();
            $response['message'] = "Code error: {$e->getCode()} - descripcion: {$e->getMessage()}";
        }
        return $response;
    }

    private function addPeeperToPassword($passwordTemp, $pepper): string
    {
        return hash_hmac("sha256", $passwordTemp, $pepper);
    }

    function generateSecurePassword($longitud = 12) {
        // Definir los caracteres permitidos
        $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_+=';
        $longitud_caracteres = strlen($caracteres);
        $contrasena = '';
        // Generar la contraseña utilizando random_int()
        for ($i = 0; $i < $longitud; $i++) {
            try {
                $indice_aleatorio = random_int(0, $longitud_caracteres - 1);
                $contrasena .= $caracteres[$indice_aleatorio];
            } catch (\Exception $e) {
                // Manejar posibles errores (aunque es poco probable en entornos modernos)
                // En caso de error, se podría usar un metodo alternativo menos seguro o registrar el error.
                die("Error al generar número aleatorio seguro: " . $e->getMessage());
            }
        }

        return $contrasena;
    }
}
