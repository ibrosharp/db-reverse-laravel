<?php 

namespace App\Lib;

use App\DataObject\Column;
use App\Exceptions\EloquentConversionException;

class ValidationTranslator {

    private static $column;
    public static function getValidationString(Column $column) : string {
        static::$column = $column;

        $val = explode("(",static::$column->getType());

        $required = static::required();
        $type = static::dataType($val);
        $max  = static::maxValue($val);

        //Start
        $validationString = $required? $required."|": "";

        $validationString .= $type? $type."|": "";

        ///End
        $validationString .= $max? $max: "";

        return $validationString;
    }

    private static function required() : ?string {
        if(static::$column->isNullable()) return null;

        return "required";
    }

    private static function dataType($dataType) :  ?string {
        
        switch( strtolower($dataType[0]) ) {
            case "bigint":
            case "float":
            case "int":
            case "mediumint": 
            case "smallint": 
            case "tinyint": 
               return "integer";
            break;
            case "boolean": 
                return "boolean";
            break;
            case "char":
            case "varchar":
            case "text": 
                return "string";
            break;
            default:
                return null;
            break;
        }
    }

    private static function maxValue($dataType) : ?string {

       

        switch( strtolower($dataType[0]) ) {
            case "bigint":
            case "float":
            case "int":
            case "mediumint": 
            case "smallint": 
            case "tinyint": 
            case "char":
            case "varchar":
                $size = explode(")",$dataType[1]);
                return "max:{$size[0]}";
            break;
            default: 
                return null;
            break;
        }
    }
}