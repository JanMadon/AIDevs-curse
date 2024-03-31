<?php

require_once __DIR__ . '/vendor/autoload.php';

use app\Tasks\Functions;

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

$functions = new Functions($config);
$functions->run();