/**
    * Remove the specified {{ $config->modelNames->name }} from storage.
    * DELETE /{{ $config->modelNames->dashedPlural }}/{id}
    * @throws \Exception
    * @urlParam id integer required Identificador do registro.
    * @responseFile status=401 storage/response/error/401.json
    * @responseFile status=404 storage/response/error/404.json {"message": "{{ $config->modelNames->human }} not found"}
    */
