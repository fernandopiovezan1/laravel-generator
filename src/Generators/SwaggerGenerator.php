<?php

namespace InfyOm\Generator\Generators;

use InfyOm\Generator\Common\GeneratorField;

class SwaggerGenerator extends BaseGenerator
{

    private string $schemaName;

    private array  $var;

    public function __construct()
    {
        parent::__construct();

        $this->path = 'app/Swagger';
        $this->schemaName = $this->config->modelNames->name . 'Schema.php';
        $this->endpointName = $this->config->modelNames->name . 'Endpoint.php';
    }

    public static function generateTypes(array $inputFields): array
    {
        $fieldTypes = [];
        $dont_require_fields = config('laravel_generator.options.hidden_fields', [])
            + config('laravel_generator.options.excluded_fields');

        /** @var GeneratorField $field */
        foreach ($inputFields as $field) {
            $fieldData = self::getFieldType($field->dbType);

            if (empty($fieldData['fieldType']) || in_array($field->name, $dont_require_fields)) {
                continue;
            }

            $fieldTypes[] = [
                'fieldName'   => $field->name,
                'type'        => $fieldData['fieldType'],
                'format'      => $fieldData['fieldFormat'],
                'nullable'    => ! $field->isNotNull ? 'true' : 'false',
                'readOnly'    => ! $field->isFillable ? 'true' : 'false',
                'description' => (! empty($field->description)) ? $field->description : '',
            ];
        }

        return $fieldTypes;
    }

    public static function getFieldType($type): array
    {
        $fieldType = null;
        $fieldFormat = null;
        switch (strtolower(explode(',', $type)[0])) {
            case 'increments':
            case 'integer':
            case 'unsignedinteger':
            case 'smallinteger':
            case 'long':
            case 'biginteger':
            case 'unsignedbiginteger':
                $fieldType = 'integer';
                $fieldFormat = 'int32';
                break;
            case 'double':
            case 'float':
            case 'real':
            case 'decimal':
                $fieldType = 'number';
                $fieldFormat = 'number';
                break;
            case 'boolean':
                $fieldType = 'boolean';
                break;
            case 'string':
            case 'char':
            case 'text':
            case 'mediumtext':
            case 'longtext':
            case 'enum':
                $fieldType = 'string';
                break;
            case 'byte':
                $fieldType = 'string';
                $fieldFormat = 'byte';
                break;
            case 'binary':
                $fieldType = 'string';
                $fieldFormat = 'binary';
                break;
            case 'password':
                $fieldType = 'string';
                $fieldFormat = 'password';
                break;
            case 'date':
                $fieldType = 'string';
                $fieldFormat = 'date';
                break;
            case 'datetime':
            case 'timestamp':
                $fieldType = 'string';
                $fieldFormat = 'date-time';
                break;
        }

        return ['fieldType' => $fieldType, 'fieldFormat' => $fieldFormat];
    }

    public function flattenRules(array $mixedRules): array
    {
        $rules = [];

        foreach ($mixedRules as $attribute => $rule) {
            if (is_object($rule)) {

                $rules[$attribute][] = get_class($rule);
                continue;
            }

            if (is_array($rule)) {
                $rulesStrs = [];

                foreach ($rule as $ruleItem) {
                    $rulesStrs[] = is_object($ruleItem) ? get_class($ruleItem) : $ruleItem;
                }

                $rules[$attribute] = $rulesStrs;
                continue;
            }

            if (is_string($rule)) {
                $rules[$attribute] = explode('|', $rule);
                continue;
            }
        }

        return $rules;
    }

    public function generate()
    {
        $this->var = $this->variables();
        $this->generateSchema();
        $this->generateEndpoints();
    }

    public function generateFactoryFields(): array
    {
        try {
            $model = new \ReflectionClass('App\Models\\' . $this->config->modelNames->name);
            $model = $model->newInstance();
            return $model->factory()->make()->toArray();
        } catch (\ReflectionException $e) {
            return [];
        }
    }

    public function variables(): array
    {
        /** @var ModelGenerator $modelGenerator */
        $modelGenerator = app(ModelGenerator::class);
        $requiredFields = $modelGenerator->generateRequiredFields();
        $requiredFields = '{' . implode(',', $requiredFields) . '}';
        $fieldTypes = $this->generateTypes($this->config->fields);
        $properties = $this->generateProperties($fieldTypes);

        return [
            'requiredFields'       => $requiredFields,
            'fieldTypes'           => $fieldTypes,
            'properties'           => implode(',' . infy_nl() . ' ', $properties),
            'translateModel'       => __("models/{$this->config->modelNames->snakePlural}.singular"),
            'translateModelPlural' => __("models/{$this->config->modelNames->snakePlural}.plural"),
            'validateFields'       => $this->generateValidateFields(),
        ];
    }

    private function generateEndpoints()
    {
        $templateData = view('laravel-generator::api.swagger.endpoint', $this->var)->render();

        g_filesystem()->createFile($this->path . '/' . $this->endpointName, $templateData);

        $this->config->commandComment(infy_nl() . 'Swagger Class : ' . $this->endpointName);
        $this->config->commandInfo($this->endpointName);
    }

    private function generateProperties(array $fieldTypes): array
    {
        $properties = [];
        $rules = $this->generateValidateFields();
        $factories = $this->generateFactoryFields();
        foreach ($fieldTypes as $fieldType) {
            $fieldType['validateFields'] = $rules[$fieldType['fieldName']] ?? [];
            $fieldType['example'] = $factories[$fieldType['fieldName']] ?? '';
            $properties[] = view(
                'laravel-generator::api.swagger.properties',
                $fieldType
            )->render();
        }
        return $properties;
    }

    private function generateSchema()
    {
        $templateData = view('laravel-generator::api.swagger.schema', $this->var)->render();

        g_filesystem()->createFile($this->path . '/' . $this->schemaName, $templateData);

        $this->config->commandComment(infy_nl() . 'Swagger Class : ' . $this->schemaName);
        $this->config->commandInfo($this->schemaName);
    }

    private function generateValidateFields(): array
    {
        try {
            $controller = 'App\Http\Controllers\API\\' . $this->config->modelNames->name . 'APIController';
            $controllerReflectionMethod = new \ReflectionMethod($controller, 'store');
            $formRequestName = $controllerReflectionMethod->getParameters()[0]->getType();
            $formRequest = new \ReflectionClass($formRequestName->getName());
            $formRequest = $formRequest->newInstance();

            return $this->flattenRules($formRequest->rules());
        } catch (\ReflectionException $e) {
            return [];
        }
    }
}
