<?php

namespace App\Repositories;

use App\Models\Car;

interface ICarRepository
{
    /**
     * @return Car[]
     */
    public function getAll(array $filters = [], int $page = 1, int $limit = 10): array;

    public function countAll(array $filters = []): int;

    public function getById(int $id): ?Car;

    public function create(array $data): Car;

    public function update(int $id, array $data): ?Car;

    public function delete(int $id): bool;
}