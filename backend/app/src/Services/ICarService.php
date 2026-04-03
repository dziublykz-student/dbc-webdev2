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

    public function create(array $data): Car;

    public function update(int $id, array $data): ?Car;

    public function delete(int $id): bool;
}