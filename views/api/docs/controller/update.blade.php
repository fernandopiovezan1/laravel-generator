/**
     * Update the specified {{ $config->modelNames->name }} in storage.
     * PUT/PATCH /{{ $config->modelNames->dashedPlural }}/{id}
     * @responseFile status=401 storage/response/error/401.json
     * @responseFile status=404 storage/response/error/404.json {"message": "{{ $config->modelNames->human }} not found"}
     */