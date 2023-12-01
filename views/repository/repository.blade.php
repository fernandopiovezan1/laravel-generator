@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $config->namespaces->repository }};

use {{ $config->namespaces->model }}\{{ $config->modelNames->name }};

class {{ $config->modelNames->name }}Repository extends BaseRepository
{
    protected array $fieldSearchable = [
        {!! $fieldSearchable !!}
    ];

    /**
     * Return searchable fields
     * @return array
     */
    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     */
    public function model(): string
    {
        return {{ $config->modelNames->name }}::class;
    }
}
