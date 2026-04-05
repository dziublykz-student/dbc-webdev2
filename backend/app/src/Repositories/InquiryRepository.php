<?php

namespace App\Repositories;

use App\Models\Inquiry;
use App\Models\InquiryMessage;
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

        $msgStmt = $this->connection->prepare("
            INSERT INTO inquiry_messages (inquiryId, senderType, message)
            VALUES (:inquiryId, :senderType, :message)
        ");

        $msgStmt->execute([
            'inquiryId' => $id,
            'senderType' => 'customer',
            'message' => $data['message'],
        ]);

        return $this->getById($id);
    }

    public function update(int $id, array $data): ?Inquiry
    {
        $stmt = $this->connection->prepare("
            UPDATE inquiries
            SET status = :status
            WHERE id = :id
        ");

        $stmt->execute([
            'id' => $id,
            'status' => $data['status'] ?? 'new',
        ]);

        if (isset($data['adminReply']) && trim((string) $data['adminReply']) !== '') {
            $msgStmt = $this->connection->prepare("
                INSERT INTO inquiry_messages (inquiryId, senderType, message)
                VALUES (:inquiryId, :senderType, :message)
            ");

            $msgStmt->execute([
                'inquiryId' => $id,
                'senderType' => 'admin',
                'message' => trim((string) $data['adminReply']),
            ]);
        }

        return $this->getById($id);
    }

    private function getById(int $id): ?Inquiry
    {
        $stmt = $this->connection->prepare("
            SELECT * FROM inquiries
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch();

        return $row ? $this->mapToInquiry($row) : null;
    }

    /**
     * @return InquiryMessage[]
     */
    private function getMessagesByInquiryId(int $inquiryId): array
    {
        $stmt = $this->connection->prepare("
            SELECT * FROM inquiry_messages
            WHERE inquiryId = :inquiryId
            ORDER BY createdAt ASC, id ASC
        ");

        $stmt->bindValue(':inquiryId', $inquiryId, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll();

        return array_map(
            fn(array $row) => new InquiryMessage(
                id: (int) $row['id'],
                inquiryId: (int) $row['inquiryId'],
                senderType: $row['senderType'],
                message: $row['message'],
                createdAt: $row['createdAt']
            ),
            $rows
        );
    }

    public function addCustomerMessage(int $id, array $data): ?Inquiry
    {
        $stmt = $this->connection->prepare("
            SELECT * FROM inquiries
            WHERE id = :id AND email = :email
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id,
            'email' => $data['email'],
        ]);

        $inquiry = $stmt->fetch();

        if (!$inquiry) {
            return null;
        }

        $msgStmt = $this->connection->prepare("
            INSERT INTO inquiry_messages (inquiryId, senderType, message)
            VALUES (:inquiryId, :senderType, :message)
        ");

        $msgStmt->execute([
            'inquiryId' => $id,
            'senderType' => 'customer',
            'message' => trim((string) $data['message']),
        ]);

        $updateStmt = $this->connection->prepare("
            UPDATE inquiries
            SET status = 'new'
            WHERE id = :id
        ");
        $updateStmt->execute(['id' => $id]);

        return $this->getById($id);
    }

    public function getByIdAndEmail(int $id, string $email): ?Inquiry
    {
        $stmt = $this->connection->prepare("
            SELECT * FROM inquiries
            WHERE id = :id AND email = :email
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id,
            'email' => $email,
        ]);

        $row = $stmt->fetch();

        return $row ? $this->mapToInquiry($row) : null;
    }

    private function mapToInquiry(array $row): Inquiry
    {
        $messages = $this->getMessagesByInquiryId((int) $row['id']);

        $lastAdminReply = null;
        foreach (array_reverse($messages) as $message) {
            if ($message->senderType === 'admin') {
                $lastAdminReply = $message->message;
                break;
            }
        }

        return new Inquiry(
            id: (int) $row['id'],
            carId: (int) $row['carId'],
            name: $row['name'],
            email: $row['email'],
            message: $row['message'],
            createdAt: $row['createdAt'],
            adminReply: $lastAdminReply,
            status: $row['status'] ?? 'new',
            messages: $messages
        );
    }
}