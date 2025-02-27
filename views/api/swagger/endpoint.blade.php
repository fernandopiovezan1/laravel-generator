@php
    echo "<?php".PHP_EOL;
@endphp

{{'namespace App\Swagger;'}}

/**
 * @OA\Get(
 *     path="/api/{{ $config->modelNames->camelPlural }}",
 *     summary="Listagem de {{ $translateModelPlural }}",
 *     tags={"{{ $config->modelNames->name }}"},
@verbatim *     security={{"bearerAuth": {}}}, @endverbatim
 *     @OA\Parameter(ref="#/components/parameters/search_limit"),
 *     @OA\Parameter(ref="#/components/parameters/search_page"),
 *     @OA\Parameter(ref="#/components/parameters/search_order"),
 *     @OA\Parameter(ref="#/components/parameters/search_fields"),
 *     @OA\Parameter(ref="#/components/parameters/search_search"),
 *     @OA\Parameter(ref="#/components/parameters/search_created_by"),
 *     @OA\Parameter(ref="#/components/parameters/search_updated_by"),
 *     @OA\Parameter(ref="#/components/parameters/search_start_created_at"),
 *     @OA\Parameter(ref="#/components/parameters/search_end_created_at"),
 *     @OA\Parameter(ref="#/components/parameters/search_start_updated_at"),
 *     @OA\Parameter(ref="#/components/parameters/search_end_updated_at"),
 *     @OA\Response(
 *         response=200,
 *         description="Listagem de {{ $translateModelPlural }}. Nota: 'attachments' e 'icon' são mutuamente exclusivos; apenas um estará presente por registro.",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/{{ $config->modelNames->name }}Response")),
 *                 @OA\Property(property="from", type="integer", example=1),
 *                 @OA\Property(property="last_page", type="integer", example=1),
 *                 @OA\Property(property="per_page", type="integer", example=15),
 *                 @OA\Property(property="to", type="integer", example=1),
 *                 @OA\Property(property="total", type="integer", example=1)
 *              ),
 *              @OA\Property(property="message", type="string", example="{{ $translateModelPlural }} recuperado com sucesso.")
 *          )
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/{{ $config->modelNames->camelPlural }}",
 *     summary="Cria um novo registro de {{ $translateModel }}",
 *     tags={"{{ $config->modelNames->name }}"},
@verbatim *     security={{"bearerAuth": {}}}, @endverbatim
 *     @OA\RequestBody(
 *         required=true,
 *         description="Dados para criar um {{ $translateModel }}. Nota: 'attachments' e 'icon' são mutuamente exclusivos; apenas um estará presente por registro.",
 *         @OA\JsonContent(ref="#/components/schemas/{{ $config->modelNames->name }}Request")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="{{ $translateModel }} criado com sucesso",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="data", ref="#/components/schemas/{{ $config->modelNames->name }}Response"),
 *             @OA\Property(property="message", type="string", example="{{ $translateModel }} salvo com sucesso")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Erro de validação",
 *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
 *     )
 * )
 *
 * @OA\Put(
 *     path="/api/{{ $config->modelNames->camelPlural }}/{id}",
 *     summary="Atualiza um registro específico de {{ $translateModel }}",
 *     tags={"{{ $config->modelNames->name }}"},
@verbatim *     security={{"bearerAuth": {}}}, @endverbatim
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID do {{ $translateModel }} a ser atualizado",
 *         @OA\Schema(type="string", example="EJXDN24Oz1BkK")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Dados para atualizar o {{ $translateModel }}. Nota: 'attachments' e 'icon' são mutuamente exclusivos; apenas um estará presente por registro.",
 *         @OA\JsonContent(ref="#/components/schemas/{{ $config->modelNames->name }}Request")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="{{ $translateModel }} atualizado com sucesso",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="data", ref="#/components/schemas/{{ $config->modelNames->name }}Response"),
 *             @OA\Property(property="message", type="string", example="{{ $translateModel }} atualizado com sucesso")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Erro de validação",
 *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="{{ $translateModel }} não encontrado",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Registro não encontrado para {{ $translateModel }}")
 *         )
 *     )
 * )
 *
 * @OA\Delete(
 *     path="/api/{{ $config->modelNames->camelPlural }}/{id}",
 *     summary="Remove um registro específico de {{ $translateModel }}",
 *     tags={"{{ $config->modelNames->name }}"},
@verbatim *     security={{"bearerAuth": {}}}, @endverbatim
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID do {{ $translateModel }} a ser excluído",
 *         @OA\Schema(type="string", example="EJXDN24Oz1BkKD")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="{{ $translateModel }} excluído com sucesso",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="data", type="array", @OA\Items(), example="[]"),
 *             @OA\Property(property="message", type="string", example="{{ $translateModel }} excluído com sucesso.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="{{ $translateModel }} não encontrado",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="{{ $translateModel }} não encontrado")
 *         )
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/{{ $config->modelNames->camelPlural }}/{id}",
 *     summary="Exibe um registro específico de {{ $translateModel }}",
 *     tags={"{{ $config->modelNames->name }}"},
@verbatim *     security={{"bearerAuth": {}}}, @endverbatim
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID do {{ $translateModel }} a ser buscado",
 *         @OA\Schema(type="string", example="EJXDN24Oz1BkKDQp")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="{{ $translateModel }} recuperado com sucesso",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="data", ref="#/components/schemas/{{ $config->modelNames->name }}Response"),
 *             @OA\Property(property="message", type="string", example="{{ $translateModel }} recuperado com sucesso")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="{{ $translateModel }} não encontrado",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Registro não encontrado para {{ $translateModel }}")
 *         )
 *     )
 * )
 */

class {{ $config->modelNames->name }}Endpoint
{
}
