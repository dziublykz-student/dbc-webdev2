<?php

namespace App\Repositories;

use App\Models\Inquiry;

interface IInquiryRepository
{
    public function getAll(): array;

    public function create(array $data): Inquiry;

    public function update(int $id, array $data): ?Inquiry;

    public function addCustomerMessage(int $id, array $data): ?Inquiry;

    public function addCustomerMessageByToken(string $token, array $data): ?Inquiry;

    public function getByPublicToken(string $token): ?Inquiry;
}