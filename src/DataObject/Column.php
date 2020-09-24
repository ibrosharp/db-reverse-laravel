<?php 

namespace App\DataObject;

use App\Lib\EloquentTranslator;

class Column {
    public function __construct( $name, $dataType, $nullable, $key,  $default,  $extra)
    {
        $this->name = $name;
        $this->dataType = $dataType;
        $this->nullable = $nullable;
        $this->default = $default;
        $this->extra = $extra;
        $this->key = $key;
    }


    public function toArray() : array {
        return [
            "name" => $this->name,
            "data_type" => $this->dataType,
            "nullable" => $this->nullable,
            "default" => $this->default,
            "extra" => $this->extra,
            "key" => $this->key
        ];
    }

    public function getName(): string {
        return $this->name;
    }

    public function isPrimary() {
        if(strtolower($this->key) == "pri" ) return true;
        return false;
    }

    public function isTimestamp() {
        if(($this->name == "created_at" || $this->name == "updated_at") && strtolower($this->nullable) == "yes" ) return true;

        return false;
    }

    public function toSchemaString() : string {

        $eloquentDataType = EloquentTranslator::translateDataType($this->dataType,$this->name);
        $eloquentExtas = EloquentTranslator::translateExtras($this->extra);
        $eloquentNullable = EloquentTranslator::translateNullable($this->nullable);
        $eloquentKey = EloquentTranslator::translateKey($this->key);
        $eloquentDefault = EloquentTranslator::translateDefault($this->default);

        if($eloquentDataType == "") {
            return "";
        }

        return "\$table->{$eloquentDataType}{$eloquentKey}{$eloquentNullable}{$eloquentExtas}{$eloquentDefault};";
        
    }

   
}