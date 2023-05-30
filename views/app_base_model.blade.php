@php
    echo "<?php".PHP_EOL;
@endphp

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\CustomSoftDelete;

abstract class BaseModel extends Model
{
    use HasFactory, CustomSoftDelete;

    /**
     * This attribute checks if the table is multi tenancy
     */
    protected bool $hasCompanyId = true;

    /**
     * Informs which relations should be used in the search
     */
    protected array $relationsBySearch = [];

    /**
     * Informs which fields should not be saved in uppercase if the trait is used
     */
    protected array $noUpper = [];

    /**
     * Add the is_active field to make it easier to validate it on the front
     */
    protected $appends = [
        'is_active',
    ];

    /**
     * Returns if the record is active according to the deleted_at field
     */
    protected function isActive(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => empty($this->deleted_at),
        );
    }

    /**
     * Returns if the company ID is used in the model
     */
    public function hasCompanyId(): bool
    {
        return $this->hasCompanyId;
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
     * Returns the field types to be used in queries
     */
    public static function getCastsStatic(): array
    {
        return (new static())->getCasts();
    }

    /**
     * Function responsible for returning the type of the field for the query
     */
    public static function getFieldType(string $field): bool
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
     * Bootstrap the model and its traits
     */
    protected static function boot()
    {
        static::saving(function ($model) {
            if ($model->exists) {
                $model->updated_by = auth('api')->user() ? auth('api')->user()->getAuthIdentifier() : 1;
            } else {
                $model->created_by = auth('api')->user() ? auth('api')->user()->getAuthIdentifier() : 1;
                if ($model->hasCompanyId) {
                    if (config('app.env') === 'testing' || PHP_SAPI === 'cli') {
                        $model->company_id = 1;
                    } else {
                        $model->company_id = config('current_company_id') ?? auth('api')->user()->company_id;
                    }
                }
            }
            //             Descomentar quando definir como será usado o log
            //             parent::saveLog($model, 'saving');
        });
        parent::boot();
    }
}