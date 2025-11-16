<?php
header("Content-Type: application/json");

require './vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

$secret_key = "{{JWT_SECRET}}"; // Replace with your secret key

if (!isset($_COOKIE['token'])) {
    http_response_code(401);
    echo json_encode(["error" => "No token provided"]);
    exit();
}

$jwt = $_COOKIE['token'];

try {
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    if ($decoded->data->isAdmin === 1) {
        http_response_code(200);
        echo json_encode(["message" => "Token is valid", "isAdmin" => true]);
    } else {
        http_response_code(200);
        echo json_encode(["message" => "Token is valid", "isAdmin" => false]);
    }
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid token"]);
}
?>