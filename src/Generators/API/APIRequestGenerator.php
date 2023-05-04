<?php

namespace InfyOm\Generator\Generators\API;

use InfyOm\Generator\Generators\BaseGenerator;
use InfyOm\Generator\Generators\ModelGenerator;

class APIRequestGenerator extends BaseGenerator
{
    private string $createFileName;

    private string $updateFileName;

    private array $rules;

    private array $bodyParameters;
    
    private ModelGenerator $modelGenerator;

    public function __construct()
    {
        parent::__construct();

        $this->path = $this->config->paths->apiRequest;
        $this->createFileName = 'Create'.$this->config->modelNames->name.'APIRequest.php';
        $this->updateFileName = 'Update'.$this->config->modelNames->name.'APIRequest.php';
        $this->modelGenerator = new ModelGenerator($this->commandData);
    }

    public function generate()
    {
        if (config('infyom.laravel_generator.options.separate_rules', false)) {
            $this->rules = $this->modelGenerator->generateRules();
        }

        if (config('infyom.laravel_generator.options.body_parameter', false)) {
            $this->bodyParameters = $this->generateBodyParameters();
        }
        
        $this->generateCreateRequest();
        $this->generateUpdateRequest();
    }

    protected function generateCreateRequest()
    {
        ###### Alterada
        $templateData = view('laravel-generator::api.request.create', $this->variables())->render();

        $this->config->commandComment(infy_nl().'Create Request created: ');
        $this->config->commandInfo($this->createFileName);
        
        $templateData = view($this->commandData->dynamicVars, $templateData);
        if (config('infyom.laravel_generator.options.separate_rules', false)) {
            $templateData = str_replace('$RULES$', implode(','.infy_nl_tab(1, 3), $this->modelGenerator->generateRules()), $templateData);
        }
        if (config('infyom.laravel_generator.options.body_parameter', false)) {
            $templateData = str_replace('$BODYPARAMETERS$', implode(','.infy_nl_tab(1,3), $this->bodyParameters), $templateData);
        }

        g_filesystem()->createFile($this->path.$this->createFileName, $templateData);

        $this->commandData->commandComment("\nCreate Request created: ");
        $this->commandData->commandInfo($this->createFileName);
    }

    protected function generateUpdateRequest()
    {
        
        ###### Alterada
        $modelGenerator = app(ModelGenerator::class);
        $rules = $modelGenerator->generateUniqueRules();

        $templateData = view('laravel-generator::api.request.update', [
            'uniqueRules' => $rules,
        ])->render();

        g_filesystem()->createFile($this->path.$this->updateFileName, $templateData);
        
        $templateData = fill_template($this->commandData->dynamicVars, $templateData);
        if (config('infyom.laravel_generator.options.separate_rules', false)) {
            $templateData = str_replace('$RULES$', implode(','.infy_nl_tab(1, 3), $this->modelGenerator->generateRules()), $templateData);
        }
        if (config('infyom.laravel_generator.options.body_parameter', false)) {
            $templateData = str_replace('$BODYPARAMETERS$', implode(','.infy_nl_tab(1,3), $this->bodyParameters), $templateData);
        }

        $this->config->commandComment(infy_nl().'Update Request created: ');
        $this->config->commandInfo($this->updateFileName);
    }

    public function rollback()
    {
        if ($this->rollbackFile($this->path, $this->createFileName)) {
            $this->config->commandComment('Create API Request file deleted: '.$this->createFileName);
        }

        if ($this->rollbackFile($this->path, $this->updateFileName)) {
            $this->config->commandComment('Update API Request file deleted: '.$this->updateFileName);
        }
    }
    
    public function generateBodyParameters()
    {
        $dont_require_fields = config('infyom.laravel_generator.options.hidden_fields', [])
            + config('infyom.laravel_generator.options.excluded_fields');
        
        $bodyParameters = [];

        foreach ($this->commandData->fields as $field) {
            if (!$field->isPrimary && !in_array($field->name, $dont_require_fields)) {
                $bodyParameter = "'".$field->name."' => ['description' => '".$field->description."']";
                $bodyParameters[] = $bodyParameter;
            }
        }
        
        return$bodyParameters;
    }
}
