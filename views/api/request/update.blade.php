@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $config->namespaces->apiRequest }}\{{ $config->modelNames->name }};

use {{ $config->namespaces->model }}\{{ $config->modelNames->name }};
use App\Http\Requests\API\BaseAPIRequest;

class Update{{ $config->modelNames->name }}APIRequest extends BaseAPIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!$this->user()?->company_id) {
            return false;
        }
        return true;
    }

    /**
     * Configure the Model
     */
    public function model(): string
    {
        return {{ $config->modelNames->name }}::class;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return {{ $config->modelNames->name }}::$rules;
    }
}
