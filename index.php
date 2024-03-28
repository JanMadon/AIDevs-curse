<?php

$config = require_once('conf.php');
//require_once 'Task.php';
require_once 'Inpromt.php';



$inpromt = new Inpromt($config);
$inpromt->run();

