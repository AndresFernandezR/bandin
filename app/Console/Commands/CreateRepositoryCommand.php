<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class CreateRepositoryCommand extends GeneratorCommand
{
    // The name of your command. This replaces $signature
    protected $name = 'repository:create';

    protected $description = 'Create a Repository Class file';

    // Type of class to make
    protected $type = 'Repository';

    //location of your custom stub
    protected function getStub()
    {
        return  base_path() . '/stubs/repository.stub';
    }

    //The root location the file should be written to
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Repositories';
    }
}
