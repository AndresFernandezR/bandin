<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class CreateServiceCommand extends GeneratorCommand
{
    // The name of your command. This replaces $signature
    protected $name = 'service:create'; 

    protected $description = 'Create a Service Class file';

    // Type of class to make
    protected $type = 'Service'; 

    //location of your custom stub
    protected function getStub()
    {
        return  base_path() . '/stubs/service.stub';
    }

    //The root location the file should be written to
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Services';
    }
}