<?php

namespace App\Services;

interface IAuthService
{
    public function login(array $data): ?array;

    public function getAuthenticatedUser(string $token): ?array;
}