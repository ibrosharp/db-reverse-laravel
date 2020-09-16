<?php 

namespace App\Actions;

use App\Interactor;

class HelpAction implements Action {

    public function execute() : void {
        Interactor::showHelp();
    }
}