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

            $constraintTables = array();

            foreach($tables as $table) {
                
                $model->addColumns($table);

                $model->addTableContraints($table);

                $creator->setTable($table);

                if(count($table->getContraints()) > 0) {
                    array_push($constraintTables,$table);
                } 
        
                $creator->createFile();
            }

            foreach($constraintTables as $table) {
                $creator->createContraints($table);
            }

         
        }else {

            $table = $model->getSingleTable($this->tableName);

            $model->addTableContraints($table);
    
            $creator->setTable($table);
    
            $creator->createFile();

            $creator->createContraints($table);
    
        }

    }

    
}