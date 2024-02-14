<?php

namespace InfyOm\Generator\Generators;

class ServiceTestGenerator extends BaseGenerator
{
    private string $serviceFileName;

    public function __construct()
    {
        parent::__construct();

        $this->path = config('laravel_generator.path.service_test', base_path('tests/Services/')) . '/';
        $this->serviceFileName = $this->config->modelNames->name . 'ServiceTest.php';
    }

    public function generate()
    {
        $this->generateService();
    }

    protected function generateService()
    {
        $templateData = view('laravel-generator::services.service_test', $this->variables())->render();

        g_filesystem()->createFile($this->path . '/' . $this->serviceFileName, $templateData);

        $this->config->commandComment(infy_nl().'Service Test created: ');
        $this->config->commandInfo($this->serviceFileName);
    }

    public function rollback()
    {
        if ($this->rollbackFile($this->path . '/', $this->serviceFileName)) {
            $this->config->commandComment('Service Test file deleted: '.$this->serviceFileName);
        }
    }
}
