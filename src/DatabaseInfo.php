<?php 

namespace App;

class DatabaseInfo {

    public function __construct(string $host,string $port, string $username, string $password, string $dbName, string $driver)
    {
        $this->driver = $driver;
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->dbName = $dbName;
    }


    public function host() : string {
        return $this->host;
    }

    public function port() : string {
        return $this->port;
    }

    public function username() : string {
        return $this->username;
    }

    public function password() : string {
        return $this->password;
    }

    public function dbName() : string  {
        return $this->dbName;
    }

    public function driver() : string {
        return $this->driver;
    }
}
