<?php 

namespace App\Creators;

use App\DataObject\Table;
use App\Exceptions\FileSystemException;
use App\Interactor;
use App\State\ConfigState;
use App\State\ConnectionState;

class MigrationsCreator implements FileCreator {

    private $table;
    private $className;
    private $fileName;
    private $path;
    public function __construct() 
    {
        $this->path = ConfigState::getFileSystemConfiguration()["output_dir"]."/migrations";

                
        if(file_exists($this->path)) {

            array_map('unlink', glob($this->path."/*.*"));

            rmdir($this->path);
        }
      
        mkdir($this->path,0777,true);
    }

    public function setTable(Table $table) : void {
        
        $this->table = $table;

        $this->className = "Create".str_replace(" ", "", ucwords(str_replace("_", " ", $table->getName())))."Table";

        $time = time();

        $date = date("Y_m_d", $time);

        $time = $time % 10000;

        $this->fileName = "{$date}_{$time}_create_{$table->getName()}_table.php";

    }

    public function createFile() : void {   

        if(!$this->table) throw new FileSystemException("No table found");

        Interactor::sendMessage("Creating : {$this->fileName}");

        $this->writeToFile($this->wrapFrame());

        Interactor::sendSucceessMessage("Created :{$this->fileName}");

    }

  
    private function getTopContent() : string {

        $content = "";

        foreach($this->table->getColums() as $column) {
            $content .= "\t\t\t{$column->toSchemaString()}\n";
        }

        return $content;
    }

    private function wrapFrame() : string {

        $properties = "".
        "<?php\n\n".

        "use Illuminate\Database\Migrations\Migration;\n".
        "use Illuminate\Database\Schema\Blueprint;\n".
        "use Illuminate\Support\Facades\Schema;\n\n".

        "class ".$this->className." extends Migration\n{\n".

            "\tpublic function up()\n".
            "\t{\n".
                "\t\tSchema::create('{$this->table->getName()}', function (Blueprint \$table) {\n".
                   $this->getTopContent().
                "\t\t});\n".
            "\t}\n\n".

            "\t/**\n".
            "\t* Reverse the migrations.\n".
            "\t*\n".
            "\t* @return void\n".
            "\t*/\n\n".

            "\tpublic function down()\n".
            "\t{\n".
                "\t\tSchema::dropIfExists('{$this->table->getName()}');\n".
            "\t}\n\n".

        "}";
        return $properties;
    }

    private function writeToFile(string $content) : void {

        file_put_contents("{$this->path}/{$this->fileName}",$content);
    }
}