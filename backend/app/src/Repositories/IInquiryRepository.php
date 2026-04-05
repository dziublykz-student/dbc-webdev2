<?php

namespace App\Repositories;

use App\Models\Inquiry;

interface IInquiryRepository
{
    /**
     * @return Inquiry[]
     */
    public function getAll(): array;

    public function create(array $data): Inquiry;
}