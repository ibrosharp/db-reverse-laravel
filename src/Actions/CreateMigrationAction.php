<?php 

namespace App\Actions;

use App\Creators\MigrationsCreator;
use App\Exceptions\FailedExecutionException;
use App\State\ConnectionState;

class CreateMigrationAction implements Action {

    
    public function __construct(?string $tableName) {

        $this->tableName = $tableName;

    }

    public function execute(): void
    {
        if(!ConnectionState::isConnected()) throw new FailedExecutionException("Database Not connected");

        $model = ConnectionState::getModel();

        $creator = new MigrationsCreator();

        if(!$this->tableName) {

            $tables = $model->getTables();

            foreach($tables as $table) {
                
                $model->addColumns($table);
    
                $creator->setTable($table);
        
                $creator->createFile();
            }

         
        }else {

            $table = $model->getSingleTable($this->tableName);
    
            $creator->setTable($table);
    
            $creator->createFile();
    
        }

    }

    
}