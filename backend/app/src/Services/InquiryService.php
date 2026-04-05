<?php

namespace App\Services;

use App\Models\Inquiry;
use App\Repositories\IInquiryRepository;
use App\Repositories\InquiryRepository;

class InquiryService implements IInquiryService
{
    private IInquiryRepository $inquiryRepository;

    public function __construct()
    {
        $this->inquiryRepository = new InquiryRepository();
    }

    public function getAll(): array
    {
        return $this->inquiryRepository->getAll();
    }

    public function create(array $data): Inquiry
    {
        return $this->inquiryRepository->create($data);
    }

    public function update(int $id, array $data): ?Inquiry
    {
        return $this->inquiryRepository->update($id, $data);
    }

    public function addCustomerMessage(int $id, array $data): ?Inquiry
    {
        return $this->inquiryRepository->addCustomerMessage($id, $data);
    }

    public function getByIdAndEmail(int $id, string $email): ?Inquiry
    {
        return $this->inquiryRepository->getByIdAndEmail($id, $email);
    }

    public function isValidInquiryData(?array $data): bool
    {
        if (!$data) {
            return false;
        }

        $requiredFields = ['carId', 'name', 'email', 'message'];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || trim((string) $data[$field]) === '') {
                return false;
            }
        }

        return filter_var($data['email'], FILTER_VALIDATE_EMAIL) !== false;
    }

    public function isValidInquiryUpdateData(?array $data): bool
    {
        if (!$data) {
            return false;
        }

        if (!isset($data['status']) || trim((string) $data['status']) === '') {
            return false;
        }

        $allowedStatuses = ['new', 'handled'];

        if (!in_array($data['status'], $allowedStatuses, true)) {
            return false;
        }

        if (isset($data['adminReply']) && !is_string($data['adminReply'])) {
            return false;
        }

        return true;
    }

    public function isValidCustomerFollowUpData(?array $data): bool
    {
        if (!$data) {
            return false;
        }

        if (
            !isset($data['email']) ||
            !isset($data['message']) ||
            trim((string) $data['email']) === '' ||
            trim((string) $data['message']) === ''
        ) {
            return false;
        }

        return filter_var($data['email'], FILTER_VALIDATE_EMAIL) !== false;
    }
}