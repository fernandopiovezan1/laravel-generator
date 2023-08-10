@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $config->namespaces->repositoryTests }};

use {{ $config->namespaces->model }}\{{ $config->modelNames->name }};
use {{ $config->namespaces->repository }}\{{ $config->modelNames->name }}Repository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Vinkla\Hashids\Facades\Hashids;
use {{ $config->namespaces->tests }}\TestCase;
use {{ $config->namespaces->tests }}\ApiTestTrait;

class {{ $config->modelNames->name }}RepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions, DatabaseMigrations;

    protected {{ $config->modelNames->name }}Repository ${{ $config->modelNames->camel }}Repo;

    public function setUp() : void
    {
        parent::setUp();
        $this->{{ $config->modelNames->camel }}Repo = app({{ $config->modelNames->name }}Repository::class);
    }

    /**
     * @test create
     */
    public function test_create_{{ $config->modelNames->snake }}()
    {
        ${{ $config->modelNames->camel }} = {{ $config->modelNames->name }}::factory()->make()->toArray();

        $created{{ $config->modelNames->name }} = $this->{{ $config->modelNames->camel }}Repo->create(${{ $config->modelNames->camel }});

        $created{{ $config->modelNames->name }} = $created{{ $config->modelNames->name }}->toArray();
        $this->assertArrayHasKey('id', $created{{ $config->modelNames->name }});
        $this->assertNotNull(
            {{ $config->modelNames->name }}::find(
                (int)Hashids::connection('main')->decodeHex($created{{ $config->modelNames->name }}['id'])
                ), '{{ $config->modelNames->human }} with given id must be in DB');
        $this->assertModelData(${{ $config->modelNames->camel }}, $created{{ $config->modelNames->name }});
    }

    /**
     * @test read
     */
    public function test_read_{{ $config->modelNames->snake }}()
    {
        ${{ $config->modelNames->camel }} = {{ $config->modelNames->name }}::factory()->create()->toArray();

        $db{{ $config->modelNames->name }} = $this->{{ $config->modelNames->camel }}Repo->find((int)Hashids::connection('main')->decodeHex(${{ $config->modelNames->camel }}['{{ $config->primaryName }}']));

        $db{{ $config->modelNames->name }} = $db{{ $config->modelNames->name }}->toArray();
        $this->assertModelData(${{ $config->modelNames->camel }}, $db{{ $config->modelNames->name }});
    }

    /**
     * @test update
     */
    public function test_update_{{ $config->modelNames->snake }}()
    {
        ${{ $config->modelNames->camel }} = {{ $config->modelNames->name }}::factory()->create()->toArray();
        $fake{{ $config->modelNames->name }} = {{ $config->modelNames->name }}::factory()->make()->toArray();

        $updated{{ $config->modelNames->name }} = $this->{{ $config->modelNames->camel }}Repo->update($fake{{ $config->modelNames->name }}, (int)Hashids::connection('main')->decodeHex(${{ $config->modelNames->camel }}['{{ $config->primaryName }}']));

        $this->assertModelData($fake{{ $config->modelNames->name }}, $updated{{ $config->modelNames->name }});
        $db{{ $config->modelNames->name }} = $this->{{ $config->modelNames->camel }}Repo->find((int)Hashids::connection('main')->decodeHex(${{ $config->modelNames->camel }}['{{ $config->primaryName }}']));
        $this->assertModelData($fake{{ $config->modelNames->name }}, $db{{ $config->modelNames->name }}->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_{{ $config->modelNames->snake }}()
    {
        ${{ $config->modelNames->camel }} = {{ $config->modelNames->name }}::factory()->create()->toArray();

        $resp = $this->{{ $config->modelNames->camel }}Repo->delete((int)Hashids::connection('main')->decodeHex(${{ $config->modelNames->camel }}['{{ $config->primaryName }}']));

        $this->assertTrue($resp);
        $this->assertNull({{ $config->modelNames->name }}::find((int)Hashids::connection('main')->decodeHex(${{ $config->modelNames->camel }}['{{ $config->primaryName }}'])), '{{ $config->modelNames->name }} should not exist in DB');
    }
}
