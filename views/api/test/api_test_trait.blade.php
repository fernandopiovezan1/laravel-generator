@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $namespacesTests }};

trait ApiTestTrait
{
    private $response;

    private array $scapeValue = [
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'is_active',
        'deleted_by',
        'created_by',
        'updated_by'
    ];

    public function assertApiResponse(Array $actualData)
    {
        $this->assertApiSuccess();

        $response = json_decode($this->response->getContent(), true);
        $responseData = $response['data'];

        $this->assertNotEmpty($responseData['id']);
        $this->assertModelData($actualData, $responseData);
    }

    public function assertApiSuccess()
    {
        $this->response->assertStatus(200);
        $this->response->assertJson(['success' => true]);
    }

    public function assertModelData(Array $actualData, Array $expectedData)
    {
        foreach ($actualData as $key => $value) {
            if (in_array($key, $this->scapeValue)) {
                continue;
            }
            $this->assertEquals($actualData[$key], $expectedData[$key]);
        }
    }
}
