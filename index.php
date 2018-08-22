<?php

define('DS', DIRECTORY_SEPARATOR);

define('APP_PATH',dirname(__FILE__));

require_once __DIR__ . '/vendor/autoload.php';

$app = new Core\Run();

$app->start();