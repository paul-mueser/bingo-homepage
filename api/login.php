<?php
header("Content-Type: application/json");

require './vendor/autoload.php';
use \Firebase\JWT\JWT;

$DB_HOST = '{{DB_HOST}}';
$DB_USER = '{{DB_USER}}';
$DB_PASS = '{{DB_PASS}}';
$DB_NAME = '{{DB_NAME}}';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["error" => "Database connection failed"]));
}

$data = json_decode(file_get_contents("php://input"), true);

$username = $data['username'];
$password = $data['password'];

// Fetch user from database
$stmt = $conn->prepare("SELECT id, password_hash, isAdmin FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || !password_verify($password, $user['password_hash'])) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid credentials"]);
} else {
    $secret_key = "{{JWT_SECRET}}"; // Replace with your secret key
    $issuer_claim = "{{SITE_URL}}"; // Replace with your domain
    $audience_claim = "{{SITE_URL}}"; // Replace with your domain
    $issuedat_claim = time(); // issued at
    $notbefore_claim = $issuedat_claim; // not before in seconds
    $expire_claim = $issuedat_claim + 3600; // expire time in seconds
    $refresh_expire_claim = $issuedat_claim + 86400; // refresh token expire time in seconds

    $token = array(
        "iss" => $issuer_claim,
        "aud" => $audience_claim,
        "iat" => $issuedat_claim,
        "nbf" => $notbefore_claim,
        "exp" => $expire_claim,
        "data" => array(
            "id" => $user['id'],
            "username" => $username,
            "isAdmin" => $user['isAdmin']
        )
    );

    $refresh_token = array(
        "iss" => $issuer_claim,
        "aud" => $audience_claim,
        "iat" => $issuedat_claim,
        "nbf" => $notbefore_claim,
        "exp" => $refresh_expire_claim,
        "data" => array(
            "id" => $user['id'],
            "username" => $username,
            "isAdmin" => $user['isAdmin']
        )
    );

    $jwt = JWT::encode($token, $secret_key, 'HS256');
    $refresh_jwt = JWT::encode($refresh_token, $secret_key, 'HS256');
    setcookie("token", $jwt, $expire_claim, "/", "", true, true);
    setcookie("refresh_token", $refresh_jwt, $refresh_expire_claim, "/", "", true, true);

    http_response_code(200);
    echo json_encode(["message" => "Login successful"]);
}

$stmt->close();
$conn->close();
?>
