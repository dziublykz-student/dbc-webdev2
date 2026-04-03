<?php

namespace App\Repositories;

use App\Models\Car;

class CarRepository implements ICarRepository
{
    private string $dataFile;

    public function __construct()
    {
        $this->dataFile = __DIR__ . '/../data/cars.json';
    }

    public function getAll(): array
    {
        $json = file_get_contents($this->dataFile);
        $data = json_decode($json, true);

        return array_map(fn(array $car) => $this->mapToCar($car), $data);
    }

    public function getById(int $id): ?Car
    {
        $cars = $this->getAll();

        foreach ($cars as $car) {
            if ($car->id === $id) {
                return $car;
            }
        }

        return null;
    }

    private function mapToCar(array $car): Car
    {
        return new Car(
            id: $car['id'],
            brand: $car['brand'],
            model: $car['model'],
            year: $car['year'],
            price: (float) $car['price'],
            mileage: $car['mileage'],
            fuelType: $car['fuelType'],
            transmission: $car['transmission'],
            status: $car['status'],
            imageUrl: $car['imageUrl'],
            description: $car['description']
        );
    }
}