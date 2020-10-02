<?php 

namespace App\Creators;

use App\DataObject\Table;
use App\Exceptions\FileSystemException;
use App\Interactor;
use App\Lib\Inflector;
use App\Lib\ValidationTranslator;
use App\State\ConfigState;
use App\State\ConnectionState;

class CrudControllerCreator implements FileCreator {

    private $table;
    private $className;
    private $fileName;
    private $path;
    private $model;
    private $modelVariable;
    private $routes;
    public function __construct() 
    {
        $this->path = ConfigState::getFileSystemConfiguration()["output_dir"]."/controllers";

                
        if(file_exists($this->path)) {

            array_map('unlink', glob($this->path."/*.*"));

            rmdir($this->path);
        }
      
        mkdir($this->path,0777,true);

        $this->routes;
    }

    public function setTable(Table $table) : void {

        $this->table = $table;

        $this->model = Inflector::singularize(str_replace(" ", "", ucwords(str_replace("_", " ", $table->getName()))));

        $this->className =  str_replace(" ", "", ucwords(str_replace("_", " ", $table->getName())))."Controller";

        $this->fileName = $this->className.".php";

        $this->modelVariable = strtolower($this->model);

    }

    public function createFile() : void {   

        if(!$this->table) throw new FileSystemException("No table found");

        Interactor::sendMessage("Creating : {$this->fileName}");

        $modelSeek = $this->modelSeek();
        $validation = $this->validations();
        $tableFiller = $this->tableFillerArray();

        $this->writeToFile($this->wrapFrame(
            $this->generateGetAllFunction(),
            $this->generateSingleFunction(),
            $this->generateCreateFunction($modelSeek,$validation,$tableFiller),
            $this->generateUpdateFunction($modelSeek,$validation,$tableFiller),
            $this->generateDeleteFunction($modelSeek)
        ));

        Interactor::sendSucceessMessage("Created :{$this->fileName}");

        $this->generateTableRoutes();

    }

    private function generateGetAllFunction() : string {
        $content = "".
        "\tpublic function index() : JsonResponse\n".
        "\t{\n\n".

            "\t\t\${$this->modelVariable} = {$this->model}::paginate(20);\n\n".
    
            "\t\treturn response()->json(['data' => \${$this->modelVariable}, 'status'=> true,'message' => '{$this->modelVariable} fetch successfully'],200);\n\n".

        
        "\t}\n\n";

        return $content;
    }

    private function generateSingleFunction() : string {
        return "";
    }

    private function generateCreateFunction(string $modelSeek,string $validation, string $tableFiller) : string {

        $content = "".
        "\tpublic function create(Request \$request) : JsonResponse\n".
        "\t{\n\n".

            $validation.

            $modelSeek.

            "\t\ttry{\n\n".

                "\t\t\t\${$this->modelVariable} = {$this->model}::create(\n".
                    $tableFiller.
                "\t\t\t);\n\n".

            "\t\t}catch(Exception \$e) {\n\n". 

                "\t\t\tLog::info(\$e)\n". 
                "\t\t\treturn response()->json(['status'=> false,'message' => 'failed to update {$this->modelVariable}'],500);\n\n".

            "\t\t}\n\n".

            "\t\treturn response()->json(['data' => \${$this->modelVariable}, 'status'=> true,'message' => '{$this->modelVariable} created successfully'],200);\n\n".

        "\t}\n\n";

        return $content;
        
    }   

    private function generateUpdateFunction(string $modelSeek,string $validation,string $tableFiller) : string {

        $content = "".
        "\tpublic function update(Request \$request,\$id)\n".
        "\t{\n\n".

            $validation.
    
            $modelSeek.
    
            "\t\ttry{\n\n".

                "\t\t\t\${$this->modelVariable}->update(\n".
                    $tableFiller.
                "\t\t\t);\n\n".

            "\t\t}catch(Exception \$e) {\n\n". 

                "\t\t\tLog::info(\$e)\n". 
                "\t\t\treturn response()->json(['status'=> false,'message' => 'failed to update {$this->modelVariable}'],500);\n\n".

            "\t\t}\n\n".
    
            "\t\treturn response()->json(['data' => \${$this->modelVariable}, 'status'=> true,'message' => '{$this->modelVariable} updated successfully'],200);\n\n".

        "\t}\n\n";

        return $content;
    }

    private function generateDeleteFunction(string $modelSeek) : string {
       
        $content ="".
        "\tpublic function destroy(\$id) : JsonResponse\n".
        "\t{\n\n".

            $modelSeek.
          

            "\t\ttry{\n\n".
    
                "\t\t\t\${$this->modelVariable}->delete();\n\n".

            "\t\t}catch(Exception \$e) {\n\n". 

                "\t\t\tLog::info(\$e)\n". 
                "\t\t\treturn response()->json(['status'=> false,'message' => 'failed to delete {$this->modelVariable}'],500);\n\n".

            "\t\t}\n\n".
    
            "\t\treturn response()->json(['data' => \${$this->modelVariable}, 'status'=> true,'message' => '{$this->modelVariable} deleted successfully'],200);\n\n".
        
        "\t}\n\n";

        return $content;
    }

    private function  validations() : string  {
        $content = "".

        "\t\t\$validator = Validator::make(\$request->all(),[\n";

        foreach($this->table->getColums() as $column) {
            if($column->isPrimary() || $column->isTimestamp()) continue;
            $validationString = ValidationTranslator::getValidationString($column);
            $content .= "\t\t\t'{$column->getName()}' => '{$validationString}' ,\n";
        }

        $content .= "\t\t]);\n\n".

        "\t\tif(\$validator->fails()) return response()->json(['status'=> false,'message' => \$validator->errors()->first()],500);\n\n";

        return $content;
    }

    private function modelSeek() :  string {

        $content = "".

        "\t\t\${$this->modelVariable} = {$this->model}::find(\$id);\n\n".
    
        "\t\tif(!\${$this->modelVariable}) return response()->json(['status'=> false,'message' => '{$this->modelVariable} not found'],404);\n\n";

        return $content;
    }

    private function tableFillerArray() : string {

        $content = "\t\t\t\t[\n";

        foreach($this->table->getColums() as $column) {
            if($column->isPrimary() || $column->isTimestamp()) continue;
            $content .= "\t\t\t\t\t'{$column->getName()}' => \$request->{$column->getName()},\n";
        }

        $content .= "\t\t\t\t]\n";

        return $content;
    }


    private function wrapFrame(string $all,string $single,string $create,string $update,string $delete) : string {
        $content = "".
        
        "<?php\n\n".

        "namespace App\Http\Controllers;\n\n".

        "use App\Http\Controllers\Controller;\n".
        "use App\{$this->model};\n".
        "use Exception;\n".
        "use Illuminate\Http\JsonResponse;\n".
        "use Illuminate\Http\Request;\n".
        "use Illuminate\Support\Facades\Log;\n".
        "use Illuminate\Support\Facades\Validator;\n\n".

        "class {$this->className} extends Controller\n".
        "{\n\n".

            "\t/**\n".
            "\t* Create a new controller instance.\n".
            "\t* \n".
            "\t* @return void\n".
            "\t*/\n".
            "\tpublic function __construct()\n".
            "\t{\n\n".

            "\t}\n\n". 

            $all.
            $single.
            $create. 
            $update. 
            $delete.

        "}";
   

        return $content;
   
       
    }

    private function writeToFile(string $content) : void {

        file_put_contents("{$this->path}/{$this->fileName}",$content);
    }

    private function generateTableRoutes() : void {
        $this->routes .= 
        "\$router->group(['prefix' => '{$this->table->getName()}'], function () use(\$router) {\n".
            "\t\$router->get('index','{$this->className}@index');\n".
            "\t\$router->post('create','{$this->className}@create');\n".
            "\t\$router->put('{id}/update','{$this->className}@update');\n".
            "\t\$router->delete('{id}/destroy','{$this->className}@destroy');\n".
        "});\n\n";
    }

    public function __destruct()
    {
        $routePath = ConfigState::getFileSystemConfiguration()["output_dir"]."/routes";

                
        if(file_exists($routePath)) {

            array_map('unlink', glob($routePath."/*.*"));

            rmdir($routePath);
        }
      
        mkdir($routePath,0777,true);

        $content = "<?php \n\n".$this->routes;
        file_put_contents("{$routePath}/web.php",$content);
    }
}