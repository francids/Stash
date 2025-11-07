<?php
require __DIR__ . "/../vendor/autoload.php";

use App\Controllers\IndexController;

$path = isset($_SERVER["REQUEST_URI"]) ? parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) : "/";
$method = $_SERVER["REQUEST_METHOD"] ?? "GET";

header("Content-Type: application/json; charset=utf-8");

$indexController = new IndexController();

if ($path === "/" && $method === "GET") {
    $indexController->index();
} else if ($path === "/hello" && $method === "GET") {
    $indexController->hello();
} else {
    http_response_code(404);
    echo json_encode(["error" => "Not Found"]);
}