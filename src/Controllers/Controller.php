<?php 

namespace App\Controllers;

use App\Interactor;


abstract class Controller {

    protected $interactor;

    public function __construct(Interactor $interactor)
    {
        $this->interactor = $interactor;
    }

    abstract public function connect();

  
}