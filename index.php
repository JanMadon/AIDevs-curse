<?php

require_once __DIR__ . '/vendor/autoload.php';

use app\Tasks\Functions;
use app\Tasks\Gnome;
use app\Tasks\Knowledge;
use app\Tasks\OwnApiCall;
use app\Tasks\People;
use app\Tasks\Rodo;
use app\Tasks\Scraper;
use app\Tasks\Search;
use app\Tasks\Tools;
use app\Tasks\Whoami;

$config = require_once('conf.php');
//require_once 'Task.php';
//require_once 'Inpromt.php';
//require_once 'Embedding.php';
//require_once 'Whisper.php';
//require_once 'Functions.php';

//$inpromt = new Inpromt($config);
//$inpromt->run();

//$embedding = new Embedding($config);
//$embedding->run();

//$whisper = new Whisper($config);
//$whisper->run();

//$functions = new Functions($config);
//$functions->run();

// $rodo = new Rodo($config);
// $rodo->run();

// $scraper = new Scraper($config);
// $scraper->run();

//$whoami = new Whoami($config);
//$whoami->run();

// $search = new Search($config);
// $search->run();

// $people = new People($config);
// $peolpe->run();

// $knowledge = new Knowledge($config);
// $knowledge->run();

// $tools = new Tools($config);
// $tools->run();

// $gnome = new Gnome($config);
// $gnome->run();

$ownApiCall = new OwnApiCall($config);
$ownApiCall->run();