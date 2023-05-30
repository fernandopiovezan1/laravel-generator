/**
     * Display the specified {{ $config->modelNames->name }}.
     * GET|HEAD /{{ $config->modelNames->dashedPlural }}/{id}
     * @urlParam id integer Identificador do registro.
     * @responseFile status=401 storage/response/error/401.json
     * @responseFile status=404 storage/response/error/404.json {"message": "{{ $config->modelNames->human }} not found"}
     */