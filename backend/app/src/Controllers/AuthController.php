<?php

namespace App\Controllers;

use App\Framework\Controller;
use App\Helpers\JwtHelper;
use App\Services\AuthService;
use App\Services\IAuthService;

class AuthController extends Controller
{
    private IAuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function login()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            $result = $this->authService->login($data ?? []);

            if (!$result) {
                return $this->sendErrorResponse('Invalid email or password', 401);
            }

            return $this->sendSuccessResponse($result);
        } catch (\Exception $e) {
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }

    public function me()
    {
        try {
            $token = JwtHelper::getBearerToken();

            if (!$token) {
                return $this->sendErrorResponse('Missing token', 401);
            }

            $result = $this->authService->getAuthenticatedUser($token);

            if (!$result) {
                return $this->sendErrorResponse('Invalid or expired token', 401);
            }

            return $this->sendSuccessResponse($result);
        } catch (\Exception $e) {
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }
}