<?php

namespace App\Services;

use App\Models\Car;

interface ICarService
{
    /**
     * @return Car[]
     */
    public function getAll(): array;

    public function getById(int $id): ?Car;
}