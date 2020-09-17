<?php 

namespace App\Actions;

use App\Interactor;
use App\Model;
use App\State\ConfigState;
use App\State\ConnectionState;
use PDOException;

class ConnectDBAction implements Action {
    public function execute() : void {

        $dataBaseInfo = Interactor::getDatabaseInfo();
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
}