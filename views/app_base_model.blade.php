@php
    echo "<?php".PHP_EOL;
@endphp

namespace App\Models;

use App\Traits\CustomSoftDelete;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Vinkla\Hashids\Facades\Hashids;

/**
 * @property array $fieldDescription
 */
abstract class BaseModel extends Model
{
    use CustomSoftDelete;
    use HasFactory;

    /**
     * Add the is_active field to make it easier to validate it on the front
     */
    protected $appends = [
        'is_active',
    ];

    /**
     * Fields description from database
     */
    protected array $fieldDescription;

    /**
     * This attribute checks if the table is multi tenancy
     */
    protected bool $hasCompanyId = true;

    /**
     * Informs which fields should not be saved in uppercase if the trait is used
     */
    protected array $noUpper = [];

    /**
     * Informs which relations should be used in the search
     */
    protected array $relationsBySearch = [];

    /**
     * Returns the field types to be used in queries
     */
    public static function getCastsStatic(): array
    {
        return (new static())->getCasts();
    }

    /**
     * Return fields description from database
     */
    public static function getFieldDescription(): array
    {
        return (new static())->fieldDescription;
    }

    /**
     * Function responsible for returning the type of the field for the query
     */
    public static function getFieldType(string $field): string
    {
        if (array_key_exists($field, static::getCastsStatic())) {
            return (new static())->getCastType($field);
        }
        return false;
    }

    /**
     * Method to return the relationships that can be queried
     */
    public function getRelationsBySearch(): array
    {
        return $this->relationsBySearch;
    }

    /**
     * Returns if the company ID is used in the model
     */
    public function hasCompanyId(): bool
    {
        return $this->hasCompanyId;
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\Relation  $query
     * @param  mixed  $value
     * @param  string|null  $field
     */
    public function resolveRouteBindingQuery($query, $value, $field = null): Builder|Relation
    {
        if (!is_numeric($value)) {
            $id = (int) Hashids::connection('main')->decodeHex($value);
        } else {
            $id = $value;
        }
        if (Request::method() === 'DELETE') {
            return $query->where($field ?? $this->getRouteKeyName(), $id)->withTrashed();
        }
        return $query->where($field ?? $this->getRouteKeyName(), $id);
    }

    /**
     * Function responsible for writing log where defined
     */
    public function saveLog(Model $model, string $event): array
    {
        if ($event == 'saving' && $model->exists) {
            $log = array_diff_assoc($model->getAttributes(), $model->getOriginal());
        } elseif ($event == 'deleting' && !$model->exists) {
            $log = $model->getAttributes();
        } else {
            $log = $model->getAttributes();
        }
        return $log;
    }

    /**
     * Convert the model instance to an array.
     */
    public function toArray(): array
    {
        $array = parent::toArray();
        if (isset($array['id']) && getenv('USE_HASH')) {
            $array['id'] = Hashids::connection('main')->encodeHex($array['id']);
        }
        return $array;
    }

    /**
     * Bootstrap the model and its traits
     */
    protected static function boot(): void
    {
        static::saving(function ($model) {
            if ($model->exists) {
                $model->updated_by = auth('api')->user() ? auth('api')->user()->getAuthIdentifier() : 1;
            } else {
                $model->created_by = auth('api')->user() ? auth('api')->user()->getAuthIdentifier() : 1;
                if ($model->hasCompanyId) {
                    if (
                        config('app.env') === 'testing' ||
                        config('app.env') === 'documentation' ||
                        PHP_SAPI === 'cli'
                    ) {
                        Log::info('entrou aqui ');
                        $model->company_id = 1;
                    } else {
                        $model->company_id = config('current_company_id') ?? auth('api')->user()->company_id;
                    }
                }
            }
        });
        parent::boot();
    }

    /**
     * Returns if the record is active according to the deleted_at field
     */
    protected function isActive(): Attribute
    {
        return new Attribute(
            get: fn() => empty($this->deleted_at),
        );
    }
}
