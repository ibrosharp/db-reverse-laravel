<?php  

namespace App\State;

use App\DatabaseInfo;
use App\Model;

final class ConnectionState {

    private static $status = false;
    /** @var Model */
    private static $model;
    /** @var DatabaseInfo */
    private static $dbInfo;
 

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

    public static function setDbInfo(DatabaseInfo $info) : void {
        static::$dbInfo = $info;
    }

    public static function getDbInfo() : DatabaseInfo {
        return static::$dbInfo;
    }

    public static function destruct() : void {
        static::$dbInfo = null;
        static::$model = null;
        static::$status = false;
    }


}