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
        
        $config =  static::$config["database"];

        $driver = (isset($config["driver"]))? $config["driver"] : "mysql";
        $host = (isset($config["driver"]))? $config["driver"] : "localhost";
        $username = (isset($config["driver"]))? $config["driver"] : "root";
        $password = (isset($config["driver"]))? $config["driver"] : "";
        $port = (isset($config["driver"]))? $config["driver"] : "80";
        $database = (isset($config["driver"]))? $config["driver"] : "";

        return new DatabaseInfo($host,$port,$username,$password,$database,$driver);
            
    }

    public static function getFileSystemConfiguration() : array {
     
        if(!isset(static::$config["filesystem"])) throw new ConfigurationException("No filesystem Configuration");

        return static::$config["filesystem"];

       
    }
}