<?php

use App\Controllers\CLIController;


require "./vendor/autoload.php";


$config = require_once("./src/config/database.php");

print_r($config);


// $controller = new CLIController();

// $controller->connect();
