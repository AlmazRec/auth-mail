<?php

namespace App\Services\Interfaces;

interface AuthServiceInterface
{
    public function signUp(array $data);
    public function signIn(array $credentials);
    public function logout();
}
