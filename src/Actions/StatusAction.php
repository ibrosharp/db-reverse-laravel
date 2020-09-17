<?php 

namespace App\Actions;

use App\Interactor;
use App\State\ConfigState;
use App\State\ConnectionState;
use Iterator;

class StatusAction implements Action {
    public function execute() : void {

        
        Interactor::showStatus(ConnectionState::isConnected(),ConnectionState::getDbInfo());
        
    }
}