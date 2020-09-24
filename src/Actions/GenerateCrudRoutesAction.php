<?php 

namespace App\Actions;

use App\Creators\SeedersCreator;
use App\Exceptions\FailedExecutionException;
use App\State\ConnectionState;

class GenerateCrudRoutesAction implements Action {

    
    public function __construct(?string $tableName) {

        $this->tableName = $tableName;

    }

    public function execute(): void
    {
       
    }

    
}