<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHelper
{
    private const SECRET_KEY = 'super_secret_webdev2_key_change_later';
    private const ALGORITHM = 'HS256';

    public static function generateToken(array $payload): string
    {
        $issuedAt = time();
        $expiresAt = $issuedAt + (60 * 60 * 4);

        $tokenPayload = [
            'iat' => $issuedAt,
            'exp' => $expiresAt,
            'data' => $payload,
        ];

        return JWT::encode($tokenPayload, self::SECRET_KEY, self::ALGORITHM);
    }

    public static function validateToken(string $token): ?array
    {
        try {
            $decoded = JWT::decode($token, new Key(self::SECRET_KEY, self::ALGORITHM));
            return (array) $decoded;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getBearerToken(): ?string
    {
        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            return null;
        }

        if (preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
            return $matches[1];
        }

        return null;
    }
}