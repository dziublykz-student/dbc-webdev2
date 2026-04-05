<?php

namespace App\Repositories;

use App\Models\User;
use App\Utils\Database;
use PDO;

class UserRepository implements IUserRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function getByEmail(string $email): ?User
    {
        $stmt = $this->connection->prepare("
            SELECT * FROM users
            WHERE email = :email
            LIMIT 1
        ");

        $stmt->bindValue(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch();

        return $user ? $this->mapToUser($user) : null;
    }

    public function getById(int $id): ?User
    {
        $stmt = $this->connection->prepare("
            SELECT * FROM users
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $user = $stmt->fetch();

        return $user ? $this->mapToUser($user) : null;
    }

    private function mapToUser(array $user): User
    {
        return new User(
            id: (int) $user['id'],
            name: $user['name'],
            email: $user['email'],
            password: $user['password'],
            role: $user['role']
        );
    }
}