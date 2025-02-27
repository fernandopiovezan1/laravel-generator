<?php

namespace InfyOm\Generator\Commands\API;

use InfyOm\Generator\Commands\BaseCommand;
use InfyOm\Generator\Generators\SwaggerGenerator;

class APISwaggerGeneratorCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'infyom.api:swagger';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create swagger documentation for given model';

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        parent::handle();
        $swaggerGenerator = app(SwaggerGenerator::class);
        $swaggerGenerator->generate();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return array_merge(parent::getOptions(), []);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array_merge(parent::getArguments(), []);
    }
}
