<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository implements IUserRepository
{
    private string $dataFile;

    public function __construct()
    {
        $this->dataFile = __DIR__ . '/../data/users.json';
    }

    public function getByEmail(string $email): ?User
    {
        $users = $this->readData();

        foreach ($users as $user) {
            if (strtolower($user['email']) === strtolower($email)) {
                return $this->mapToUser($user);
            }
        }

        return null;
    }

    public function getById(int $id): ?User
    {
        $users = $this->readData();

        foreach ($users as $user) {
            if ((int)$user['id'] === $id) {
                return $this->mapToUser($user);
            }
        }

        return null;
    }

    private function readData(): array
    {
        $json = file_get_contents($this->dataFile);
        return json_decode($json, true) ?? [];
    }

    private function mapToUser(array $user): User
    {
        return new User(
            id: (int)$user['id'],
            name: $user['name'],
            email: $user['email'],
            password: $user['password'],
            role: $user['role']
        );
    }
}