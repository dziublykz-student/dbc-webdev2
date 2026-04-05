<?php

namespace App\Repositories;

use App\Models\Inquiry;
use App\Utils\Database;
use PDO;

class InquiryRepository implements IInquiryRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function getAll(): array
    {
        $stmt = $this->connection->query("
            SELECT * FROM inquiries
            ORDER BY createdAt DESC
        ");

        $rows = $stmt->fetchAll();

        return array_map(fn(array $row) => $this->mapToInquiry($row), $rows);
    }

    public function create(array $data): Inquiry
    {
        $stmt = $this->connection->prepare("
            INSERT INTO inquiries (carId, name, email, message)
            VALUES (:carId, :name, :email, :message)
        ");

        $stmt->execute([
            'carId' => (int) $data['carId'],
            'name' => $data['name'],
            'email' => $data['email'],
            'message' => $data['message'],
        ]);

        $id = (int) $this->connection->lastInsertId();

        $stmt = $this->connection->prepare("
            SELECT * FROM inquiries WHERE id = :id
        ");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $this->mapToInquiry($stmt->fetch());
    }

    private function mapToInquiry(array $row): Inquiry
    {
        return new Inquiry(
            id: (int) $row['id'],
            carId: (int) $row['carId'],
            name: $row['name'],
            email: $row['email'],
            message: $row['message'],
            createdAt: $row['createdAt']
        );
    }
}