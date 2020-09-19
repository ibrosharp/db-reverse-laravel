<?php 

namespace App\Lib;

use App\Exceptions\EloquentConversionException;

class EloquentTranslator {

    public static function translateDataType(string $dataType,string $field) : string {

        $val = explode("(",$dataType);

        $migrationString = "";
        switch( strtolower($val[0]) ) {
            case "bigint": 
                $size = explode(")",$val[1]);
                $migrationString = "bigInteger('{$field}',{$size[0]})";
            break;

            case "blob": 
                $migrationString = "binary('{$field}')";
            break;

            case "boolean": 
                $migrationString = "boolean('{$field}')";
            break;

            case "char": 
                $size = explode(")",$val[1]);
                $migrationString = "char('{$field}',{$size[0]})";
            break;


            case "date": 
                $migrationString = "data('{$field}')";
            break;

            case "datetime": 
                $migrationString = "dataTime('{$field}')";
            break;
            
            case "decimal":
                $size = explode(")",$val[1]);
                $migrationString = "decimal('{$field}',{$size[0]})";
            break;
            
            case "double": 
                $size = explode(")",$val[1]);
                $migrationString = "double('{$field}',{$size[0]})";
            break;


            case "enum":
                $size = explode(")",$val[1]);
                $migrationString = "enum('{$field}',{$size[0]})";
            break;

            case "float":
                $size = explode(")",$val[1]);
                $migrationString = "float('{$field}',{$size[0]})";
            break;

            case "geometry":
                $migrationString = "geometry('{$field}')";
            break;
            case "geometrycollection": 
                $migrationString = "geometryCollection('{$field}')";
            break;
            
            case "int":
                $size = explode(")",$val[1]);
                $migrationString = "integer('{$field}',{$size[0]})";
            break;

            case "ipaddress": 
                $migrationString = "ipAddress('{$field}')";
            break;
            
            case "json": 
                $migrationString = "json('{$field}')";
            break;

            case "jsonb": 
                $migrationString = "jsonb('{$field}')";
            break;

            case "linestring": 
                $migrationString = "lineString('{$field}')";
            break;
            
            case "longtext": 
                $migrationString = "longText('{$field}')";
            break;

            case "macaddress": 
            case "mac address": 
                $migrationString = "macAddress('{$field}')";
            break;

            case "mediumint": 
                $size = explode(")",$val[1]);
                $migrationString = "mediumInteger('{$field}',{$size[0]})";
            break;

            case "mediumtext": 
                $migrationString = "mediumText('{$field}')";
            break;

            case "multilinestring": 
                $migrationString = "multiLineString('{$field}')";
            break;

            case "multipoint": 
                $migrationString = "multiPoint('{$field}')";
            break;

            case "multipolygon": 
                $migrationString = "multiPolygon('{$field}')";
            break;

            case "point": 
                $migrationString = "point('{$field}')";
            break;

            case "polygon": 
                $migrationString = "polygon('{$field}')";
            break;

            case "set": 
                $size = explode(")",$val[1]);
                $set = "'" . implode("','", $size) . "'";
                $migrationString = "set('{$field}',[{$set}])";
            break;

            case "smallint": 
                $size = explode(")",$val[1]);
                $migrationString = "smallInteger('{$field}',{$size[0]})";
            break;

            case "varchar": 
                $size = explode(")",$val[1]);
                $migrationString = "string('{$field}',{$size[0]})";
            break;


            case "text": 
                $migrationString = "text('{$field}')";
            break;

            case "time": 
                $migrationString = "time('{$field}')";
            break;

            case "timestamp": 
                $migrationString = "timestamp('{$field}')";
            break;

            case "tinyint": 
                $size = explode(")",$val[1]);
                $migrationString = "tinyInteger('{$field}',{$size[0]})";
            break;

            case "tinyint": 
                $size = explode(")",$val[1]);
                $migrationString = "tinyInteger('{$field}',{$size[0]})";
            break;

            case "year": 
                $migrationString = "year('{$field}')";
            break;

            default: 
                throw new EloquentConversionException("Unknown data type {$dataType}");
            break;
            
        }

        if (strpos($dataType, "unsigned") !== false) {
            $migrationString .= "->unsigned()";
        }

        return $migrationString;

    
    }

    public static function translateExtras(string $extras) : string {

        $eloquentString = "";
        switch(strtolower($extras)) {
            case "": 
            break;
            case "auto_increment": 
                $eloquentString .= "->autoIncrement()";
            break;      

            default: 
                throw new EloquentConversionException("Unknown extra field {$extras}");
            break;
        }

        return $eloquentString;
    }

    public static function translateNullable(?string $nullable) : string {

        if(strtolower($nullable) == "yes") return "->nullable();";

        return "";

    }

    public static function translateKey(string $key) : string {

        $eloquentString = "";

        switch(strtolower($key)) {
            case "pri": 

            break;
            case "uni": 
                $eloquentString .= "->unique()";
            break;

            case "mul": 
            break;

            case "": 
            break;

            default: 
                throw new EloquentConversionException("Unkwown key {$key}");
            break;
        }

        return $eloquentString;
        
    }
}