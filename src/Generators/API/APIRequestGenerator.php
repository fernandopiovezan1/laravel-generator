<?php

namespace InfyOm\Generator\Generators\API;

use InfyOm\Generator\Common\CommandData;
use InfyOm\Generator\Generators\BaseGenerator;
use InfyOm\Generator\Generators\ModelGenerator;
use InfyOm\Generator\Utils\FileUtil;

class APIRequestGenerator extends BaseGenerator
{
    /** @var CommandData */
    private $commandData;

    /** @var string */
    private $path;

    /** @var string */
    private $createFileName;

    /** @var string */
    private $updateFileName;
    
    /** @var array */
    private $rules;

    /** @var array */
    private $bodyParameters;
    /** @var ModelGenerator */
    private $modelGenerator;
    
    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
        $this->path = $commandData->config->pathApiRequest;
        $this->createFileName = 'Create'.$this->commandData->modelName.'APIRequest.php';
        $this->updateFileName = 'Update'.$this->commandData->modelName.'APIRequest.php';
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

    private function generateCreateRequest()
    {
        
        $templateData = get_template('api.request.create_request', 'laravel-generator');

        $templateData = fill_template($this->commandData->dynamicVars, $templateData);
        if (config('infyom.laravel_generator.options.separate_rules', false)) {
            $templateData = str_replace('$RULES$', implode(','.infy_nl_tab(1, 3), $this->modelGenerator->generateRules()), $templateData);
        }
        if (config('infyom.laravel_generator.options.body_parameter', false)) {
            $templateData = str_replace('$BODYPARAMETERS$', implode(','.infy_nl_tab(1,3), $this->bodyParameters), $templateData);
        }
        
        FileUtil::createFile($this->path, $this->createFileName, $templateData);

        $this->commandData->commandComment("\nCreate Request created: ");
        $this->commandData->commandInfo($this->createFileName);
    }

    private function generateUpdateRequest()
    {
        $modelGenerator = new ModelGenerator($this->commandData);
        $rules = $modelGenerator->generateUniqueRules();
        $this->commandData->addDynamicVariable('$UNIQUE_RULES$', $rules);

        $templateData = get_template('api.request.update_request', 'laravel-generator');

        $templateData = fill_template($this->commandData->dynamicVars, $templateData);
        if (config('infyom.laravel_generator.options.separate_rules', false)) {
            $templateData = str_replace('$RULES$', implode(','.infy_nl_tab(1, 3), $this->modelGenerator->generateRules()), $templateData);
        }
        if (config('infyom.laravel_generator.options.body_parameter', false)) {
            $templateData = str_replace('$BODYPARAMETERS$', implode(','.infy_nl_tab(1,3), $this->bodyParameters), $templateData);
        }

        FileUtil::createFile($this->path, $this->updateFileName, $templateData);

        $this->commandData->commandComment("\nUpdate Request created: ");
        $this->commandData->commandInfo($this->updateFileName);
    }

    public function rollback()
    {
        if ($this->rollbackFile($this->path, $this->createFileName)) {
            $this->commandData->commandComment('Create API Request file deleted: '.$this->createFileName);
        }

        if ($this->rollbackFile($this->path, $this->updateFileName)) {
            $this->commandData->commandComment('Update API Request file deleted: '.$this->updateFileName);
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
