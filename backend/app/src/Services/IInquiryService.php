<?php

namespace App\Services;

use App\Models\Inquiry;

interface IInquiryService
{
    /**
     * @return Inquiry[]
     */
    public function getAll(): array;

    public function create(array $data): Inquiry;

    public function isValidInquiryData(?array $data): bool;
}