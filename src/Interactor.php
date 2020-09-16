<?php 

namespace App;


class Interactor {


    public static function sendSucceessMessage(string $message) : void {

        echo PHP_EOL;

        static::changeTextColor("Green");

        echo $message;

        echo PHP_EOL;

        static::changeTextColor("Default");
        
    }

    public static function sendErrorMessage(string $message) : void {

        echo PHP_EOL;

        static::changeTextColor("Red");

        echo $message;

        echo PHP_EOL;

        static::changeTextColor("Default");
        
    }

    public static function getDatabaseInfo() : DatabaseInfo {

        $dbDriver = static::getDBDriver();
       
        $hostName = static::getHostName();

        $port = static::getPortNumber();
       
        $dbName = static::getDatabaseName();

        $username = static::getUserName();

        $password = static::getPassword();

        return new DatabaseInfo($hostName,$port,$username,$password,$dbName,$dbDriver);
            
      
    }

    private static function changeBackgroundColor(string $color) {

    }

    private static function changeTextColor(string $color) {

        $colors = [
           "Default" => "\e[39m",
           "Black" => "\e[30m",
           "Red" => "\e[31m",
           "Green" => "\e[32m",
           "Yellow" => "\e[33m",
           "Blue" => "\e[34m",
           "Magenta" => "\e[35m",
           "Cyan" => "\e[36m",
           "Light gray" => "\e[37m",
           "Dark gray" => "\e[90m",
           "Light red" => "\e[91m",
           "Light green" => "\e[92m",
           "Light yellow" => "\e[93m",
           "Light blue" => "\e[94m",
           "Light magenta" => "\e[95m",
           "Light cyan" => "\e[96m",
           "White" => "\e[97m",
        ];
        if(isset($colors[$color])) echo $colors[$color];

    }

    private static function  getDatabaseName() : string {

        static::changeTextColor("Light gray");

        while(true) {
            echo PHP_EOL;
            $line = readline("Enter database name : ");

            if($line) break;
        }

        static::changeTextColor("Default");

        return $line;
    }

    private static function getPortNumber() : string  {

        static::changeTextColor("Yellow");

        while(true) {

            echo PHP_EOL;
            
            $line = readline("Enter Port (Default: 80): ");

            if($line > 0 || $line < 65535) break;
            
            echo "Invalid port must be within 0 - 65535" . PHP_EOL;

        }

        static::changeTextColor("Default");

        return $line ?: "80";
    }

    private static function getHostName() : string  {

        static::changeTextColor("Blue");

        echo PHP_EOL;
        $line = readline("Enter Host name (Default: localhost): ");

        static::changeTextColor("Default");

        return $line ?: "localhost";

    }

    private static function getDBDriver() : string {
        static::changeTextColor("Red");
        echo PHP_EOL;
        
        do {
            $continue = false;
            echo  "Choose a database driver".PHP_EOL.
                    "\t1. Mysql" . PHP_EOL;


            echo PHP_EOL;
            $line = readline("Option : ");

            switch($line) {
                case "1":
                    $dbDriver = "mysql";
                    
                break;

                default: 
                    echo "Invalid option" . PHP_EOL;
                    $continue = true;
                break;
            }

        }while($continue);

        static::changeTextColor("Default");


        return $dbDriver;
    }

    private static function getUserName() : string {

        echo PHP_EOL;

        static::changeTextColor("White");
        
        $line = readline("Enter database username (Default: root): ");

        static::changeTextColor("Default");

        return $line ?: "root";

      


    }

    private static function getPassword() : string {

        echo PHP_EOL;

        static::changeTextColor("Green");

        $line = readline("Enter database password (Default: ): ");

        static::changeTextColor("Default");

        return $line ?: $line;

     

    }

    public static function sendWelcome() {

        static::changeTextColor("Blue");
        echo PHP_EOL;
        echo    "*********************************************************************************************" .PHP_EOL;
        echo    '*  ____  ____     ____                                  _                                _  *'. PHP_EOL. 
                '* |  _ \| __ )   |  _ \ _____   _____ _ __ ___  ___    | |    __ _ _ __ __ _ __    _____| | *'. PHP_EOL.
                "* | | | |  _ \   | |_) / _ \ \ / / _ \ '__/ __|/ _ \   | |   / _` | '__/ _` |\ \  / / _ \ | *" .PHP_EOL.
                '* | |_| | |_) |  |  _ <  __/\ V /  __/ |  \__ \  __/   | |__| (_| | | | (_| | \ \/ /  __/ | *' .PHP_EOL.
                '* |____/|____/___|_| \_\___| \_/ \___|_|  |___/\___|___|_____\__,_|_|  \__,_|  \__/ \___|_| *' .PHP_EOL.
                '*          |_____|                               |_____|                                    *' .PHP_EOL.
                '*                                                                                           *' .PHP_EOL;
        echo    "*********************************************************************************************" .PHP_EOL;

        
        echo"Author :  Ibrahim Abdulsamad" .PHP_EOL . 
            "Email  :  abdulsamadibrahim210@gmail.com" . PHP_EOL .
            "GitHub :  https://github.com/ib-Jkid" . PHP_EOL;

        echo PHP_EOL;

        echo "Generate migrations, models, seeders for Database" . PHP_EOL;

        static::changeTextColor("Default");

        echo "Type Help to display available commands" . PHP_EOL;
    }

    public static function showHelp() {
        echo "showing help";
                                                                                      
        
    }

    public static function sendMessage(string $message) : void {
        
        echo PHP_EOL;

        static::changeTextColor("Green");

        echo $message;

        echo PHP_EOL;

        static::changeTextColor("Default");
    
    }
}