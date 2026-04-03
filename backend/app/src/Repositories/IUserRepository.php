<?php

namespace App\Repositories;

use App\Models\User;

interface IUserRepository
{
    public function getByEmail(string $email): ?User;

    public function getById(int $id): ?User;
}