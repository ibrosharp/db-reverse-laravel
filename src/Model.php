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

    public function getColumns(Table $table) : SplObjectStorage {

        $query = $this->connection->prepare("SHOW COLUMNS FROM {$table->getName()}");

        $query->execute();

        $data = $query->fetchAll(PDO::FETCH_ASSOC);

        $columns = new SplObjectStorage;

        foreach($data as $column) {

           
            $columns->attach(new Column($column["Field"],$column["Type"],$column["Null"],$column["Key"],$column["Default"],$column["Extra"]));

        }

        return $columns;
    }

    public function __destruct()
    {
        $this->connection = NULL;
    }


}