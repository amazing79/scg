<?php

namespace App\Application\Users;

use App\Domain\Common\Conts\HttpStatusCode;
use App\Domain\Common\Conts\HttpStatusMessages;
use App\Domain\Common\Presenter;
use App\Domain\Common\Traits\VerifyPresenter;
use App\Domain\Users\Exceptions\InvalidCredentialsException;
use App\Domain\Users\Exceptions\UserNotFoundException;
use App\Domain\Users\UsersRepository;

class LoginUserCommandHandler
{
    use VerifyPresenter;
    public function __construct(
        private UsersRepository $repository,
        private ?Presenter $presenter = null
    )
    {
    }

    public function handle(array $values): array
    {
        $response = [];
        $response['code'] = HttpStatusCode::INTERNAL_SERVER_ERROR;
        $response['message'] = HttpStatusMessages::getMessage(HttpStatusCode::INTERNAL_SERVER_ERROR);
        try {
            $credentials = $values;
            $user = $this->repository->getUserByCredentials($credentials);
            if(is_null($user)) {
                throw new UserNotFoundException();
            }
            $password = $this->addPeeperToPassword($credentials);
            if(!password_verify($password, $user->getPassword())){
                throw new InvalidCredentialsException();
            }
            if($this->hasPresenter($this)) {
                $user = $this->presenter->convert($user);
            }
            $token = $this->repository->createUserSession($user);
            $response['token'] = $token;
            $response['code'] = HttpStatusCode::OK;
            $response['message'] = HttpStatusMessages::getMessage(HttpStatusCode::OK);

        } catch (UserNotFoundException|InvalidCredentialsException $e) {
            $response['message'] = "Code error: {$e->getCode()} - descripcion: {$e->getMessage()}";
            $response['code'] = $e->getCode();
        } catch (\Exception $e) {
            $response['message'] = "Code error: {$e->getCode()} - descripcion: {$e->getMessage()}";
        }
        return $response;
    }

    private function addPeeperToPassword($credentials): string
    {
        return hash_hmac("sha256", $credentials['password'], $credentials['pepper']);
    }
}