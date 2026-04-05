<?php

namespace App\Controllers;

use App\Framework\Controller;
use App\Helpers\JwtHelper;
use App\Services\InquiryService;
use App\Services\IInquiryService;

class InquiryController extends Controller
{
    private IInquiryService $inquiryService;

    public function __construct()
    {
        $this->inquiryService = new InquiryService();
    }

    public function getAll()
    {
        try {
            $user = $this->requireAdmin();

            if (!$user) {
                return $this->sendErrorResponse('Forbidden: admin access required', 403);
            }

            $inquiries = $this->inquiryService->getAll();
            return $this->sendSuccessResponse($inquiries);
        } catch (\Exception $e) {
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }

    public function create()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (!$this->inquiryService->isValidInquiryData($data)) {
                return $this->sendErrorResponse('Invalid inquiry data', 400);
            }

            $inquiry = $this->inquiryService->create($data);
            return $this->sendSuccessResponse($inquiry, 201);
        } catch (\Exception $e) {
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }

    private function requireAuth(): ?array
    {
        $token = JwtHelper::getBearerToken();

        if (!$token) {
            return null;
        }

        $decoded = JwtHelper::validateToken($token);

        if (!$decoded || !isset($decoded['data'])) {
            return null;
        }

        return (array) $decoded['data'];
    }

    private function requireAdmin(): ?array
    {
        $user = $this->requireAuth();

        if (!$user) {
            return null;
        }

        if (($user['role'] ?? '') !== 'admin') {
            return null;
        }

        return $user;
    }
}