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
        public readonly string $createdAt,
        public readonly string $publicToken,
        public readonly ?string $adminReply = null,
        public readonly string $status = 'new',
        public readonly array $messages = []
    ) {
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}