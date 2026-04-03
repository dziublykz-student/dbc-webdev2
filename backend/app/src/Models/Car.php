<?php

namespace App\Models;

class Car implements \JsonSerializable
{
    public function __construct(
        public readonly int $id,
        public readonly string $brand,
        public readonly string $model,
        public readonly int $year,
        public readonly float $price,
        public readonly int $mileage,
        public readonly string $fuelType,
        public readonly string $transmission,
        public readonly string $status,
        public readonly string $imageUrl,
        public readonly string $description
    ) {
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}