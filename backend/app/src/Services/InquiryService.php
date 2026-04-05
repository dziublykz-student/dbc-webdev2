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
}