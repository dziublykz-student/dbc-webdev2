<?php

namespace App\Services;

use App\Models\Car;
use App\Repositories\ICarRepository;
use App\Repositories\CarRepository;

class CarService implements ICarService
{
    private ICarRepository $carRepository;

    public function __construct()
    {
        $this->carRepository = new CarRepository();
    }

    public function getAll(array $filters = [], int $page = 1, int $limit = 10): array
    {
        $total = $this->carRepository->countAll($filters);
        $cars = $this->carRepository->getAll($filters, $page, $limit);

        return [
            'data' => $cars,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'totalPages' => $limit > 0 ? (int) ceil($total / $limit) : 1,
            ],
        ];
    }

    public function getById(int $id): ?Car
    {
        return $this->carRepository->getById($id);
    }

    public function create(array $data): Car
    {
        return $this->carRepository->create($data);
    }

    public function update(int $id, array $data): ?Car
    {
        return $this->carRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->carRepository->delete($id);
    }
}