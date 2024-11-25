<?php

namespace App\Domain\Users;

interface UsersRepository
{
    public function login($credentials):?User;
    public function addUser(User $user):int;
}