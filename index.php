<?php

use app\controllers\HomeController;
use app\core\Application;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/Utils/debug.php'; // to Debug dd();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    print_r($_POST);
};


$app = new Application($_ENV);

//print_r($app->request->getBody('selectedTask'));

$app->router->get('/', [HomeController::class, 'index']);
$app->router->get('/', [HomeController::class, 'index']);
// $app->router->get('/', 'home');
// $app->router->get('/', 'home');
// $app->router->get('/', 'home');
// $app->router->get('/', 'home');
// $app->router->get('/', 'home');
// $app->router->get('/', 'home');


//$app->router->get('/', 'home');
