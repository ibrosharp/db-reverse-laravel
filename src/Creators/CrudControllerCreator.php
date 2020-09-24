<?php 

namespace App\Creators;

use App\DataObject\Table;
use App\Exceptions\FileSystemException;
use App\Interactor;
use App\Lib\Inflector;
use App\State\ConfigState;
use App\State\ConnectionState;

class CrudControllerCreator implements FileCreator {

    private $table;
    private $className;
    private $fileName;
    private $path;
    private $model;
    public function __construct() 
    {
        $this->path = ConfigState::getFileSystemConfiguration()["output_dir"]."/controllers";

                
        if(file_exists($this->path)) {

            array_map('unlink', glob($this->path."/*.*"));

            rmdir($this->path);
        }
      
        mkdir($this->path,0777,true);
    }

    public function setTable(Table $table) : void {

        $this->table = $table;

        $this->model = Inflector::singularize(str_replace(" ", "", ucwords(str_replace("_", " ", $table->getName()))));

        $this->className =  str_replace(" ", "", ucwords(str_replace("_", " ", $table->getName())))."Controller";

        $this->fileName = $this->className.".php";

    }

    public function createFile() : void {   

        if(!$this->table) throw new FileSystemException("No table found");

        Interactor::sendMessage("Creating : {$this->fileName}");

        $this->writeToFile($this->wrapFrame(
            $this->generateGetAllFunction(),
            $this->generateSingleFunction(),
            $this->generateCreateFunction(),
            $this->generateUpdateFunction(),
            $this->generateDeleteFunction()
        ));

        Interactor::sendSucceessMessage("Created :{$this->fileName}");

    }

    private function generateGetAllFunction() : string {
        return "";
    }

    private function generateSingleFunction() : string {
        return "";
    }

    private function generateCreateFunction() : string {
        return "";
    }   

    private function generateUpdateFunction() : string {
        return "";
    }

    private function generateDeleteFunction() : string {
        return "";
    }


    private function wrapFrame(string $all,string $single,string $create,string $update,string $delete) : string {
        return "";
       
    }

    private function writeToFile(string $content) : void {

        file_put_contents("{$this->path}/{$this->fileName}",$content);
    }
}