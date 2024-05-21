<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeModule extends Command
{
    protected $signature = 'make:module
                            {name : The name of the controller.}
                            {--a|all : Create all controller methods.}
                            {--i|index : Create index method.}
                            {--c|create : Create create method.}
                            {--s|show : Create show method.}
                            {--u|update : Create update method.}
                            {--d|delete : Create delete method.}';

    protected $description = 'Generate a custom controller with all its methods and dependencies. -AB';

    public function handle()
    {
        $controllerName = $this->argument('name');
        $options = $this->options();
        $stub = $this->getStub();

        $this->createBaseRepository();

        // $this->createController($controllerName);

        if ($options['all']) {
            $this->createRequest($controllerName, 'Index');
            $this->createRequest($controllerName, 'Create');
            $this->createRequest($controllerName, 'Show');
            $this->createRequest($controllerName, 'Update');
            $this->createRequest($controllerName, 'Delete');

            $this->createRepository($controllerName, 'Index');
            $this->createRepository($controllerName, 'Create');
            $this->createRepository($controllerName, 'Show', true);
            $this->createRepository($controllerName, 'Update', $update = true);
            $this->createRepository($controllerName, 'Delete', $delete = true);

            $this->replaceNamespace($stub, $controllerName)
                ->replaceDependencies($stub, $controllerName)
                ->writeController($controllerName, $stub);
        } else if ($options['index']) {
            $this->createRequest($controllerName, 'Index');
            $this->createRepository($controllerName, 'Index');

            $this->replaceNamespace($stub, $controllerName)
                ->replaceDependencies($stub, $controllerName)
                ->writeController($controllerName, $stub);
        }
    }

    // protected function createMigration($controllerName)
    // {
    //     $this->call('make:migration', [
    //         'name' => 'create_' . strtolower($controllerName) . 's_table',
    //     ]);
    // }

    protected function createModel($controllerName)
    {
        $this->call('make:model', [
            'name' => $controllerName,
        ]);
    }

    protected function createRequest($controllerName, $requestName)
    {
        $this->call('make:request', [
            'name' => $controllerName . '/' . $requestName . $controllerName . 'Request',
        ]);
    }

    protected function createBaseRepository()
    {
        // Create a folder name Repositories in app folder if it doesn't exist
        $repositoryPath = app_path('Repositories');
        if (!file_exists($repositoryPath)) {
            mkdir($repositoryPath);
        }

        // Create a BaseRepository.php file in app/Repositories folder
        $baseRepositoryPath = $repositoryPath . '/BaseRepository.php';

        if (!file_exists($baseRepositoryPath)) {
            $this->info('Generating BaseRepository...');

            $baseRepositoryContent = <<<'EOD'
            <?php

            namespace App\Repositories;

            use App\Traits\Generator;
            use App\Traits\Getter;
            use App\Traits\ResponseAPI;

            class BaseRepository
            {
                // use ResponseAPI,Getter, Generator;
                use ResponseAPI;
            }
            EOD;

            file_put_contents($baseRepositoryPath, $baseRepositoryContent);

            $this->info('BaseRepository generated successfully.');
        } else {
            $this->error('BaseRepository already exists!');
        }
    }

    protected function createRepository($controllerName, $repositoryName, $show = false, $update = false, $delete = false)
    {
        // Create a folder name for the controller Name in app/Repositories folder if it doesn't exist
        $repositoryPath = app_path('Repositories/') . $controllerName;

        if (!file_exists($repositoryPath)) {
            mkdir($repositoryPath);
        }

        // Create a Repository file in app/Repositories folder
        $repositoryPath = $repositoryPath . '/' . $repositoryName . $controllerName . 'Repository.php';

        if (!file_exists($repositoryPath)) {
            $this->info('Generating Repository...');

            $executeMethod = 'public function execute()';
            if ($show || $update || $delete) {
                $executeMethod = 'public function execute($id)';
            }

            $repositoryContent = <<<EOD
        <?php

        namespace App\Repositories\DummyNamespace;

        use App\Repositories\BaseRepository;

        class DummyRepository extends BaseRepository
        {
            $executeMethod
            {
                //
            }
        }
        EOD;

            $repositoryContent = str_replace(
                ['DummyNamespace', 'DummyRepository'],
                [$controllerName, $repositoryName . $controllerName  . 'Repository'],
                $repositoryContent
            );

            file_put_contents($repositoryPath, $repositoryContent);

            $this->info('Repository generated successfully.');
        } else {
            $this->error('Repository already exists!');
        }
    }

    protected function createController($controllerName)
    {
        $controllerPath = app_path('Http/Controllers/') . str_replace('\\', '/', $controllerName) . 'Controller.php';

        if (file_exists($controllerPath)) {
            $this->error('Controller already exists!');
            return;
        }

        $this->generateFile($controllerPath, $this->getStub(), 'Controller', $controllerName);



        $this->info('Controller and its dependencies generated successfully.');
    }

    protected function generateFile($filePath, $content, $type)
    {
        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }

        file_put_contents($filePath, $content);
        $this->info(ucfirst($type) . ' generated successfully.');
    }

    protected function getStub()
    {
        // Create the stub directory if it doesn't exist
        $stubDirectory = base_path('app/console/commands/stubs/controllers');
        if (!File::exists($stubDirectory)) {
            File::makeDirectory($stubDirectory, 0755, true);
        }

        // Create the Controller stub file if it doesn't exist
        $stubPath = $stubDirectory . '/Controller.stub';
        if (!File::exists($stubPath)) {
            // Content of the Controller stub file
            $stubContent = <<<'EOD'
            <?php

            namespace DummyNamespace;

            use App\Http\Controllers\Controller;

            use App\Http\Requests\Dummy\{IndexDummyRequest, CreateDummyRequest, ShowDummyRequest, UpdateDummyRequest, DeleteDummyRequest};
           
            use App\Repositories\Dummy\{IndexDummyRepository, CreateDummyRepository, ShowDummyRepository, UpdateDummyRepository, DeleteDummyRepository};

            class DummyController extends Controller
            {
                protected $index, $create, $show, $update, $delete;

                // * CONSTRUCTOR INJECTION
                public function __construct(
                    IndexDummyRepository   $index,
                    CreateDummyRepository  $create,
                    ShowDummyRepository    $show,
                    UpdateDummyRepository  $update,
                    DeleteDummyRepository  $delete
                ){
                    $this->index   = $index;
                    $this->create  = $create;
                    $this->show    = $show;
                    $this->update  = $update;
                    $this->delete  = $delete;
                }

                protected function index(IndexDummyRequest $request) {
                    return $this->index->execute();
                }

                
                protected function create(CreateDummyRequest $request) {
                    return $this->create->execute($request);
                }

                
                protected function show(ShowDummyRequest $request, $id) {
                    return $this->show->execute($id);
                }

                
                protected function update(UpdateDummyRequest $request, $id) {
                    return $this->update->execute($request, $id);
                }

                
                protected function delete(DeleteDummyRequest $request, $id) {
                    return $this->delete->execute($id);
                }
            }
            EOD;

            // Save the stub content to the file
            file_put_contents($stubPath, $stubContent);
        }

        // Return the content of the stub file
        return file_get_contents($stubPath);
    }

    protected function replaceNamespace(&$stub, $controllerName)
    {
        $stub = str_replace('DummyNamespace', 'App\\Http\\Controllers\\' . $controllerName, $stub);
        return $this;
    }

    protected function replaceDependencies(&$stub, $controllerName)
    {
        $replacements = [
            // Request
            'use App\Http\Requests\Dummy\{IndexDummyRequest, CreateDummyRequest, ShowDummyRequest, UpdateDummyRequest, DeleteDummyRequest};' => 'use App\Http\Requests\\' . $controllerName . '\{Index' . $controllerName . 'Request, Create' . $controllerName . 'Request, Show' . $controllerName . 'Request, Update' . $controllerName . 'Request, Delete' . $controllerName . 'Request};',

            'IndexDummyRequest'   => 'Index' . $controllerName . 'Request',
            'CreateDummyRequest'  => 'Create' . $controllerName . 'Request',
            'ShowDummyRequest'    => 'Show' . $controllerName . 'Request',
            'UpdateDummyRequest'  => 'Update' . $controllerName . 'Request',
            'DeleteDummyRequest'  => 'Delete' . $controllerName . 'Request',

            // Repository
            'use App\Repositories\Dummy\{IndexDummyRepository, CreateDummyRepository, ShowDummyRepository, UpdateDummyRepository, DeleteDummyRepository};' => 'use App\Repositories\\' . $controllerName . '\{Index' . $controllerName . 'Repository, Create' . $controllerName . 'Repository, Show' . $controllerName . 'Repository, Update' . $controllerName . 'Repository, Delete' . $controllerName . 'Repository};',

            'IndexDummyRepository'   => 'Index' . $controllerName . 'Repository',
            'CreateDummyRepository'  => 'Create' . $controllerName . 'Repository',
            'ShowDummyRepository'    => 'Show' . $controllerName . 'Repository',
            'UpdateDummyRepository'  => 'Update' . $controllerName . 'Repository',
            'DeleteDummyRepository'  => 'Delete' . $controllerName . 'Repository',

            'DummyController' => $controllerName . 'Controller',
        ];
        foreach ($replacements as $search => $replacement) {
            $stub = str_replace($search, $replacement, $stub);
        }
        return $this;
    }

    protected function writeController($controllerName, $stub)
    {
        // Create directory first then the controller
        $controllerDirectory = app_path('Http/Controllers/') . $controllerName;

        if (!file_exists($controllerDirectory)) {
            mkdir($controllerDirectory, 0755, true);
        }

        $controllerPath = $controllerDirectory . '/' . str_replace('\\', '/', $controllerName) . 'Controller.php';

        if (!file_exists($controllerPath)) {
            $this->info('Generating Controller...');

            file_put_contents($controllerPath, $stub);

            $this->info('Controller generated successfully.');
            // $this->info($controllerPath);
            $this->output->writeln("\n<fg=green>INFO</> Request <options=bold>[{$controllerPath}]</> created successfully.\n");
        } else {
            $this->error('Controller already exists!');
        }
    }
}
