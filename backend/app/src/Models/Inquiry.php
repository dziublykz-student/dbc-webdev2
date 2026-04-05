<?php

namespace App\Models;

class Inquiry implements \JsonSerializable
{
    public function __construct(
        public readonly int $id,
        public readonly int $carId,
        public readonly string $name,
        public readonly string $email,
        public readonly string $message,
        public readonly string $createdAt
    ) {
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}