<?php

use app\core\Application;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/Utils/debug.php'; // to Debug dd();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

dd($_SERVER['REQUEST_METHOD'] === "GET");


$app = new Application($_ENV);

$app->router->get('/', 'home');
