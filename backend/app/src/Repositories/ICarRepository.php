<?php

namespace App\Repositories;

use App\Models\Car;

interface ICarRepository
{
    /**
     * @return Car[]
     */
    public function getAll(): array;

    public function getById(int $id): ?Car;
}