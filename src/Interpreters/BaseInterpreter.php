<?php 

namespace App\Interpreters;

use App\Actions\Action;
use App\Actions\ConnectDBAction;
use App\Actions\HelpAction;
use App\Exceptions\InvalidCommandException;
use Throwable;

class BaseInterpreter implements Interpreter {

    

    public function __construct()
    {
        
    }


    public function interprete(string $statement): Action
    {
        $commands  = explode(" ",$statement);
        try {
            switch(count($commands)) {
                case 1: 
                    $execute = $commands[0];
                    
                    return $this->$execute(); 
                  
                break;
                case 2: 
                    $execute = $commands[0];
                    return $this->$execute($commands[1]);
                break;
                case 3:
                    $execute = $commands[0];
                    return $this->$execute($commands[1],$commands[2]);
                break;
                default: 
                    throw new InvalidCommandException();
                break;
            }

        }catch(Throwable $e) {
            throw new InvalidCommandException("The statement '{$statement}' is not recognized ");
        }


         

        
        

      
    }

    private function help() : Action {
      
        return new HelpAction();
    }

    private function connect() : Action {
        return new ConnectDBAction();
    }


}