@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $config->namespaces->serviceTests }}\{{ $config->modelNames->name }};

use {{ $config->namespaces->model }}\{{ $config->modelNames->name }};
use {{ $config->namespaces->services }}\{{ $config->modelNames->name }}\Update{{ $config->modelNames->name }}Service;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Vinkla\Hashids\Facades\Hashids;
use {{ $config->namespaces->tests }}\TestCase;
use {{ $config->namespaces->tests }}\ApiTestTrait;

class Update{{ $config->modelNames->name }}ServiceTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions, DatabaseMigrations;

    protected Update{{ $config->modelNames->name }}Service $updateService;

    public function setUp() : void
    {
        parent::setUp();
        $this->updateService = app(Update{{ $config->modelNames->name }}Service::class);
    }

    /**
     * @test update
     */
    public function test_update_{{ $config->modelNames->snake }}_by_service()
    {
        ${{ $config->modelNames->camel }} = {{ $config->modelNames->name }}::factory()->create()->toArray();
        $fake{{ $config->modelNames->name }} = {{ $config->modelNames->name }}::factory()->make()->toArray();

        $this->updateService->validId(${{ $config->modelNames->camel }}['id']);
        $this->updateService->setId(${{ $config->modelNames->camel }}['id']);
        $this->updateService->setData($fake{{ $config->modelNames->name }});
        $updated{{ $config->modelNames->name }} = $this->updateService->handle();

        $this->assertModelData($fake{{ $config->modelNames->name }}, $updated{{ $config->modelNames->name }});
        $db{{ $config->modelNames->name }} = {{ $config->modelNames->name }}::find((int)Hashids::connection('main')->decodeHex(${{ $config->modelNames->camel }}['id']));
        $this->assertModelData($fake{{ $config->modelNames->name }}, $db{{ $config->modelNames->name }}->toArray());
    }
}