<?php  

namespace App\DataObject;

use SplObjectStorage;

class Database {

    private $tables;
    private $name;

    public function __construct(string $dbname)
    {
        $this->name = $dbname;
    }


    public function getName() : string {
        return $this->name;
    }

    public function setTables(SplObjectStorage $tables) {
        $this->tables = $tables; 
    }

    public function getTables() : SplObjectStorage {
        return $this->tables;
    }

}