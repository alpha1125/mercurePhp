<?php
header('Content-Type: application/json');

require 'vendor/autoload.php';
require 'config.php';

use Firebase\JWT\JWT;


// Create a subscriber JWT for the frontend
$subscriberPayload = [
    "mercure" =>
        [
            "subscribe" => [
                "https://example.com/news",
            ],
        ],
];

if (count($_GET) === 0) {
    // do nothing.
    // This is public access.
}

// this is for testing multiple users.
// JS: fetch("jwt.php?user=1")
// publish.php?user=1


if (isset($_GET['user'])) {
    $subscriberPayload['mercure']['subscribe'][] = "https://example.com/news/user/{$_GET['user']}";
}



$subscriberJwt = JWT::encode($subscriberPayload, SUBSCRIBER_KEY, 'HS256');

// Return the token for the browser to use
echo json_encode(["token" => $subscriberJwt]);

