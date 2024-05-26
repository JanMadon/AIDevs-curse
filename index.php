<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/Utils/debug.php'; // to Debug dump();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
