<?php 

namespace App\Actions;

use App\Creators\SeedersCreator;
use App\Exceptions\FailedExecutionException;
use App\State\ConnectionState;

class CreateSeederAction implements Action {

    
    public function __construct(string $tableName) {

        $this->tableName = $tableName;
        

    }

    public function execute(): void
    {
        if(!ConnectionState::isConnected()) throw new FailedExecutionException("Database Not connected");

        $model = ConnectionState::getModel();

        $table = $model->getSingleTable($this->tableName);

        $model->addTableContents($table);

        $creator = new SeedersCreator();

        $creator->setTable($table);

        $creator->createFile();

    }
    
}