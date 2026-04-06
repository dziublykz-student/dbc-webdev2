<?php

namespace App\Services;

use App\Models\Car;

interface ICarService
{
    public function getAll(array $filters = [], int $page = 1, int $limit = 10): array;
    public function getById(int $id): ?Car;
    public function create(array $data): Car;
    public function update(int $id, array $data): ?Car;
    public function delete(int $id): bool;
    public function validateCarData(array $data): void;
}