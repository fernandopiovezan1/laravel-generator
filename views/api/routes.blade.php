Route::resource('{{ $config->prefixes->getRoutePrefixWith('/') }}{{ $config->modelNames->dashedPlural }}', {{ $config->namespaces->apiController }}\{{ $config->modelNames->name }}APIController::class){!! infy_nl_tab() !!}->except(['create', 'edit'])@if(!$config->prefixes->route);@endif
@if($config->prefixes->route){!! infy_nl_tab().'->names(['.infy_nl_tab(1,2).implode(','.infy_nl_tab(1, 2), create_resource_route_names($config->prefixes->getRoutePrefixWith('.').$config->modelNames->camelPlural)).infy_nl_tab().']);' !!}@endif
