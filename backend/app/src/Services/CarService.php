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
        $preparedFilters = $this->prepareFilters($filters);

        $page = max(1, $page);
        $limit = max(1, $limit);

        $total = $this->carRepository->countAll($preparedFilters);
        $cars = $this->carRepository->getAll($preparedFilters, $page, $limit);

        return [
            'data' => $cars,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'totalPages' => (int) ceil($total / $limit),
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

    public function isValidCarData(?array $data): bool
    {
        if (!$data) {
            return false;
        }

        $requiredFields = [
            'brand',
            'model',
            'year',
            'price',
            'mileage',
            'fuelType',
            'transmission',
            'status',
            'imageUrl',
            'description',
        ];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || trim((string) $data[$field]) === '') {
                return false;
            }
        }

        return true;
    }

    private function prepareFilters(array $filters): array
    {
        $prepared = [];

        if (isset($filters['brand']) && trim((string) $filters['brand']) !== '') {
            $prepared[] = [
                'field' => 'brand',
                'placeholder' => 'brand',
                'value' => trim((string) $filters['brand']),
            ];
        }

        if (isset($filters['fuelType']) && trim((string) $filters['fuelType']) !== '') {
            $prepared[] = [
                'field' => 'fuelType',
                'placeholder' => 'fuelType',
                'value' => trim((string) $filters['fuelType']),
            ];
        }

        if (isset($filters['status']) && trim((string) $filters['status']) !== '') {
            $prepared[] = [
                'field' => 'status',
                'placeholder' => 'status',
                'value' => trim((string) $filters['status']),
            ];
        }

        return $prepared;
    }
}