@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $config->namespaces->apiRequest }}\{{ $config->modelNames->name }};

use InfyOm\Generator\Request\APIRequest;

class Create{{ $config->modelNames->name }}APIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): bool
    {
        return [
            {!! $rules !!}
        ];
    }

    public static function bodyParameters(): array
    {
        return [
            {!! $bodyParameters !!}
        ];
    }
}
