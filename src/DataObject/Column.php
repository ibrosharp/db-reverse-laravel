<?php 

namespace App\DataObject;

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

    public function toSchemaString() : string {
        return "";
        //"$table->integer('id')->nullable()->default()->autoIncrement()"
    }
}