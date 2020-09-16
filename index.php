<?php

use App\Controllers\CLIController;
use App\State\ConfigState;

require "./vendor/autoload.php";


$config = require_once("./src/config/config.php");



ConfigState::loadConfig($config);

print_r(ConfigState::getFileSystemConfiguration());

// array_map('unlink', glob($config["output_dir"]."/seeders/*.*"));

// rmdir($config["output_dir"]."/seeders");

// mkdir($config["output_dir"]."/seeders",0777,true);



// file_put_contents(__DIR__."/output/seeders/test.txt","Hello World. Testing!");


$app = new CLIController();

$app->connect();

$app->run();
