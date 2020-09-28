<?php
#!/usr/bin/php
system("stty -icanon");

use App\Controllers\CLIController;
use App\State\ConfigState;

require "./vendor/autoload.php";

$config = require_once("./src/config/config.php");

ConfigState::loadConfig($config);

$app = new CLIController();

$app->connect();

$app->run();




