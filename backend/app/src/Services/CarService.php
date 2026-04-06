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
        $this->validateCarData($data);
        return $this->carRepository->create($data);
    }

    public function update(int $id, array $data): ?Car
    {
        $this->validateCarData($data);
        return $this->carRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->carRepository->delete($id);
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

    public function validateCarData(array $data): void
    {
        $year = (int)($data['year'] ?? 0);
        $price = (float)($data['price'] ?? -1);
        $mileage = (int)($data['mileage'] ?? -1);
        $status = $data['status'] ?? '';
        $fuelType = $data['fuelType'] ?? '';
        $allowedFuelTypes = ['Petrol', 'Diesel', 'Electric', 'Hybrid'];
        $allowedStatuses = ['Available', 'Sold'];
        $transmission = trim((string) ($data['transmission'] ?? ''));
        $allowedTransmissions = ['Manual', 'Automatic'];

        if ($year < 1965 || $year > (int)date('Y')) {
            throw new \InvalidArgumentException('Year must be between 1965 and current year.');
        }

        if ($price < 0) {
            throw new \InvalidArgumentException('Price must be positive.');
        }

        if ($mileage < 0) {
            throw new \InvalidArgumentException('Mileage must be positive.');
        }

        if (!in_array($fuelType, $allowedFuelTypes, true)) {
            throw new \InvalidArgumentException('Invalid fuel type.');
        }
        
        if (!in_array($transmission, $allowedTransmissions, true)) {
            throw new \InvalidArgumentException('Invalid transmission.');
        }

        if (!in_array($status, $allowedStatuses, true)) {
            throw new \InvalidArgumentException('Invalid status value.');
        }

        $requiredFields = ['brand','model','fuelType','transmission','imageUrl','description'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field]) || trim((string)$data[$field]) === '') {
                throw new \InvalidArgumentException("$field is required.");
            }
        }
    }
}
