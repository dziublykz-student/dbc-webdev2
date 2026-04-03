<?php

namespace App\Services;

use App\Helpers\JwtHelper;
use App\Repositories\IUserRepository;
use App\Repositories\UserRepository;

class AuthService implements IAuthService
{
    private IUserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function login(array $data): ?array
    {
        if (
            !isset($data['email']) ||
            !isset($data['password']) ||
            trim($data['email']) === '' ||
            trim($data['password']) === ''
        ) {
            return null;
        }

        $user = $this->userRepository->getByEmail($data['email']);

        if (!$user) {
            return null;
        }

        if (!password_verify($data['password'], $user->password)) {
            return null;
        }

        $token = JwtHelper::generateToken([
            'id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
        ]);

        return [
            'token' => $token,
            'user' => $user,
        ];
    }

    public function getAuthenticatedUser(string $token): ?array
    {
        $decoded = JwtHelper::validateToken($token);

        if (!$decoded || !isset($decoded['data'])) {
            return null;
        }

        $userData = (array) $decoded['data'];
        $user = $this->userRepository->getById((int)($userData['id'] ?? 0));

        if (!$user) {
            return null;
        }

        return [
            'user' => $user,
        ];
    }
}