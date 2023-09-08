@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $config->namespaces->apiController }};

use {{ $config->namespaces->apiRequest }}\{{ $config->modelNames->name }}\Create{{ $config->modelNames->name }}APIRequest;
use {{ $config->namespaces->apiRequest }}\{{ $config->modelNames->name }}\Update{{ $config->modelNames->name }}APIRequest;
use {{ $config->namespaces->model }}\{{ $config->modelNames->name }};
use {{ $config->namespaces->services }}\{{ $config->modelNames->name }}\Create{{ $config->modelNames->name }}Service;
use {{ $config->namespaces->services }}\{{ $config->modelNames->name }}\Delete{{ $config->modelNames->name }}Service;
use {{ $config->namespaces->services }}\{{ $config->modelNames->name }}\Retrieve{{ $config->modelNames->name }}Service;
use {{ $config->namespaces->services }}\{{ $config->modelNames->name }}\Retrieves{{ $config->modelNames->plural }}Service;
use {{ $config->namespaces->services }}\{{ $config->modelNames->name }}\Update{{ $config->modelNames->name }}Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use {{ $config->namespaces->app }}\Http\Controllers\AppBaseController;

{!! $docController !!}
class {{ $config->modelNames->name }}Controller extends AppBaseController
{

    public function __construct(
        private readonly Create{{ $config->modelNames->name }}Service $createService,
        private readonly Delete{{ $config->modelNames->name }}Service $deleteService,
        private readonly Retrieve{{ $config->modelNames->name }}Service $retrieveService,
        private readonly Retrieves{{ $config->modelNames->plural }}Service $retrievesService,
        private readonly Update{{ $config->modelNames->name }}Service $updateService,
    )
    {}

    {!! $docIndex !!}
    public function index(Request $request): JsonResponse
    {
        $data = $this->retrievesService->handle($request);

        return $this->sendResponse(
            $data,
            __('messages.retrieved', ['model' => __('models/{{ $config->modelNames->camelPlural }}.plural')])
        );
    }

    {!! $docStore !!}
    public function store(Create{{ $config->modelNames->name }}APIRequest $request): JsonResponse
    {
        $this->createService->setData($request->all());

        ${{ $config->modelNames->camel }} = $this->createService->handle();

        return $this->sendResponse(
            ${{ $config->modelNames->camel }},
            __('messages.saved', ['model' => __('models/{{ $config->modelNames->camelPlural }}.singular')])
        );
    }

    {!! $docShow !!}
    public function show(string $id): JsonResponse
    {
        $this->retrieveService->setId($id);

        /** @var {{ $config->modelNames->name }} ${{ $config->modelNames->camel }} */
        ${{ $config->modelNames->camel }} = $this->retrieveService->handle();

        if (empty(${{ $config->modelNames->camel }})) {
            return $this->sendError(
                __('messages.not_found', ['model' => __('models/{{ $config->modelNames->camelPlural }}.singular')])
            );
        }
        return $this->sendResponse(
            ${{ $config->modelNames->camel }}->toArray(),
            __('messages.retrieved', ['model' => __('models/{{ $config->modelNames->camelPlural }}.singular')])
        );
    }

    {!! $docUpdate !!}
    public function update(string $id, Update{{ $config->modelNames->name }}APIRequest $request): JsonResponse
    {
        /** @var {{ $config->modelNames->name }} ${{ $config->modelNames->camel }} */
        ${{ $config->modelNames->camel }} = $this->updateService->validId($id);

        if (empty(${{ $config->modelNames->camel }})) {
            return $this->sendError(
                __('messages.not_found', ['model' => __('models/{{ $config->modelNames->camelPlural }}.singular')])
            );
        }

        $this->updateService->setId($id);
        $this->updateService->setData($request->all());
        ${{ $config->modelNames->camel }} = $this->updateService->handle();

        return $this->sendResponse(
            ${{ $config->modelNames->camel }},
            __('messages.updated', ['model' => __('models/{{ $config->modelNames->camelPlural }}.singular')])
        );
    }

    {!! $docDestroy !!}
    public function destroy(string $id): JsonResponse
    {
        $this->deleteService->setId($id);

        return $this->response($this->deleteService->handle());
    }
}
