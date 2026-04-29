<?php

namespace App\Models;

class InquiryMessage implements \JsonSerializable
{
    public function __construct(
        public readonly int $id,
        public readonly int $inquiryId,
        public readonly string $senderType,
        public readonly string $message,
        public readonly string $createdAt
    ) {
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}