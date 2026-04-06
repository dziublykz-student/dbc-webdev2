<?php

namespace App\Services;

use App\Models\Inquiry;

interface IInquiryService
{
    public function getAll(): array;
    public function create(array $data): Inquiry;
    public function update(int $id, array $data): ?Inquiry;
    public function isValidInquiryData(?array $data): bool;
    public function addCustomerMessageByToken(string $token, array $data): ?Inquiry;
    public function isValidCustomerFollowUpData(?array $data): bool;
    public function getByPublicToken(string $token): ?Inquiry;
}