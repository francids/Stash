<?php
namespace App\Services;

use App\Database\Connection;

class ItemsService
{
    private \PDO $db;

    public function __construct(Connection $conn)
    {
        $this->db = $conn->get();
    }

    public function isASupportedType(?string $type): bool
    {
        return in_array($type, ["note", "link"]);
    }

    public function getAll(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM items WHERE user_id = ?");

        $stmt->execute([$userId]);

        return $stmt->fetchAll() ?: [];
    }

    public function add(int $userId, string $content, ?string $type = null): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO items (user_id, type, content)
            VALUES (:user_id, :type, :content)"
        );

        $stmt->execute([
            "user_id" => $userId,
            "type" => $type,
            "content" => $content,
        ]);

        return $stmt->rowCount() ?: 0;
    }

    public function edit(int $userId, string $itemId, string $newContent): int
    {
        $stmt = $this->db->prepare(
            "UPDATE items SET content = :new_content WHERE id = :item_id AND user_id = :user_id"
        );

        $stmt->execute([
            "new_content" => $newContent,
            "user_id" => $userId,
            "item_id" => $itemId,
        ]);

        $rowCount = $stmt->rowCount();

        if ($rowCount === 0) {
            throw new \Exception("Item not found", 404);
        }

        return $rowCount;
    }

    public function remove(int $userId, string $itemId): int
    {
        $stmt = $this->db->prepare(
            "DELETE FROM items WHERE id = :item_id AND user_id = :user_id"
        );

        $stmt->execute([
            "user_id" => $userId,
            "item_id" => $itemId,
        ]);

        $rowCount = $stmt->rowCount();

        if ($rowCount === 0) {
            throw new \Exception("Item not found", 404);
        }

        return $rowCount;
    }
}