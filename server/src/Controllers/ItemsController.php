<?php
namespace App\Controllers;

use App\Services\ItemsService;

class ItemsController
{
    private ItemsService $itemsService;

    public function __construct(ItemsService $itemsService)
    {
        $this->itemsService = $itemsService;
    }

    public function getAll(int $userId): void
    {
        echo json_encode($this->itemsService->getAll($userId));
        exit;
    }

    public function add(int $userId): void
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["content"])) {
            http_response_code(400);
            echo json_encode(["error" => "Content is required"]);
            exit;
        }

        if (isset($data["type"]) && !$this->itemsService->isASupportedType($data["type"])) {
            http_response_code(400);
            echo json_encode(["error" => "The value of 'type' is not a supported type"]);
            exit;
        }

        $rowsAdded = $this->itemsService->add($userId, $data["content"], $data["type"]);

        if ($rowsAdded > 0) {
            http_response_code(201);
            echo json_encode(["message" => "Item added successfully"]);
            exit;
        }

        http_response_code(500);
        echo json_encode(["message" => "Something went wrong"]);
        exit;
    }

    public function edit(int $userId): void
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["id"]) || !isset($data["content"])) {
            http_response_code(400);
            echo json_encode(["error" => "Id and content are required"]);
            exit;
        }

        try {
            $this->itemsService->edit($userId, $data["id"], $data["content"]);
            http_response_code(200);
            echo json_encode(["message" => "Item edited successfully"]);
        } catch (\Exception $e) {
            http_response_code($e->getCode() ?: 500);
            echo json_encode(["error" => $e->getMessage()]);
        }
        exit;
    }

    public function remove(int $userId): void
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["id"])) {
            http_response_code(400);
            echo json_encode(["error" => "Id is required"]);
            exit;
        }

        try {
            $this->itemsService->remove($userId, $data["id"]);
            http_response_code(200);
            echo json_encode(["message" => "Item removed successfully"]);
        } catch (\Exception $e) {
            http_response_code($e->getCode() ?: 500);
            echo json_encode(["error" => $e->getMessage()]);
        }
        exit;
    }
}