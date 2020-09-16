<?php 

namespace App;


class Interactor {

 
    public function __construct()
    {
        
    }

    public function sendSucceessMessage(string $message) : void {

        echo PHP_EOL;

        $this->changeTextColor("Green");

        echo $message;

        echo PHP_EOL;
        
    }

    public function sendErrorMessage(string $message) : void {

        echo PHP_EOL;

        $this->changeTextColor("Red");

        echo $message;

        echo PHP_EOL;
        
    }

    public function getDatabaseInfo() : DatabaseInfo {

        $dbDriver = $this->getDBDriver();
       
        $hostName = $this->getHostName();

        $port = $this->getPortNumber();
       
        $dbName = $this->getDatabaseName();

        $username = $this->getUserName();

        $password = $this->getPassword();

        return new DatabaseInfo($hostName,$port,$username,$password,$dbName,$dbDriver);
            
      
    }

    private function changeBackgroundColor(string $color) {

    }

    private function changeTextColor(string $color) {

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

    private function  getDatabaseName() : string {

        $this->changeTextColor("Light gray");

        while(true) {
            echo PHP_EOL;
            $line = readline("Enter database name : ");

            if($line) break;
        }

        return $line;
    }

    private function getPortNumber() : string  {

        $this->changeTextColor("Yellow");

        while(true) {

            echo PHP_EOL;
            
            $line = readline("Enter Port (Default: 80): ");

            if($line > 0 || $line < 65535) break;
            
            echo "Invalid port must be within 0 - 65535" . PHP_EOL;

        }

        return $line ?: "80";
    }

    private function getHostName() : string  {

        $this->changeTextColor("Blue");

        echo PHP_EOL;
        $line = readline("Enter Host name (Default: localhost): ");

        return $line ?: "localhost";

    }

    private function getDBDriver() : string {
        $this->changeTextColor("Red");
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

        return $dbDriver;
    }

    private function getUserName() : string {

        echo PHP_EOL;

        $this->changeTextColor("White");
        
        $line = readline("Enter database username (Default: root): ");

        return $line ?: "root";

    }

    private function getPassword() : string {

        echo PHP_EOL;

        $this->changeTextColor("Green");

        $line = readline("Enter database password (Default: ): ");

        return $line ?: $line;

    }
}