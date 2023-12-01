@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $config->namespaces->services }};

use {{ $config->namespaces->app }}\Repositories\{{ $config->modelNames->name }}Repository;
use App\Traits\CodeDecodeId;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class {{ $config->modelNames->name }}Service
{
    use CodeDecodeId;

    protected Request $request;

    public function __construct(private readonly {{ $config->modelNames->name }}Repository ${{$config->modelNames->camel}}Repository)
    {
    }

    /**
     * Call repository to create one record from data property
     */
    public function create(): array
    {
        ${{$config->modelNames->camel}} = $this->{{$config->modelNames->camel}}Repository->create($this->request->all());
        return ${{$config->modelNames->camel}}->toArray();
    }

    /**
     * Call repository to deactivate or activate record in database
     */
    public function delete(): array
    {
        return $this->{{$config->modelNames->camel}}Repository->deleteOrUndelete($this->id);
    }

    /**
     * Call repository to find a record by id
     */
    public function find(): Builder|Collection|Model|null
    {
        return $this->{{$config->modelNames->camel}}Repository->find($this->id);
    }

    /**
     * Call repository to find a record according to param of search
     */
    public function search(Request $request): array
    {
        $company = $this->{{$config->modelNames->camel}}Repository->executeSearch($request);
        return $company->toArray();
    }

    /**
     * Set property request to use in methods inside class
     */
    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    /**
     * Call repository to update record according id
     */
    public function update(): array
    {
        return $this->{{$config->modelNames->camel}}Repository->update($this->request->all(), $this->id);
    }

    /**
     * Validate if parameter id exist in database
     */
    public function validId(): Builder|Collection|Model|null
    {
        return $this->{{$config->modelNames->camel}}Repository->find($this->id);
    }
}
