<?php

namespace App\Domain\Users;

interface UsersRepository
{
    public function getUserByCredentials($credentials):?User;
    public function addUser(User $user):int;
    public function createUserSession(User $user):string;

    public function getUserbySessionId(string $sessionId):?User;
    public function deleteUserSession(string $sessionId):bool;
}