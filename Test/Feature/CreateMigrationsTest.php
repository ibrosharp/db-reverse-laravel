<?php 

namespace Test\Unit;

use App\DatabaseInfo;
use App\Interpreters\BaseInterpreter;
use App\Model;
use App\State\ConfigState;
use App\State\ConnectionState;
use PHPUnit\Framework\TestCase;

class CreateMigrationsTest extends TestCase {


   
    public function testCreateMigrationFeature() {


        ConfigState::loadConfig( [
    
            "database" => [
                "driver" => "mysql",
                "host" => "localhost",
                "username" => "street",
                "password" => "qwertyuiop", 
                "port" => "80",
                "database" => "lumen"
            ],
            "filesystem" => [
                "output_dir" => __DIR__."/../../output",
            ]
           
        
        ]);

        /** @var DatabaseInfo */
        $dbInfo = ConfigState::getDBConfiguration();

        $this->assertInstanceOf(DatabaseInfo::class,$dbInfo);

        $this->assertNotNull($dbInfo->dbName());

        $this->assertNotNull($dbInfo->host());

        $this->assertNotNull($dbInfo->port());

        $this->assertNotNull($dbInfo->password());

        $this->assertNotNull($dbInfo->driver());

        $this->assertNotNull($dbInfo->username());

        $model = Model::getInstance($dbInfo);

        $this->assertInstanceOf(Model::class,$model);

        return $model;

        ConnectionState::setDbInfo($dbInfo);

        ConnectionState::setModel($model);

        ConnectionState::setConnectionStatus(true);

        $interpreter  = new BaseInterpreter();

        $action = $interpreter->interprete("create migrations");

        $action->execute();
    }


}