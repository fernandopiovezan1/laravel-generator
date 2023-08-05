@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $config->namespaces->serviceTests }}\{{ $config->modelNames->name }};

use {{ $config->namespaces->model }}\{{ $config->modelNames->name }};
use {{ $config->namespaces->services }}\{{ $config->modelNames->name }}\Retrieves{{ $config->modelNames->plural }}Service;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Request;
use {{ $config->namespaces->tests }}\TestCase;
use {{ $config->namespaces->tests }}\ApiTestTrait;

class Retrieves{{ $config->modelNames->plural }}ServiceTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions, DatabaseMigrations;

    protected Retrieves{{ $config->modelNames->plural }}Service $retrievesService;

    public function setUp() : void
    {
        parent::setUp();
        $this->retrievesService = app(Retrieves{{ $config->modelNames->plural }}Service::class);
    }

    /**
     * @test read all
     */
    public function test_read_all_{{ $config->modelNames->snake }}_by_service()
    {
        {{ $config->modelNames->name }}::factory()->create();

        $req = new Request(['limit' => 1]);
        $db{{ $config->modelNames->name }} = $this->retrievesService->handle($req);

        $this->assertArrayHasKey('data', $db{{ $config->modelNames->name }});
    }
}