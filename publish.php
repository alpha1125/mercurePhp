<?php

require 'vendor/autoload.php';
require 'config.php';

use Firebase\JWT\JWT;

$jwtPayload = ["mercure" => ["publish" => ["https://example.com/news"]]];
$jwt        = JWT::encode($jwtPayload, PUBLISHER_KEY, 'HS256');

$data = [
    "topic" => "https://example.com/news",
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
