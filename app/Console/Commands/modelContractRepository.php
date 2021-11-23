<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class modelContractRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:model-contract';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new contract and a new repository for the model (with RepositoryServiceProvider).';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        $this->files=$files;
        parent::__construct();
    }

    
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $modelName = $this->ask('Model name');;

        if ($modelName === '' || is_null($modelName) || empty($modelName)) return $this->error('The model name is invalid!');

        $fileModelName = "${modelName}.php";
        $fileModel = app_path()."/Models/$fileModelName";

        if(!$this->files->isFile($fileModel)){
            return $this->error("The model ".$fileModelName.' not exists!');
        }

        $contract = $modelName."Contract";
        $repository = $modelName."Repository";

$contentsContract =
'<?php
namespace App\Contracts;

interface '.$contract.' {
    /*  
    **
    * @param int $id
    * @return mixed
    * @throws ModelNotFoundException
    */
    public function findById(int $id);

    /**
    * @param array $params
    * @return mixed
    */
    public function create(array $params);

    /**
    * @param array $params
    * @return mixed
    */
    public function update(array $params);

    /**
    * @param $id
    * @return bool
    */
    public function delete($id);
}';

$contentsRepository = 
'<?php

use App\Models\\'.$modelName.';
use App\Contracts\\'.$contract.';
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class '.$repository.' extends BaseRepository implements '.$contract.'
{
    public function __construct('.$modelName.' $model){
        parent::__construct($model);
        $this->model = $model;
    }
    /*  
    **
    * @param int $id
    * @return mixed
    * @throws ModelNotFoundException
    */
    public function findById(int $id){
        try {
            return $this->findOneOrFail($id);
        } 
        catch (ModelNotFoundException $e) {
             throw new ModelNotFoundException($e);
        }
    }
            
    /**
    * @param array $params
    * @return Category|mixed
    */
    public function create(array $params){
        try {
            $collection = collect($params);
            $data = new '.$modelName.'($collection->all());
            $data->save();
            return $data;
        } 
        catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    /**
    * @param array $params
    * @return mixed
    */
    public function update(array $params){
        $data = $this->findById($params["id"]);
        $collection = collect($params)->except("_token");
        $data->update($collection->all());
        return $data;
    }

    /**
    * @param $id
    * @return bool|mixed
    */
    public function delete($id){
        $data = $this->findById($id);
        $data->delete();
        return $data;
    }
}';
        
        if ($this->confirm('Do you wish to create '.$modelName.' model Contract and Repository files?')) {
            $fileContractName = "${contract}.php";
            $fileRepositoryName = "${repository}.php";
            $path = app_path();
            
            $fileContract = $path."/Contracts/$fileContractName";
            $fileRepository = $path."/Repositories/$fileRepositoryName";

            $contractsDir = $path."/Contracts";
            $repositoriesDir = $path."/Repositories";

            $this->info("Execute...");

            if($this->files->isDirectory($contractsDir)){
                if($this->files->isFile($fileContract)){
                    return $this->error($fileContractName.' File Already exists!');
                }
                if(!$this->files->put($fileContract, $contentsContract)){
                    return $this->error('Something went wrong!');
                }
                $this->info("$fileContractName generated in App/Contracts !");
            }
            else{
                $this->files->makeDirectory($contractsDir, 0777, true, true);
                $this->info("App/Contracts directrory generated!");
                if(!$this->files->put($fileContract, $contentsContract)){
                    return $this->error('Something went wrong!');
                }
                $this->info("$fileContractName generated App/Contracts !");
            }

            if($this->files->isDirectory($repositoriesDir)){
                if($this->files->isFile($fileRepository)){
                    $this->files->delete($fileContract);
                    return $this->error($fileRepositoryName.' File Already exists!, '.$fileContractName." deleted!");
                }

                if(!$this->files->put($fileRepository, $contentsRepository)){
                    $this->files->delete($fileContract);
                    return $this->error($fileRepositoryName.': Something went wrong putting daata!,'.$fileContractName." deleted!");
                }

                $this->info("$fileRepositoryName generated in App\Repositories !");
            }
            else{
                $this->files->makeDirectory($repositoriesDir, 0777, true, true);
                $this->info("App/Repositories generated!");
                if(!$this->files->put($fileRepository, $contentsRepository)){
                    $this->files->delete($fileContract);
                    return $this->error($fileRepositoryName.': Something went wrong putting daata!,'.$fileContractName." deleted!");
                }
                $this->info("$fileContractName generated in App\Repositories !");
            }

$contentsRepSerPro =
'<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Contracts\\'.$contract.';
use App\Repositories\\'.$repository.';
/*RepositoryContractDeclare*/


class RepositoryServiceProvider extends ServiceProvider
{
    protected $repositories = 
    [
        '.$contract.'::class => '.$repository.'::class,
        /*RepositoryContractBind*/

    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->repositories as $interface => $implementation){
            $this->app->bind($interface, $implementation);
        }
    }
}
';
            $fileRepSerProviderName = "RepositoryServiceProvider.php";
            $fileRepositoryServiceProvider = $path."/Providers/$fileRepSerProviderName";
            //RepositoryServiceProvider.php already exist
            if($this->files->isFile($fileRepositoryServiceProvider)){
                $declareContent = "use App\Contracts\\".$contract.";\nuse App\Repositories\\".$repository.";\n";
                $bindContent = "\t\t".$contract."::class => ".$repository."::class,\n";
                
                if($this->updateFile($fileRepositoryServiceProvider, $declareContent, $bindContent) == -1){
                    return $this->error("Cannot write to file ($fileRepositoryServiceProvider), please edit manually:\nAfter -> RepositoryContractDeclare insert\n\nuse App\Contracts\*Class*Contract;\nuse App\Repositories\*Class*Repository;\n\nAfter -> RepositoryContractBind insert\n\n*Class*Contract::class         =>         *Class*Repository::class,\n");
                }
                return $this->info("$fileRepSerProviderName updated succesfully !");
            }
            //create RepositoryServiceProvider.php
            else{
                if(!$this->files->put($fileRepositoryServiceProvider, $contentsRepSerPro)){
                    return $this->error("Something went wrong putting data in $fileRepSerProviderName");
                }
                return $this->info("$fileRepSerProviderName generated in App\Providers !");
            }
        }
        return 0;
    }

    private function updateFile($file, $declareContent, $bindContent){
        $writeOne = FALSE;
        $writeTwo = FALSE;
        $newData = "";
        $fh = fopen($file,'r+');
        if($fh == FALSE) return -1;
        while(!feof($fh)) {
            $data = fgets($fh);
            if( $writeOne === FALSE ){
                if(str_contains($data, 'RepositoryContractDeclare')){
                    $newData = $newData.$declareContent;
                    $writeOne = TRUE;
                }
            } 
            if( $writeTwo === FALSE ){
                if( str_contains($data, 'RepositoryContractBind') ){
                    $newData = $newData.$bindContent;
                    $writeTwo = TRUE;
                }
            }
            $newData = $newData.$data;
        }
        if(file_put_contents($file, $newData) == FALSE){
            fclose($fh);
            return -1;
        }
        fclose($fh); 
        return 0;
    }
}