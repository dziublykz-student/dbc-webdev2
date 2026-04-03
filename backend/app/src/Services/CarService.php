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

    public function getAll(): array
    {
        return $this->carRepository->getAll();
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