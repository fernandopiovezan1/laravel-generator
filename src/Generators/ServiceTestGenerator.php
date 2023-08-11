<?php

namespace InfyOm\Generator\Generators;

class ServiceTestGenerator extends BaseGenerator
{
    private string $createFileName;
    private string $updateFileName;
    private string $deleteFileName;
    private string $retrieveFileName;
    private string $retrievesFileName;

    public function __construct()
    {
        parent::__construct();

        $this->path = config('laravel_generator.path.service_test', base_path('tests/Services/')) . '/'. $this->config->modelNames->name;
        $this->createFileName = 'Create' . $this->config->modelNames->name . 'ServiceTest.php';
        $this->updateFileName = 'Update' . $this->config->modelNames->name . 'ServiceTest.php';
        $this->deleteFileName = 'Delete' . $this->config->modelNames->name . 'ServiceTest.php';
        $this->retrieveFileName = 'Retrieve' . $this->config->modelNames->name . 'ServiceTest.php';
        $this->retrievesFileName = 'Retrieves' . $this->config->modelNames->plural . 'ServiceTest.php';
    }

    public function generate()
    {
        $this->generateCreateService();
        $this->generateDeleteService();
        $this->generateUpdateService();
        $this->generateRetrieveService();
        $this->generateRetrievesService();
    }

    protected function generateCreateService()
    {
        $templateData = view('laravel-generator::services.test.create', $this->variables())->render();

        g_filesystem()->createFile($this->path . '/' . $this->createFileName, $templateData);

        $this->config->commandComment(infy_nl().'Create Service Test created: ');
        $this->config->commandInfo($this->createFileName);
    }

    protected function generateUpdateService()
    {
        $templateData = view('laravel-generator::services.test.update', $this->variables())->render();

        g_filesystem()->createFile($this->path . '/' . $this->updateFileName, $templateData);

        $this->config->commandComment(infy_nl().'Update Service Test created: ');
        $this->config->commandInfo($this->updateFileName);
    }

    protected function generateDeleteService()
    {
        $templateData = view('laravel-generator::services.test.delete', $this->variables())->render();

        g_filesystem()->createFile($this->path . '/' . $this->deleteFileName, $templateData);

        $this->config->commandComment(infy_nl().'Delete Service Test created: ');
        $this->config->commandInfo($this->deleteFileName);
    }

    protected function generateRetrieveService()
    {
        $templateData = view('laravel-generator::services.test.retrieve', $this->variables())->render();

        g_filesystem()->createFile($this->path . '/' . $this->retrieveFileName, $templateData);

        $this->config->commandComment(infy_nl().'Retrieve Service Test created: ');
        $this->config->commandInfo($this->retrieveFileName);
    }

    protected function generateRetrievesService()
    {
        $templateData = view('laravel-generator::services.test.retrieves', $this->variables())->render();

        g_filesystem()->createFile($this->path . '/' . $this->retrievesFileName, $templateData);

        $this->config->commandComment(infy_nl().'Retrieves Service Test created: ');
        $this->config->commandInfo($this->retrievesFileName);
    }

    public function rollback()
    {
        if ($this->rollbackFile($this->path . '/', $this->createFileName)) {
            $this->config->commandComment('Create API Service Test file deleted: '.$this->createFileName);
        }

        if ($this->rollbackFile($this->path . '/', $this->createFileName)) {
            $this->config->commandComment('Create API Service Test file deleted: '.$this->createFileName);
        }

        if ($this->rollbackFile($this->path . '/', $this->updateFileName)) {
            $this->config->commandComment('Update API Service Test file deleted: '.$this->updateFileName);
        }

        if ($this->rollbackFile($this->path . '/', $this->deleteFileName)) {
            $this->config->commandComment('Delete API Service Test file deleted: '.$this->deleteFileName);
        }

        if ($this->rollbackFile($this->path . '/', $this->retrieveFileName)) {
            $this->config->commandComment('Retrieve API Service Test file deleted: '.$this->retrieveFileName);
        }

        if ($this->rollbackFile($this->path . '/', $this->retrievesFileName)) {
            $this->config->commandComment('Retrieves API Service Test file deleted: '.$this->retrievesFileName);
        }

        if (!empty($this->path) && is_dir($this->path)) {
            rmdir($this->path);
            $this->config->commandComment('Services Test dir deleted.');
        }

    }
}
