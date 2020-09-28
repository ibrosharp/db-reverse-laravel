<?php 

namespace App\Controllers;


use App\Interactor;
use App\Interpreters\BaseInterpreter;
use App\Model;
use App\State\ConfigState;
use App\State\ConnectionState;
use Exception;
use PDOException;
use SplDoublyLinkedList;


class CLIController extends Controller {

    private $interpreter;
    /** @var SplDoublyLinkedList */
    private $commandStack;

    public function __construct()
    {
        $this->interpreter = new BaseInterpreter();
        $this->commandStack = new SplDoublyLinkedList();
    }

    public function connect() : void {

        Interactor::sendMessage("Attempting database connection...");
        $dataBaseInfo = ConfigState::getDBConfiguration();
        ConnectionState::setDbInfo($dataBaseInfo);

        try {

            $model = Model::getInstance($dataBaseInfo);

            Interactor::sendSucceessMessage("Database connected successfully!!!");

            ConnectionState::setConnectionStatus(true);

            ConnectionState::setModel($model);

           

        }catch(PDOException $e) {

            Interactor::sendErrorMessage($e->getMessage());
            
        }
    }
   

    public function run() : void{

        echo "Laravel_DB_Helper -> ";
        
        Interactor::sendWelcome();

        do {
          
          

            $line = "";

            while ($c = fread(STDIN, 1)) {

             
            
                if($c  == PHP_EOL) {
                    $this->commandStack->unshift($line);
                    $this->commandStack->rewind();
                    if(strlen($line) > 0) break;
                }

                if(ord($c) == 65) {
                    $line = "";
                    if(!$this->commandStack->isEmpty()) {
                        $line = $this->commandStack->current();
                        $this->commandStack->next();
                       
                    }
                   
                }elseif(ord($c) == 66 ){
                    
                }elseif(ord($c) == 127) {
                    $line = substr($line,0,-1);
                  
                }else {
                    $line .= $c;
                }

                
                
                echo "\r\033[K";
                echo "Laravel_DB_Helper -> ".$line;
             
            }

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