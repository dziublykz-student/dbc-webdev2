<?php

namespace App\Repositories;

use App\Models\Car;
use App\Utils\Database;
use PDO;

class CarRepository implements ICarRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function getAll(array $filters = [], int $page = 1, int $limit = 10): array
    {
        $sql = "SELECT * FROM cars";
        $params = [];

        [$whereClause, $params] = $this->buildWhereClause($filters);

        if ($whereClause !== '') {
            $sql .= ' ' . $whereClause;
        }

        $offset = max(0, ($page - 1) * $limit);
        $sql .= " LIMIT :limit OFFSET :offset";

        $stmt = $this->connection->prepare($sql);

        $this->bindFilterParams($stmt, $params);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        $cars = $stmt->fetchAll();

        return array_map(fn(array $car) => $this->mapToCar($car), $cars);
    }

    public function countAll(array $filters = []): int
    {
        $sql = "SELECT COUNT(*) as total FROM cars";
        $params = [];

        [$whereClause, $params] = $this->buildWhereClause($filters);

        if ($whereClause !== '') {
            $sql .= ' ' . $whereClause;
        }

        $stmt = $this->connection->prepare($sql);
        $this->bindFilterParams($stmt, $params);
        $stmt->execute();

        $result = $stmt->fetch();

        return (int) ($result['total'] ?? 0);
    }

    public function getById(int $id): ?Car
    {
        $stmt = $this->connection->prepare("SELECT * FROM cars WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $car = $stmt->fetch();

        return $car ? $this->mapToCar($car) : null;
    }

    public function create(array $data): Car
    {
        $stmt = $this->connection->prepare("
            INSERT INTO cars (
                brand, model, year, price, mileage,
                fuelType, transmission, status, imageUrl, description
            ) VALUES (
                :brand, :model, :year, :price, :mileage,
                :fuelType, :transmission, :status, :imageUrl, :description
            )
        ");

        $stmt->execute([
            'brand' => $data['brand'],
            'model' => $data['model'],
            'year' => (int) $data['year'],
            'price' => (float) $data['price'],
            'mileage' => (int) $data['mileage'],
            'fuelType' => $data['fuelType'],
            'transmission' => $data['transmission'],
            'status' => $data['status'],
            'imageUrl' => $data['imageUrl'],
            'description' => $data['description'],
        ]);

        return $this->getById((int) $this->connection->lastInsertId());
    }

    public function update(int $id, array $data): ?Car
    {
        $stmt = $this->connection->prepare("
            UPDATE cars
            SET
                brand = :brand,
                model = :model,
                year = :year,
                price = :price,
                mileage = :mileage,
                fuelType = :fuelType,
                transmission = :transmission,
                status = :status,
                imageUrl = :imageUrl,
                description = :description
            WHERE id = :id
        ");

        $stmt->execute([
            'id' => $id,
            'brand' => $data['brand'],
            'model' => $data['model'],
            'year' => (int) $data['year'],
            'price' => (float) $data['price'],
            'mileage' => (int) $data['mileage'],
            'fuelType' => $data['fuelType'],
            'transmission' => $data['transmission'],
            'status' => $data['status'],
            'imageUrl' => $data['imageUrl'],
            'description' => $data['description'],
        ]);

        return $this->getById($id);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->connection->prepare("DELETE FROM cars WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    private function buildWhereClause(array $filters): array
    {
        if (empty($filters)) {
            return ['', []];
        }

        $conditions = [];
        $params = [];

        foreach ($filters as $filter) {
            $conditions[] = "{$filter['field']} = :{$filter['placeholder']}";
            $params[$filter['placeholder']] = $filter['value'];
        }

        return ['WHERE ' . implode(' AND ', $conditions), $params];
    }

    private function bindFilterParams(\PDOStatement $stmt, array $params): void
    {
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
    }

    private function mapToCar(array $car): Car
    {
        return new Car(
            id: (int) $car['id'],
            brand: $car['brand'],
            model: $car['model'],
            year: (int) $car['year'],
            price: (float) $car['price'],
            mileage: (int) $car['mileage'],
            fuelType: $car['fuelType'],
            transmission: $car['transmission'],
            status: $car['status'],
            imageUrl: $car['imageUrl'],
            description: $car['description']
        );
    }
}