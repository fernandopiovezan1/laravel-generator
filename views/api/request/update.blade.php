@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $config->namespaces->apiRequest }}\{{ $config->modelNames->name }};

use InfyOm\Generator\Request\APIRequest;

class Update{{ $config->modelNames->name }}APIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
    * Provides a detailed description of the expected parameters
    * in the body of an HTTP request.
    */
    public static function bodyParameters(): array
    {
        return [
            {!! $bodyParameters !!}
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            {!! $rules !!}
        ];
    }
}
