<?php 

namespace App\Creators;

use App\DataObject\Table;
use App\Exceptions\FileSystemException;
use App\Interactor;
use App\State\ConfigState;
use App\State\ConnectionState;

class SeedersCreator implements FileCreator {

    private $table;
    private $className;
    private $fileName;
    private $path;
    public function __construct() 
    {
        $this->path = ConfigState::getFileSystemConfiguration()["output_dir"]."/seeders";

                
        if(file_exists($this->path)) {

            array_map('unlink', glob($this->path."/*.*"));

            rmdir($this->path);
        }
      
        mkdir($this->path,0777,true);
    }

    public function setTable(Table $table) : void {
        $this->table = $table;

        $this->className = str_replace(" ", "", ucwords(str_replace("_", " ", $table->getName())))."TableSeeder";

        $this->fileName = $this->className.".php";
    }

    public function createFile() : void {   

        if(!$this->table) throw new FileSystemException("No table found");

        Interactor::sendMessage("Creating : {$this->fileName}");

        $this->writeToFile($this->wrapFrame($this->makeContent()));

        Interactor::sendSucceessMessage("Created :{$this->fileName}");

    }

    private function makeContent() : string {
        $content = "";

        foreach ($this->table->getContents() as $data) {
            $content .= "\t\t\t[\n";
                foreach ($data as $columnName=>$value) {
                    $content .=(strlen($value) > 0)?  "\t\t\t\t'" . $columnName . "' => '" . ($value) . "',\n" : 
                     "\t\t\t\t'" . $columnName . "' => null,\n";
                }
            $content .= "\t\t\t],\n";
        }

        return $content;
    }

    private function wrapFrame(string $content) : string {
        $properties = "".
        "<?php\n\n".
        
        "/**\n".
        "* Run the database seeds.\n".
        "*\n".
        "* @return void\n".
        "*/\n\n".

        "namespace Database\Seeders;\n\n".

        "use Illuminate\Database\Seeder;\n\n".

        "class ".$this->className." extends Seeder\n{\n".

            "\tpublic function run()\n\t{\n".

                "\t\t\$rows = [\t\n".
                    $content.
                "\t\t];\n".

                "\t\tforeach (\$rows as \$row) {\n".

                    "\t\t\t\\DB::table('".$this->table->getName()."')->insert(\$row);\n".

                "\t\t}\n".

            "\t}\n".

        "}";
        return $properties;
    }

    private function writeToFile(string $content) : void {

        file_put_contents("{$this->path}/{$this->fileName}",$content);
    }
}