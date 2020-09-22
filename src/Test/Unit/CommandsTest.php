<?php 

namespace App\Test;

use App\Actions\CloseAction;
use App\Actions\ConnectDBAction;
use App\Actions\CreateMigrationAction;
use App\Actions\CreateModelAction;
use App\Actions\CreateSeederAction;
use App\Actions\HelpAction;
use App\Actions\StatusAction;
use App\Interpreters\BaseInterpreter;
use PHPUnit\Framework\TestCase;

class CommandsTest extends TestCase {

    

    public function testInterpreter() {

        $interpreter = new BaseInterpreter();

        $this->assertInstanceOf(BaseInterpreter::class,$interpreter);

        return $interpreter;
    }

    /**
     * @depends testInterpreter
     */
    public function testCreateMigrationsCommand(BaseInterpreter $interpreter) {
        
        $action = $interpreter->interprete("create migrations");

        $this->assertInstanceOf(CreateMigrationAction::class,$action);
    }
    
    /**
     * @depends testInterpreter
     */
    public function testCreateModelsCommand(BaseInterpreter $interpreter) {

        $action = $interpreter->interprete("create models");

        $this->assertInstanceOf(CreateModelAction::class,$action);

    }


    /**
     * @depends testInterpreter
     */
    public function testCreateSeedersCommand(BaseInterpreter $interpreter) {

        $action = $interpreter->interprete("create seeders");

        $this->assertInstanceOf(CreateSeederAction::class,$action);

    }
    

    /**
     * @depends testInterpreter
     */
    public function testHelpCommand(BaseInterpreter $interpreter) {

        $action = $interpreter->interprete("help");

        $this->assertInstanceOf(HelpAction::class,$action);

    }

    /**
     * @depends testInterpreter
     */
    public function testStatusCommand(BaseInterpreter $interpreter) {

        $action = $interpreter->interprete("status");

        $this->assertInstanceOf(StatusAction::class,$action);

    }

     /**
     * @depends testInterpreter
     */
    public function testExitCommand(BaseInterpreter $interpreter) {

        $action = $interpreter->interprete("exit");

        $this->assertInstanceOf(CloseAction::class,$action);

    }

      /**
     * @depends testInterpreter
     */
    public function testConnectCommand(BaseInterpreter $interpreter) {

        $action = $interpreter->interprete("connect");

        $this->assertInstanceOf(ConnectDBAction::class,$action);

    }



}