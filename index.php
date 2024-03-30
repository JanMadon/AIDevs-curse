<?php

$config = require_once('conf.php');
//require_once 'Task.php';
require_once 'Inpromt.php';
require_once 'Embedding.php';
require_once 'Whisper.php';



//$inpromt = new Inpromt($config);
//$inpromt->run();

//$embedding = new Embedding($config);
//$embedding->run();

$whisper = new Whisper($config);
$whisper->run();