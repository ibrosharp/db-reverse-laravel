<?php 

namespace App\Actions;

use App\DataObject\Database;

class CreateSeederAction implements Action {


    private $database;
    
    public function __construct(Database $database) {

        $this->database = $database;

    }

    public function execute(): void
    {
        
    }
}