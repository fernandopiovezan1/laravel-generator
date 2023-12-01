@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $config->namespaces->serviceTests }};

use {{ $config->namespaces->model }}\{{ $config->modelNames->name }};
use {{ $config->namespaces->services }}\{{ $config->modelNames->name }}Service;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Request;
use {{ $config->namespaces->tests }}\ApiTestTrait;
use {{ $config->namespaces->tests }}\TestCase;

class {{ $config->modelNames->name }}ServiceTest extends TestCase
{
    use ApiTestTrait;
    use DatabaseTransactions;
    use DatabaseMigrations;

    protected {{ $config->modelNames->name }}Service ${{$config->modelNames->camel}}Service;

    public function setUp() : void
    {
        parent::setUp();
        $this->{{$config->modelNames->name}}Service = app({{ $config->modelNames->name }}Service::class);
    }

    /**
     * @test create
     */
    public function test_create_{{ $config->modelNames->snake }}_by_service()
    {
        $data = new Request({{ $config->modelNames->name }}::factory()->make()->toArray());

        $this->{{$config->modelNames->name}}Service->setRequest($data);
        $created{{ $config->modelNames->name }} = $this->{{$config->modelNames->name}}Service->create();

        $this->assertArrayHasKey('id', $created{{ $config->modelNames->name }});
        $this->assertNotNull($created{{ $config->modelNames->name }}['id'], 'Created {{ $config->modelNames->name }} must have id specified');
        $this->assertNotNull(
            {{ $config->modelNames->name }}::find(
                $created{{ $config->modelNames->name }}['id']), '{{ $config->modelNames->human }} with given id must be in DB');
        $this->assertModelData($data->all(), $created{{ $config->modelNames->name }});
    }

    /**
     * @test delete
     */
    public function test_delete_{{ $config->modelNames->snake }}_by_service()
    {
        $data = {{ $config->modelNames->name }}::factory()->create()->toArray();

        $this->{{$config->modelNames->name}}Service->setId($data['id']);
        $delete{{ $config->modelNames->name }} = $this->{{$config->modelNames->name}}Service->delete();

        $this->assertTrue($delete{{ $config->modelNames->name }}['code'] === 200);
        $this->assertNull({{ $config->modelNames->name }}::find($data['id']), '{{ $config->modelNames->name }} should not exist in DB');
    }

    /**
     * @test read all
     */
    public function test_read_all_{{ $config->modelNames->snake }}_by_service()
    {
        $data = {{ $config->modelNames->name }}::factory()->create();

        $req = new Request(['limit' => 1, 'direction' => 'desc']);
        $db{{ $config->modelNames->name }} = $this->{{$config->modelNames->name}}Service->search($req);

        $this->assertArrayHasKey('data', $db{{ $config->modelNames->name }});
        $this->assertModelData($db{{ $config->modelNames->name }}['data'][0], $data->toArray());
    }

    /**
     * @test read
     */
    public function test_read_{{ $config->modelNames->snake }}_by_service()
    {
        ${{ $config->modelNames->camel }} = {{ $config->modelNames->name }}::factory()->create()->toArray();

        $this->{{$config->modelNames->name}}Service->setId(${{ $config->modelNames->camel }}['id']);
        $db{{ $config->modelNames->name }} = $this->{{$config->modelNames->name}}Service->find();

        $db{{ $config->modelNames->name }} = $db{{ $config->modelNames->name }}->toArray();
        $this->assertModelData(${{ $config->modelNames->camel }}, $db{{ $config->modelNames->name }});
    }

    /**
     * @test update
     */
    public function test_update_{{ $config->modelNames->snake }}_by_service()
    {
        ${{ $config->modelNames->camel }} = {{ $config->modelNames->name }}::factory()->create()->toArray();
        $fake{{ $config->modelNames->name }} = new Request({{ $config->modelNames->name }}::factory()->make()->toArray());

        $this->{{$config->modelNames->name}}Service->setId(${{ $config->modelNames->camel }}['id']);
        $this->{{$config->modelNames->name}}Service->validId(${{ $config->modelNames->camel }}['id']);
        $this->{{$config->modelNames->name}}Service->setRequest($fake{{ $config->modelNames->name }});
        $updated{{ $config->modelNames->name }} = $this->{{$config->modelNames->name}}Service->update();

        $this->assertModelData($fake{{ $config->modelNames->name }}->all(), $updated{{ $config->modelNames->name }});
        $db{{ $config->modelNames->name }} = {{ $config->modelNames->name }}::find(${{ $config->modelNames->camel }}['id']);
        $this->assertModelData($fake{{ $config->modelNames->name }}->all(), $db{{ $config->modelNames->name }}->toArray());
    }
}