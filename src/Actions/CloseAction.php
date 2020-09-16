<?php 

namespace App\Actions;

use App\Interactor;
use App\State\ConfigState;
use App\State\ConnectionState;
use Iterator;

class CloseAction implements Action {
    public function execute() : void {
        Interactor::sendMessage("Bye.....");
        sleep(2);

        ConnectionState::destruct();
        
        die();  
    }
}