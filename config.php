<?php
require 'vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

// Load .env file
$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

define("MERCURE_HUB_URL", "https://localhost:3443/.well-known/mercure");
define("PUBLISHER_KEY", $_ENV['MERCURE_PUBLISHER_JWT_KEY'] ?? '!ChangeThisMercureHubJWTSecretKey!');
define("SUBSCRIBER_KEY", $_ENV['MERCURE_SUBSCRIBER_JWT_KEY'] ?? '!ChangeThisMercureHubJWTSecretKey!');
