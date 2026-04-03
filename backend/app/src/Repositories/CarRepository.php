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
        $data = $this->readData();

        return array_map(fn(array $car) => $this->mapToCar($car), $data);
    }

    public function getById(int $id): ?Car
    {
        $data = $this->readData();

        foreach ($data as $car) {
            if ((int)$car['id'] === $id) {
                return $this->mapToCar($car);
            }
        }

        return null;
    }

    public function create(array $data): Car
    {
        $cars = $this->readData();

        $newId = empty($cars)
            ? 1
            : max(array_column($cars, 'id')) + 1;

        $newCar = [
            'id' => $newId,
            'brand' => $data['brand'],
            'model' => $data['model'],
            'year' => (int)$data['year'],
            'price' => (float)$data['price'],
            'mileage' => (int)$data['mileage'],
            'fuelType' => $data['fuelType'],
            'transmission' => $data['transmission'],
            'status' => $data['status'],
            'imageUrl' => $data['imageUrl'],
            'description' => $data['description'],
        ];

        $cars[] = $newCar;
        $this->writeData($cars);

        return $this->mapToCar($newCar);
    }

    public function update(int $id, array $data): ?Car
    {
        $cars = $this->readData();

        foreach ($cars as $index => $car) {
            if ((int)$car['id'] === $id) {
                $updatedCar = [
                    'id' => $id,
                    'brand' => $data['brand'],
                    'model' => $data['model'],
                    'year' => (int)$data['year'],
                    'price' => (float)$data['price'],
                    'mileage' => (int)$data['mileage'],
                    'fuelType' => $data['fuelType'],
                    'transmission' => $data['transmission'],
                    'status' => $data['status'],
                    'imageUrl' => $data['imageUrl'],
                    'description' => $data['description'],
                ];

                $cars[$index] = $updatedCar;
                $this->writeData($cars);

                return $this->mapToCar($updatedCar);
            }
        }

        return null;
    }

    public function delete(int $id): bool
    {
        $cars = $this->readData();
        $originalCount = count($cars);

        $cars = array_values(array_filter($cars, fn(array $car) => (int)$car['id'] !== $id));

        if (count($cars) === $originalCount) {
            return false;
        }

        $this->writeData($cars);
        return true;
    }

    private function readData(): array
    {
        $json = file_get_contents($this->dataFile);
        return json_decode($json, true) ?? [];
    }

    private function writeData(array $data): void
    {
        file_put_contents(
            $this->dataFile,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }

    private function mapToCar(array $car): Car
    {
        return new Car(
            id: (int)$car['id'],
            brand: $car['brand'],
            model: $car['model'],
            year: (int)$car['year'],
            price: (float)$car['price'],
            mileage: (int)$car['mileage'],
            fuelType: $car['fuelType'],
            transmission: $car['transmission'],
            status: $car['status'],
            imageUrl: $car['imageUrl'],
            description: $car['description']
        );
    }
}