@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $config->namespaces->apiController }};

use {{ $config->namespaces->apiRequest }}\Create{{ $config->modelNames->name }}APIRequest;
use {{ $config->namespaces->apiRequest }}\Update{{ $config->modelNames->name }}APIRequest;
use {{ $config->namespaces->model }}\{{ $config->modelNames->name }};
use {{ $config->namespaces->repository }}\{{ $config->modelNames->name }}Repository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use {{ $config->namespaces->app }}\Http\Controllers\AppBaseController;

{!! $docController !!}
class {{ $config->modelNames->name }}APIController extends AppBaseController
{
    private {{ $config->modelNames->name }}Repository ${{ $config->modelNames->camel }}Repository;

    public function __construct({{ $config->modelNames->name }}Repository ${{ $config->modelNames->camel }}Repo)
    {
        $this->{{ $config->modelNames->camel }}Repository = ${{ $config->modelNames->camel }}Repo;
    }

    {!! $docIndex !!}
    public function index(Request $request): JsonResponse
    {
        if ($request->exists('search')) {
            ${{ $config->modelNames->camelPlural }} = $this->{{ $config->modelNames->camel }}Repository
                ->advancedSearch($request)
                ->orderByRaw('deleted_at asc,' . ($request->get('order') ?? 'id') .
                             ' ' . ($request->get('direction') ?? 'DESC'))
                ->paginate($request->get('limit'));
        } else {
            ${{ $config->modelNames->camelPlural }} = $this->{{ $config->modelNames->camel }}Repository
                ->findAllFieldsAnd($request)
                ->orderByRaw('deleted_at asc,' . ($request->get('order') ?? 'id') .
                             ' ' . ($request->get('direction') ?? 'DESC'))
                ->paginate($request->get('limit'));
        }

        return $this->sendResponse(
            ${{ $config->modelNames->camelPlural }}->toArray(),
            __('messages.retrieved', ['model' => __('models/{{ $config->modelNames->camelPlural }}.plural')])
        );
    }

    {!! $docStore !!}
    public function store(Create{{ $config->modelNames->name }}APIRequest $request): JsonResponse
    {
        $input = $request->all();

        ${{ $config->modelNames->camel }} = $this->{{ $config->modelNames->camel }}Repository->create($input);

        return $this->sendResponse(
            ${{ $config->modelNames->camel }}->toArray(),
            __('messages.saved', ['model' => __('models/{{ $config->modelNames->camelPlural }}.singular')])
        );
    }

    {!! $docShow !!}
    public function show($id): JsonResponse
    {
        if (!is_numeric($id)) {
            return $this->sendError('Parâmetro incorreto');
        }

        /** @var {{ $config->modelNames->name }} ${{ $config->modelNames->camel }} */
        ${{ $config->modelNames->camel }} = $this->{{ $config->modelNames->camel }}Repository->find($id);

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
    public function update($id, Update{{ $config->modelNames->name }}APIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var {{ $config->modelNames->name }} ${{ $config->modelNames->camel }} */
        ${{ $config->modelNames->camel }} = $this->{{ $config->modelNames->camel }}Repository->find($id);

        if (empty(${{ $config->modelNames->camel }})) {
            return $this->sendError(
                __('messages.not_found', ['model' => __('models/{{ $config->modelNames->camelPlural }}.singular')])
            );
        }

        ${{ $config->modelNames->camel }} = $this->{{ $config->modelNames->camel }}Repository->update($input, $id);

        return $this->sendResponse(
            ${{ $config->modelNames->camel }},
            __('messages.updated', ['model' => __('models/{{ $config->modelNames->camelPlural }}.singular')])
        );
    }

    {!! $docDestroy !!}
    public function destroy($id): JsonResponse
    {
        return $this->response($this->{{ $config->modelNames->camel }}Repository->deleteOrUndelete($id));
    }
}
