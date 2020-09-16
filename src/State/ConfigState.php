<?php 

namespace App\State;

use App\DatabaseInfo;
use App\Exceptions\ConfigurationException;

class ConfigState {

    private static $config;

    public static function loadConfig(array $config) {
     
        static::$config = $config;
    }

    public static function getDBConfiguration() : DatabaseInfo {

        if(!isset(static::$config["database"])) throw new ConfigurationException("No database configuration Configuration");
        
        return static::$config["database"];

        return new DatabaseInfo();
            
    }

    public static function getFileSystemConfiguration() : array {
     
        if(!isset(static::$config["filesystem"])) throw new ConfigurationException("No filesystem Configuration");

        return static::$config["filesystem"];

       
    }
}