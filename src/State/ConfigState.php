<?php 

namespace App\State;

class ConfigState {

    private static $config;

    public static function loadConfig(array $config) {
        static::$config = $config;
    }

    public static function getDBConfiguration() : array {

        if(!isset(static::$config["database"]))
            return static::$config["database"];
            
    }
}