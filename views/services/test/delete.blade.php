@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $config->namespaces->serviceTests }}\{{ $config->modelNames->name }};

use {{ $config->namespaces->model }}\{{ $config->modelNames->name }};
use {{ $config->namespaces->services }}\{{ $config->modelNames->name }}\Delete{{ $config->modelNames->name }}Service;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use {{ $config->namespaces->tests }}\TestCase;
use {{ $config->namespaces->tests }}\ApiTestTrait;

class Delete{{ $config->modelNames->name }}ServiceTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions, DatabaseMigrations;

    protected Delete{{ $config->modelNames->name }}Service $deleteService;

    public function setUp() : void
    {
        parent::setUp();
        $this->deleteService = app(Delete{{ $config->modelNames->name }}Service::class);
    }

    /**
     * @test delete
     */
    public function test_delete_{{ $config->modelNames->snake }}_by_service()
    {
        $data = {{ $config->modelNames->name }}::factory()->create()->toArray();

        $this->deleteService->setId($data['id']);
        $delete{{ $config->modelNames->name }} = $this->deleteService->handle();

        $this->assertTrue($delete{{ $config->modelNames->name }}['code'] === 200);
        $this->assertNull({{ $config->modelNames->name }}::find($data['id']), '{{ $config->modelNames->name }} should not exist in DB');
    }
}