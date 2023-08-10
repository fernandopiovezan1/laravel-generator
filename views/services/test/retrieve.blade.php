@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $config->namespaces->serviceTests }}\{{ $config->modelNames->name }};

use {{ $config->namespaces->model }}\{{ $config->modelNames->name }};
use {{ $config->namespaces->services }}\{{ $config->modelNames->name }}\Retrieve{{ $config->modelNames->name }}Service;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use {{ $config->namespaces->tests }}\TestCase;
use {{ $config->namespaces->tests }}\ApiTestTrait;

class Retrieve{{ $config->modelNames->name }}ServiceTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions, DatabaseMigrations;

    protected Retrieve{{ $config->modelNames->name }}Service $retrieveService;

    public function setUp() : void
    {
        parent::setUp();
        $this->retrieveService = app(Retrieve{{ $config->modelNames->name }}Service::class);
    }

    /**
     * @test read
     */
    public function test_read_{{ $config->modelNames->snake }}_by_service()
    {
        ${{ $config->modelNames->camel }} = {{ $config->modelNames->name }}::factory()->create()->toArray();

        $this->retrieveService->setId(${{ $config->modelNames->camel }}['id']);
        $db{{ $config->modelNames->name }} = $this->retrieveService->handle();

        $db{{ $config->modelNames->name }} = $db{{ $config->modelNames->name }}->toArray();
        $this->assertModelData(${{ $config->modelNames->camel }}, $db{{ $config->modelNames->name }});
    }
}