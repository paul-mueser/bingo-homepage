<?php
header("Content-Type: application/json");

// Clear the JWT and refresh token cookies
setcookie("token", "", time() - 3600, "/", "", true, true);
setcookie("refresh_token", "", time() - 3600, "/", "", true, true);

echo json_encode(["message" => "Logout successful"]);
?>