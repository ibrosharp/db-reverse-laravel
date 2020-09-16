<?php 

namespace App\Controllers;

use App\Exceptions\FailedExecutionException;
use App\Exceptions\InvalidCommandException;
use App\Interactor;
use App\Interpreters\BaseInterpreter;
use App\Model;
use App\State\ConfigState;
use App\State\ConnectionState;
use Exception;
use PDOException;

class CLIController extends Controller {

    private $interpreter;

    public function __construct()
    {
        $this->interpreter = new BaseInterpreter();
    }

    public function connect() {

        Interactor::sendMessage("Attempting database connection...");
        $dataBaseInfo = ConfigState::getDBConfiguration();

        try {

            $model = Model::getInstance($dataBaseInfo);

            Interactor::sendSucceessMessage("Database connected successfully!!!");

            ConnectionState::setConnectionStatus(true);

            ConnectionState::setModel($model);

            ConnectionState::setDbInfo($dataBaseInfo);

        }catch(PDOException $e) {

            Interactor::sendErrorMessage($e->getMessage());
            
        }
    }
   

    public function run() {
        
        Interactor::sendWelcome();

        do {

            $line = readline("> ");

            try {

                $action = $this->interpreter->interprete($line);
                $action->execute();

            }catch(Exception $e) {

                Interactor::sendErrorMessage($e->getMessage());

            }

         
           
           





        }while(true);

        Interactor::sendSucceessMessage("Bye...");
    }
  
}