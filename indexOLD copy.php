<?php

require_once __DIR__ . '/vendor/autoload.php';

use app\Prompts\GPTprompt;
use app\Tasks\Functions;
use app\Tasks\Gnome;
use app\Tasks\Google;
use app\Tasks\Knowledge;
use app\Tasks\Md2html;
use app\Tasks\Meme;
use app\Tasks\Optimaldb;
use app\Tasks\OwnApiCall;
use app\Tasks\OwnApiProCall;
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

// $whoami = new Whoami($config);
// $whoami->run();

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

// $ownApiCall = new OwnApiCall($config);
// $ownApiCall->run();

// $ownApiProCall = new OwnApiProCall($config);
// $ownApiProCall->run();

// $meme = new Meme($config);
// $meme->run();

// $optimaldb = new Optimaldb($config);
// $optimaldb->run();

// // c05l03-pamiec-dlugoterminowa -> https://serpapi.com/ //logowanie przez githab
// $google = new Google($config);
// $google->run();

// $md2html = new Md2html($config);
// $md2html->run();

// $file = file_get_contents('aidevs_dane_ft1.jsonl');
// print_r(json_decode($file, true));