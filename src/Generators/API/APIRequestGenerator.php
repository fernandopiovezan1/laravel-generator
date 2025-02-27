<?php

namespace InfyOm\Generator\Generators\API;

use InfyOm\Generator\Generators\BaseGenerator;
use InfyOm\Generator\Generators\ModelGenerator;

class APIRequestGenerator extends BaseGenerator
{
    private string $createFileName;

    private string $updateFileName;

    public function __construct()
    {
        parent::__construct();

        $this->path = $this->config->paths->apiRequest . $this->config->modelNames->name;
        $this->createFileName = 'Create'.$this->config->modelNames->name.'APIRequest.php';
        $this->updateFileName = 'Update'.$this->config->modelNames->name.'APIRequest.php';
    }

    public function generate()
    {
        $this->generateCreateRequest();
        $this->generateUpdateRequest();
    }

    protected function generateCreateRequest()
    {
        $templateData = view('laravel-generator::api.request.create', $this->variables())->render();

        g_filesystem()->createFile($this->path . '/' . $this->createFileName, $templateData);

        $this->config->commandComment(infy_nl().'Create Request created: ');
        $this->config->commandInfo($this->createFileName);
    }

    protected function generateUpdateRequest()
    {
        $modelGenerator = app(ModelGenerator::class);
        $rules = $modelGenerator->generateUniqueRules();

        $templateData = view('laravel-generator::api.request.update', $this->variables())->render();

        g_filesystem()->createFile($this->path . '/' . $this->updateFileName, $templateData);

        $this->config->commandComment(infy_nl().'Update Request created: ');
        $this->config->commandInfo($this->updateFileName);
    }

    public function variables(): array
    {
        /** @var ModelGenerator $modelGenerator */
        $modelGenerator = app(ModelGenerator::class);
        return [
            'rules' => implode(','.infy_nl_tab(1, 3), $modelGenerator->generateRules()) . ',',
        ];
    }

    public function rollback()
    {
        if ($this->rollbackFile($this->path . '/', $this->createFileName)) {
            $this->config->commandComment('Create API Request file deleted: '.$this->createFileName);
        }

        if ($this->rollbackFile($this->path . '/', $this->updateFileName)) {
            $this->config->commandComment('Update API Request file deleted: '.$this->updateFileName);
        }

        if (!empty($this->path) && file_exists($this->path)) {
            rmdir($this->path);
            $this->config->commandComment('API Request dir deleted: ');
        }
    }
}
