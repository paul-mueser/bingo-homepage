<?php
header("Content-Type: application/json");
include 'secrets.php';

require './vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

if (!isset($_COOKIE['refresh_token'])) {
    http_response_code(401);
    echo json_encode(["error" => "No refresh token provided"]);
    exit();
}

$refresh_jwt = $_COOKIE['refresh_token'];

try {
    $decoded = JWT::decode($refresh_jwt, new Key($secret_key, 'HS256'));

    $issuer_claim = $site_url; // Replace with your domain
    $audience_claim = $site_url;
    $issuedat_claim = time(); // issued at
    $notbefore_claim = $issuedat_claim; // not before in seconds
    $expire_claim = $issuedat_claim + 3600; // expire time in seconds

    $token = array(
        "iss" => $issuer_claim,
        "aud" => $audience_claim,
        "iat" => $issuedat_claim,
        "nbf" => $notbefore_claim,
        "exp" => $expire_claim,
        "data" => array(
            "id" => $decoded->data->id,
            "username" => $decoded->data->username,
            "isAdmin" => $decoded->data->isAdmin
        )
    );

    $jwt = JWT::encode($token, $secret_key, 'HS256');

    // Set the new JWT token as an HTTP-only and secure cookie
    setcookie("token", $jwt, $expire_claim, "/", "", true, true);

    http_response_code(200);
    echo json_encode(["message" => "Token refreshed", "isAdmin" => $decoded->data->isAdmin]);
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid refresh token"]);
}
?>