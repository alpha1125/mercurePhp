<?php
require 'vendor/autoload.php';
require 'config.php';

use Firebase\JWT\JWT;

if (PHP_SAPI === 'cli') {
    $_GET = [];
    for ($i = 1; $i < $argc; $i++) {
        $param = explode('=', $argv[$i], 2);
        if (count($param) === 2) {
            $_GET[$param[0]] = $param[1];
        }
    }
}

$user = $_GET['user'] ?? -1;

if($user == -1) {
    header('HTTP/1.0 400 Bad Request');

    echo json_encode(["status" => "error", "message" => "User not found"]);
    exit;
}

$jwtPayload = ["mercure" => ["publish" => ["https://example.com/news/user/$user"]]];
$jwt        = JWT::encode($jwtPayload, PUBLISHER_KEY, 'HS256');

$data = [
    "topic" => "https://example.com/news/user/$user",
    "data"  => json_encode(["message" => "Hello from PHP with JWT!", "timestamp" => date("Y-m-d H:i:s")])
];

$options = [
    "http" => [
        "header"  => "Authorization: Bearer $jwt\r\n".
            "Content-Type: application/x-www-form-urlencoded",
        "method"  => "POST",
        "content" => http_build_query($data),
    ],
    "ssl" => [
        "verify_peer" => false,
        "verify_peer_name" => false,
    ]
];

$context  = stream_context_create($options);
$response = file_get_contents(MERCURE_HUB_URL, false, $context);

if ($response === false) {
    echo json_encode(["status" => "error", "message" => "Failed to publish"]);
} else {
    echo json_encode(["status" => "success", "message" => "Event published"]);
}
