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
            $user = $this->requireStaff();

            if (!$user) {
                return $this->sendErrorResponse('Forbidden: staff access required', 403);
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

    public function update($vars = [])
    {
        try {
            $user = $this->requireStaff();

            if (!$user) {
                return $this->sendErrorResponse('Forbidden: staff access required', 403);
            }

            $id = (int) ($vars['id'] ?? 0);
            $data = json_decode(file_get_contents('php://input'), true);

            if (!$this->inquiryService->isValidInquiryUpdateData($data)) {
                return $this->sendErrorResponse('Invalid inquiry update data', 400);
            }

            $inquiry = $this->inquiryService->update($id, $data);

            if (!$inquiry) {
                return $this->sendErrorResponse('Inquiry not found', 404);
            }

            return $this->sendSuccessResponse($inquiry);
        } catch (\Exception $e) {
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }

    public function addCustomerMessage($vars = [])
    {
        try {
            $id = (int) ($vars['id'] ?? 0);
            $data = json_decode(file_get_contents('php://input'), true);

            if (!$this->inquiryService->isValidCustomerFollowUpData($data)) {
                return $this->sendErrorResponse('Invalid follow-up data', 400);
            }

            $inquiry = $this->inquiryService->addCustomerMessage($id, $data);

            if (!$inquiry) {
                return $this->sendErrorResponse('Inquiry not found or email does not match', 404);
            }

            return $this->sendSuccessResponse($inquiry);
        } catch (\Exception $e) {
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }

    public function getOneForCustomer($vars = [])
    {
        try {
            $id = (int) ($vars['id'] ?? 0);
            $email = trim((string) ($_GET['email'] ?? ''));

            if ($email === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                return $this->sendErrorResponse('Invalid inquiry access data', 400);
            }

            $inquiry = $this->inquiryService->getByIdAndEmail($id, $email);

            if (!$inquiry) {
                return $this->sendErrorResponse('Inquiry not found or email does not match', 404);
            }

            return $this->sendSuccessResponse($inquiry);
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

    private function requireStaff(): ?array
    {
        $user = $this->requireAuth();

        if (!$user) {
            return null;
        }

        $role = $user['role'] ?? '';

        if (!in_array($role, ['admin', 'employee'], true)) {
            return null;
        }

        return $user;
    }
}