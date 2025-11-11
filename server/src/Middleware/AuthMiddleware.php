<?php
namespace App\Middleware;

use App\Services\JWTService;

class AuthMiddleware
{
    private JWTService $jwtService;

    public function __construct(JWTService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    public function authenticate(): int
    {
        $token = $this->jwtService->getTokenFromHeader();

        if (!$token) {
            http_response_code(401);
            echo json_encode(["error" => "Token not provided"]);
            exit;
        }

        $validation = $this->jwtService->verifyToken($token);
        if (!$validation["valid"]) {
            http_response_code(401);
            echo json_encode(["error" => "Invalid token", "details" => $validation["error"] ?? "Unknown error"]);
            exit;
        }

        return $validation["user_id"];
    }
}
