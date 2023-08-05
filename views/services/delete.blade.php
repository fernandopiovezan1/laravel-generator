@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $config->namespaces->services }}\{{ $config->modelNames->name }};

use {{ $config->namespaces->app }}\Repositories\{{ $config->modelNames->name }}Repository;
use Vinkla\Hashids\Facades\Hashids;

class Delete{{ $config->modelNames->name }}Service
{
    protected int $id;

    public function __construct(private readonly {{ $config->modelNames->name }}Repository $repository)
    {}

    public function setId(string $id): void
    {
        $this->id = (int)Hashids::connection('main')->decodeHex($id);
    }

    public function handle(): array
    {
        return $this->repository->deleteOrUndelete($this->id);
    }
}
