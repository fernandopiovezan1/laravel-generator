<?php

namespace InfyOm\Generator\Generators;

class ServiceGenerator extends BaseGenerator
{
    private string $createFileName;
    private string $updateFileName;
    private string $deleteFileName;
    private string $retrieveFileName;
    private string $retrievesFileName;

    public function __construct()
    {
        parent::__construct();

        $this->path = $this->config->paths->service . '/'. $this->config->modelNames->name;
        $this->createFileName = 'Create' . $this->config->modelNames->name . 'Service.php';
        $this->updateFileName = 'Update' . $this->config->modelNames->name . 'Service.php';
        $this->deleteFileName = 'Delete' . $this->config->modelNames->name . 'Service.php';
        $this->retrieveFileName = 'Retrieve' . $this->config->modelNames->name . 'Service.php';
        $this->retrievesFileName = 'Retrieves' . $this->config->modelNames->plural . 'Service.php';
    }
    
    public function generate()
    {
        $this->generateCreateService();
        $this->generateUpdateService();
        $this->generateDeleteService();
        $this->generateRetrieveService();
        $this->generateRetrievesService();
    }

    protected function generateCreateService()
    {
        $templateData = view('laravel-generator::services.create', $this->variables())->render();

        g_filesystem()->createFile($this->path . '/' . $this->createFileName, $templateData);

        $this->config->commandComment(infy_nl().'Create Service created: ');
        $this->config->commandInfo($this->createFileName);

    }

    protected function generateUpdateService()
    {
        $templateData = view('laravel-generator::services.update', $this->variables())->render();

        g_filesystem()->createFile($this->path . '/' . $this->updateFileName, $templateData);

        $this->config->commandComment(infy_nl().'Update Service created: ');
        $this->config->commandInfo($this->updateFileName);
    }

    protected function generateDeleteService()
    {
        $templateData = view('laravel-generator::services.delete', $this->variables())->render();

        g_filesystem()->createFile($this->path . '/' . $this->deleteFileName, $templateData);

        $this->config->commandComment(infy_nl().'Delete Service created: ');
        $this->config->commandInfo($this->deleteFileName);
    }

    protected function generateRetrieveService()
    {
        $templateData = view('laravel-generator::services.retrieve', $this->variables())->render();

        g_filesystem()->createFile($this->path . '/' . $this->retrieveFileName, $templateData);

        $this->config->commandComment(infy_nl().'Retrieve Service created: ');
        $this->config->commandInfo($this->retrieveFileName);
    }

    protected function generateRetrievesService()
    {
        $templateData = view('laravel-generator::services.retrieves', $this->variables())->render();

        g_filesystem()->createFile($this->path . '/' . $this->retrievesFileName, $templateData);

        $this->config->commandComment(infy_nl().'Retrieves Service created: ');
        $this->config->commandInfo($this->retrievesFileName);
    }

    public function rollback()
    {
        if ($this->rollbackFile($this->path, $this->fileName)) {
            $this->config->commandComment('Repository file deleted: '.$this->fileName);
        }
    }
}
