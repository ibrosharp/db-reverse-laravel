<?php 

namespace App\Creators;

use App\DataObject\Table;
use App\Exceptions\FileSystemException;
use App\Interactor;
use App\Lib\Inflector;
use App\State\ConfigState;
use App\State\ConnectionState;

class ModelsCreator implements FileCreator {

    private $table;
    private $className;
    private $fileName;
    private $path;
    public function __construct() 
    {
        $this->path = ConfigState::getFileSystemConfiguration()["output_dir"]."/models";

                
        if(file_exists($this->path)) {

            array_map('unlink', glob($this->path."/*.*"));

            rmdir($this->path);
        }
      
        mkdir($this->path,0777,true);
    }

    public function setTable(Table $table) : void {

        $this->table = $table;

        $this->className = Inflector::singularize(str_replace(" ", "", ucwords(str_replace("_", " ", $table->getName()))));

        $this->fileName = $this->className.".php";

    }

    public function createFile() : void {   

        if(!$this->table) throw new FileSystemException("No table found");

        Interactor::sendMessage("Creating : {$this->fileName}");

        $columns = $this->getColumns();

        $this->writeToFile($this->wrapFrame($columns["fillables"],$columns["hidden"],$this->getRelationsips()));

        Interactor::sendSucceessMessage("Created :{$this->fileName}");

    }

    private function getColumns() : array {

        $fillables = "";
        $hidden = "";
        foreach($this->table->getColums() as $column) {
            if(preg_match("/(pass|created_at|updated_at|password)/", $column->getName()) === 1) {
                $hidden .= "\t\t'{$column->getName()}',\n";
                continue;
            }

            $fillables .= "\t\t'{$column->getName()}',\n";
        }
        return [
            "fillables" => $fillables,
            "hidden" => $hidden];
    }

    private function getRelationsips() : string { 
        return "\t\tpublic function notYet() {\n".
            "\t\t\t\$this->hasMany('App\None');\n".
        "\t}";
    }

    private function wrapFrame($fillables,$hidden,$relationShips) : string {

        $properties = "".
        
        "<?php\n\n".


        "namespace App;\n\n".


        "use Illuminate\Database\Eloquent\Model;\n\n".


        "class {$this->className} extends Model {\n". 
        
            "\tprotected \$table = '{$this->table->getName()}';\n\n".

            "\tprotected \$fillable = [\n".
                $fillables. PHP_EOL.
              
            "\t];\n\n".

            "\tprotected \$hidden = [\n". 
                $hidden.  PHP_EOL.
            "\t];\n\n".

            $relationShips.  PHP_EOL.

        "}\n";
      
        
        return $properties;
    }

    private function writeToFile(string $content) : void {

        file_put_contents("{$this->path}/{$this->fileName}",$content);
    }
}