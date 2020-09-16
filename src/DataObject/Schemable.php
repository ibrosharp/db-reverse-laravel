<?php 

namespace App\DataObject;

interface Schemable {
    public function getSchema() : string;
}