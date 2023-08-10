@php
    echo "<?php".PHP_EOL;
@endphp

{{'namespace App\Models;'}}

@if($config->options->softDelete){{'use App\Traits\CustomSoftDelete;' }}@nls(1)@endif
@if($config->options->tests or $config->options->factory){{'use Illuminate\Database\Eloquent\Factories\HasFactory;' }}@nls(1)@endif
{{'use Rennokki\QueryCache\Traits\QueryCacheable;'}}
{{'use Illuminate\Database\Eloquent\Casts\Attribute;'}}
{{'use Vinkla\Hashids\Facades\Hashids;'}}

@if(isset($swaggerDocs)){!! $swaggerDocs  !!}@endif
class {{ $config->modelNames->name }} extends BaseModel
{
@if($config->options->softDelete) {{ infy_tab(3).'use CustomSoftDelete;' }}@nls(1)@endif
@if($config->options->tests or $config->options->factory){{infy_tab(3).'use HasFactory;' }}@nls(1)@endif
{{ infy_tab(4).'use QueryCacheable;' }}

    public $table = '{{ $config->tableName }}';
@if($customPrimaryKey)@tab()protected $primaryKey = '{{ $customPrimaryKey }}';@nls(2)@endif
@if($config->connection)@tab()protected $connection = '{{ $config->connection }}';@nls(2)@endif
@if(!$timestamps)@tab()public $timestamps = false;@nls(2)@endif
@if($customSoftDelete)@tab()protected $dates = ['{{ $customSoftDelete }}'];@nls(2)@endif
@if($customCreatedAt)@tab()const CREATED_AT = '{{ $customCreatedAt }}';@nls(2)@endif
@if($customUpdatedAt)@tab()const UPDATED_AT = '{{ $customUpdatedAt }}';@nls(2)@endif
    /**
     * Time in seconds to live Cache
     */
    public int $cacheFor = 3600;

    /**
     * The attributes that are mass assignable.
     * @var array<string>
     */
    public $fillable = [
        {!! $fillables !!}
    ];

    /**
     * The attributes that should be casted to native types.
     * @var array
     */
    protected $casts = [
        {!! $casts !!}
    ];

    /**
     * Invalidate the cache automatically
     * upon update in the database.
     */
    protected static bool $flushCacheOnUpdate = true;

    /**
     * Check if the model uses the company id field
     *
     */
    protected bool $hasCompanyId = false;

    /**
     * The attributes that should be hidden for serialization.
     * @var array
     */
    protected $hidden = [
        'deleted_by',
        'deleted_at'
    ];

    /**
     * Responsible for determining which relationships
     * will be used in queries
     */
    protected array $relationsBySearch = [
        'createdBy',
        'updatedBy'
    ];

    /**
     * Responsible for bringing the assembled
     * relationships without the need for a call
     */
    protected $with = [
        'createdBy',
        'deletedBy',
        'updatedBy',
    ];

    /**
     * The validation rules.
     */
    public static array $rules = [
        {!! $rules !!}
    ];

    {!! $relations !!}
}
