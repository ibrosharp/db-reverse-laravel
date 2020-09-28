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
    private $fileCount;
    public function __construct() 
    {
        $this->path = ConfigState::getFileSystemConfiguration()["output_dir"]."/migrations";

                
        if(file_exists($this->path)) {

            array_map('unlink', glob($this->path."/*.*"));

            rmdir($this->path);
        }
      
        mkdir($this->path,0777,true);

        $this->fileCount = 0;
    }

    public function setTable(Table $table) : void {
        
        $this->table = $table;

        $this->className = "Create".str_replace(" ", "", ucwords(str_replace("_", " ", $table->getName())))."Table";

        $time = time();

        $date = date("Y_m_d", $time);

        $time = $time % 10000;

        $this->fileName = "{$date}_{$time}{$this->fileCount}_create_{$table->getName()}_table.php";

    }

    public function createFile() : void {   

        if(!$this->table) throw new FileSystemException("No table found");

        Interactor::sendMessage("Creating : {$this->fileName}");

        $this->writeToFile($this->wrapFrame($this->getTopContent(),$this->getButtomContent()));

        Interactor::sendSucceessMessage("Created :{$this->fileName}");

        $this->fileCount++;

    }

    public function createContraints(Table $table) {

        $this->fileCount++;

        $this->table = $table;

        $this->className = "Add".str_replace(" ", "", ucwords(str_replace("_", " ", $table->getName())))."ForeignKey";

        $time = time();

        $date = date("Y_m_d", $time);

        $time = $time % 10000;

        $this->fileName = "{$date}_{$time}{$this->fileCount}_add_{$table->getName()}_foreign_key.php";
        

        $topContent = "\t\tSchema::table('lists', function(Blueprint \$table) {\n";

        $bottomContent = $topContent;

        
        foreach($table->getContraints() as $contraints) {

            
            $topContent .= "\t\t\t\$table->foreign('{$contraints['foreign_key']}')->references('{$contraints['external_key']}')->on('{$contraints['external_table']}')->onDelete('cascade');\n";

            $bottomContent .= "\t\t\t\$table->dropForeign('{$contraints['foreign_key']}');\n";
        }

        $topContent .= "\t\t});\n";

        $bottomContent .= "\t\t});\n";
       


        $this->writeToFile($this->wrapFrame($topContent,$bottomContent));
    }

  
    private function getTopContent() : string {

        $content = "\t\tSchema::create('{$this->table->getName()}', function (Blueprint \$table) {\n";

        foreach($this->table->getColums() as $column) {
            $content .= "\t\t\t{$column->toSchemaString()}\n";
        }

        $content .= "\t\t});\n";

        return $content;
    }

    private function getButtomContent() : string {
        return  "\t\tSchema::dropIfExists('{$this->table->getName()}');\n";
    }

    private function wrapFrame(string $topContent,string $bottomContent) : string {

        $properties = "".
        "<?php\n\n".

        "use Illuminate\Database\Migrations\Migration;\n".
        "use Illuminate\Database\Schema\Blueprint;\n".
        "use Illuminate\Support\Facades\Schema;\n\n".

        "class ".$this->className." extends Migration\n{\n".

            "\tpublic function up()\n".
            "\t{\n".
                $topContent.
            "\t}\n\n".

            "\t/**\n".
            "\t* Reverse the migrations.\n".
            "\t*\n".
            "\t* @return void\n".
            "\t*/\n\n".

            "\tpublic function down()\n".
            "\t{\n".
               $bottomContent.
            "\t}\n\n".

        "}";
        return $properties;
    }

    private function writeToFile(string $content) : void {

        file_put_contents("{$this->path}/{$this->fileName}",$content);
    }

    public function __destruct()
    {
        Interactor::sendSucceessMessage("Total Migrations created: {$this->fileCount}");
    }


}