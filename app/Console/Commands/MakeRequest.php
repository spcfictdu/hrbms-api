<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeRequest extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:request {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new request';

    
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Request';

    /**
     *
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/Stubs/Request.stub';
    }
    

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return  'App\Http\Requests';
    }

}
