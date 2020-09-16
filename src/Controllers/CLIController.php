<?php 

namespace App\Controllers;

use App\Interactor;
use App\Model;
use App\State\ConnectionState;
use PDOException;

class CLIController extends Controller {

    public function __construct()
    {
        parent::__construct(new Interactor());
    }

    public function connect() {

        $dataBaseInfo = $this->interactor->getDatabaseInfo();

        try {

            $model = Model::getInstance($dataBaseInfo);

            $this->interactor->sendSucceessMessage("Database connected successfully!!!");

            ConnectionState::setConnectionStatus(true);

            ConnectionState::setModel($model);

        }catch(PDOException $e) {

            $this->interactor->sendErrorMessage($e->getMessage());
            
        }
       
        
    }
  
}