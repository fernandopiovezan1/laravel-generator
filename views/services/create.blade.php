@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $config->namespaces->services }}\{{ $config->modelNames->name }};

use {{ $config->namespaces->app }}\Repositories\{{ $config->modelNames->name }}Repository;

class Create{{ $config->modelNames->name }}Service
{
    protected array $data;

    public function __construct(private readonly {{ $config->modelNames->name }}Repository $repository)
    {}

    /**
     * Set data value to Model 
     * 
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * Execute create in repository  
     * 
     */
    public function handle(): array
    {
        ${{ $config->modelNames->camel }} = $this->repository->create($this->data);
        return ${{ $config->modelNames->camel }}->toArray();
    }
}
