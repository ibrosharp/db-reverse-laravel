<?php  

namespace App\State;

use App\Model;

final class ConnectionState {

    private static $status = false;
    private static $model;
 

    public static function isConnected() : bool {
        return static::$status;
    }

    public static function setConnectionStatus(bool $status) {
        static::$status = $status;
    }

    public static function setModel(Model $model) {
        static::$model = $model;
    }

    public static function getModel() :  Model {
        return static::$model;
    }


}