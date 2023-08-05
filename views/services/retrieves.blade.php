@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $config->namespaces->services }}\{{ $config->modelNames->name }};

use {{ $config->namespaces->app }}\Repositories\{{ $config->modelNames->name }}Repository;
use Illuminate\Http\Request;

class Retrieves{{ $config->modelNames->plural }}Service
{
    protected int $id;

    public function __construct(private readonly {{ $config->modelNames->name }}Repository $repository)
    {}

    public function handle(Request $request): array
    {
        ${{ $config->modelNames->camel }} =  $this->repository->executeSearch($request);
        return ${{ $config->modelNames->camel }}->toArray();
    }
}
