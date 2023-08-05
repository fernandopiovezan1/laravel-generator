@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $config->namespaces->services }}\{{ $config->modelNames->name }};

use {{ $config->namespaces->app }}\Repositories\{{ $config->modelNames->name }}Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;

class Update{{ $config->modelNames->name }}Service
{
    protected array $data;
    protected int $id;

    public function __construct(private readonly {{ $config->modelNames->name }}Repository $repository)
    {}

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function setId(string $id): void
    {
        $this->id = (int)Hashids::connection('main')->decodeHex($id);
    }

    public function validId(string $id): Builder|Collection|Model|null
    {
        return $this->repository->find((int)Hashids::connection('main')->decodeHex($id));
    }

    public function handle(): array
    {
        return $this->repository->update($this->data, $this->id);
    }
}
