@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $config->namespaces->services }}\{{ $config->modelNames->name }};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;


use {{ $config->namespaces->app }}\Repositories\{{ $config->modelNames->name }}Repository;

class Retrieve{{ $config->modelNames->name }}Service
{
    protected int $id;

    public function __construct(private readonly {{ $config->modelNames->name }}Repository $repository)
    {}

    /**
    * Set Id for model search
    *
    */
    public function setId(string $id): void
    {
        $this->id = (int)Hashids::connection('main')->decodeHex($id);
    }

    /**
    * Execute search in model
    *
    */
    public function handle(): Builder|Collection|Model|null
    {
        return $this->repository->find($this->id);
    }
}
