<?php

use app\controllers\HomeController;
use app\controllers\TaskController;
use app\core\Application;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/Utils/debug.php'; // to Debug dd();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = new Application($_ENV);

//print_r($app->request->getBody('selectedTask'));


switch($app->request->url()) {
    case '/' : 
        $app->router->get('/', [HomeController::class, 'index']);
        break;
    case '/helloapi' : 
        $app->router->get('/helloapi', [TaskController::class, 'helloapi']);
        break;
    case '/moderation' : 
        $app->router->get('/moderation', [TaskController::class, 'moderation']);
        break;
    case '/blogger' : 
        $app->router->get('/blogger', [TaskController::class, 'blogger']);
        break;
}

// $app->router->get('/helloapi', [TaskController::class, 'helloapi']);
// $app->router->get('/moderation', [TaskController::class, 'moderation']);
// $app->router->get('/blogger', [TaskController::class, 'blogger']);

// $app->router->get('/', 'home');
// $app->router->get('/', 'home');
// $app->router->get('/', 'home');
// $app->router->get('/', 'home');
// $app->router->get('/', 'home');


//$app->router->get('/', 'home');
