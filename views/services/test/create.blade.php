@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $config->namespaces->serviceTests }}\{{ $config->modelNames->name }};

use {{ $config->namespaces->model }}\{{ $config->modelNames->name }};
use {{ $config->namespaces->services }}\{{ $config->modelNames->name }}\Create{{ $config->modelNames->name }}Service;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Vinkla\Hashids\Facades\Hashids;
use {{ $config->namespaces->tests }}\TestCase;
use {{ $config->namespaces->tests }}\ApiTestTrait;

class Create{{ $config->modelNames->name }}ServiceTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions, DatabaseMigrations;

    protected Create{{ $config->modelNames->name }}Service $createService;

    public function setUp() : void
    {
        parent::setUp();
        $this->createService = app(Create{{ $config->modelNames->name }}Service::class);
    }

    /**
     * @test create
     */
    public function test_create_{{ $config->modelNames->snake }}_by_service()
    {
        $data = {{ $config->modelNames->name }}::factory()->make()->toArray();

        $this->createService->setData($data);
        $created{{ $config->modelNames->name }} = $this->createService->handle();

        $this->assertArrayHasKey('id', $created{{ $config->modelNames->name }});
        $this->assertNotNull($created{{ $config->modelNames->name }}['id'], 'Created {{ $config->modelNames->name }} must have id specified');
        $this->assertNotNull(
            Product::find(
                (int)Hashids::connection('main')->decodeHex($created{{ $config->modelNames->name }}['id'])
                ), '{{ $config->modelNames->human }} with given id must be in DB');
        $this->assertModelData($data, $created{{ $config->modelNames->name }});
    }
}