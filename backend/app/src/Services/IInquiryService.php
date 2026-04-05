<?php

namespace App\Services;

use App\Models\Inquiry;

interface IInquiryService
{
    public function getAll(): array;
    public function create(array $data): Inquiry;
    public function update(int $id, array $data): ?Inquiry;
    public function isValidInquiryData(?array $data): bool;
    public function isValidInquiryUpdateData(?array $data): bool;
    public function addCustomerMessage(int $id, array $data): ?Inquiry;
    public function isValidCustomerFollowUpData(?array $data): bool;
    public function getByIdAndEmail(int $id, string $email): ?Inquiry;
}