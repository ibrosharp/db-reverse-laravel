<?php  

namespace App\Interpreters;

use App\Actions\Action;

interface Interpreter {
 
    public function interprete(string $statement) : Action;
}