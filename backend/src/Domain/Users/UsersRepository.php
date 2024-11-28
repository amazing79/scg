<?php

namespace App\Domain\Users;

interface UsersRepository
{
    public function getUserByCredentials($credentials):?User;
    public function addUser(User $user):int;
    public function createUserSession(User $user):string;
    public function getUserBySessionId(string $sessionId):?User;
    public function deleteSession(string $sessionId):bool;
    public function getActiveUserSession(int $idUser): string;
    public function deleteUserSession(int $idUser): bool;
}