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
    case '/liar' : 
        $app->router->get('/liar', [TaskController::class, 'liar']);
        break;
    case '/inpromt' : 
        $app->router->get('/inpromt', [TaskController::class, 'inpromt']);
        break;
    case '/embedding' : 
        $app->router->get('/embedding', [TaskController::class, 'embedding']);
        break;
    case '/whisper' : 
        $app->router->get('/whisper', [TaskController::class, 'whisper']);
        break;
    case '/functions' : 
        $app->router->get('/functions', [TaskController::class, 'functions']);
        break;
    case '/rodo' : 
        $app->router->get('/rodo', [TaskController::class, 'rodo']);
        break;
    case '/scraper' : 
        $app->router->get('/scraper', [TaskController::class, 'scraper']);
        break;
    case '/whoami' : 
        $app->router->get('/whoami', [TaskController::class, 'whoami']);
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
