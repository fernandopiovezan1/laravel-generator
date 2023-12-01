/**
     * Display a listing of the {{ $config->modelNames->plural }}.
     * @authenticated
     * @queryParam limit integer Quantidade de registros retornado na consulta. Exemplo 15 No-example
     * @queryParam page integer Página a ser exibida na consulta. Exemplo 1 No-example
     * @queryParam order string Campo para ordenação do retorno. Exemplo name No-example
     * @queryParam fields string Informe a seleção de campos que devem retornar da
     *  consulta separados por virgula. Exemplo id, name No-example
     * @queryParam search string Pesquise por qualquer campo, ao usar este campo as
     *  outras consultas serão desconsideradas. Este campo usa uma consulta OR em todos
     *  os campos da tabela e das relações, portanto pode ser uma busca lenta em sua execução No-example
     * @queryParam created_by[] string[] Pesquise pela coluna da tabela relacionada. Exemplo tabela[coluna]. No-example
     * @queryParam updated_by[?] string[] Pesquise pela coluna da tabela relacionada. Exemplo tabela[coluna]. No-example
     * @queryParam start_created_at string Busca por data inicial de criação. Se enviado sozinho faz busca exata.
     * Exemplo 2021-01-30 No-example
     * @queryParam end_created_at string Busca por data final de criação, quando combinada com o start_created_at
     *  é efetuada uma busca com Between. Exemplo 2021-01-30 No-example
     * @queryParam start_updated_at string Busca por data inicial de criação. Se enviado sozinho faz busca exata.
     * Exemplo 2021-01-30 No-example
     * @queryParam end_updated_at string Busca por data final de criação, quando combinada com o start_created_at
     *  é efetuada uma busca com Between. Exemplo 2021-01-30 No-example
     * @queryParam hide_relation string Informe o nome da relação que deverá ser ocultada na consulta. No-Example
     * @responseFile status=401 storage/response/error/401.json
     */