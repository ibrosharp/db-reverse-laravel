<?php 

namespace App;

use App\DataObject\Table;
use App\DataObject\Column;
use App\Exceptions\DatabaseException;
use PDO;
use PDOException;
use SplObjectStorage;

class Model {

    private $connection;

    private function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }


    public static function getInstance(DatabaseInfo $info) : Model
    {
        try {
            $connection = new PDO("{$info->driver()}:host={$info->host()};dbname={$info->dbName()};port={$info->port()}", $info->username(), $info->password(), array(PDO::ATTR_PERSISTENT => true));
            return new Model($connection);
        }catch(PDOException $e) {
            throw $e;
        }
        
    }

    public function getTables() : SplObjectStorage {

        $query = $this->connection->prepare("SHOW TABLES");

        $query->execute();

        $data = $query->fetchAll(PDO::FETCH_NUM);

        $tables = new SplObjectStorage;

        foreach($data as $table) {
           
            $tables->attach(new Table($table[0]));
        }

        return $tables;
    }

    public function addTableContraints(Table $table) {
        
        $query = $this->connection->prepare("show create table {$table->getName()}");

        $query->execute();

        $data = $query->fetchAll(PDO::FETCH_NUM);


       // print_r($data);

        $dataArray = explode(",", $data[0][1]);

        $constrains = array();

        foreach($dataArray as $line) {

            if( preg_match("/CONSTRAINT/",$line) ) {

                preg_match_all("/`.*?`/",$line,$array);

                array_push($constrains,[
                    "constraints" => trim($array[0][0],"`"),
                    "foreign_key" => trim($array[0][1],"`"),
                    "external_table" => trim($array[0][2],"`"),
                    "external_key" => trim($array[0][3],"`")
                ]);
            }
        }
       
    
       $table->addContraints($constrains);

    }
    public function getSingleTable(string $tableName) : Table {

        $query = $this->connection->prepare("EXPLAIN {$tableName}");

        $query->execute();

        $data = $query->fetchAll(PDO::FETCH_ASSOC);

        if(count($data) < 1) throw new DatabaseException("Invalid table name: {$tableName}");

        $columns = new SplObjectStorage;

        foreach($data as $column) {
           
            $columns->attach(new Column($column["Field"],$column["Type"],$column["Null"],$column["Key"],$column["Default"],$column["Extra"]));

        }

        $table = new Table($tableName);

        $table->setColumns($columns);

        return $table;

        
    }

    public function addTableContents(Table $table) : void {
        $query = $this->connection->prepare("SELECT * FROM {$table->getName()}");

        $query->execute();

        $data = $query->fetchAll(PDO::FETCH_ASSOC);

        $table->addContents($data);

    }

    public function addColumns(Table $table) : void {

        $query = $this->connection->prepare("SHOW COLUMNS FROM {$table->getName()}");

        $query->execute();

        $data = $query->fetchAll(PDO::FETCH_ASSOC);

        $columns = new SplObjectStorage;

        foreach($data as $column) {

           
            $columns->attach(new Column($column["Field"],$column["Type"],$column["Null"],$column["Key"],$column["Default"],$column["Extra"]));

        }

        $table->setColumns($columns);

    }

    public function __destruct()
    {
        $this->connection = NULL;
    }


}