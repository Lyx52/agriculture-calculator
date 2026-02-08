<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Farmland extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    public function farm(): BelongsTo {
        return $this->belongsTo(Farm::class, 'farm_id', 'id');
    }

    public function technology(): BelongsTo {
        return $this->belongsTo(Codifier::class, 'agriculture_technology_code', 'code');
    }

    public function owner(): HasOneThrough {
        return $this->hasOneThrough(User::class, Farm::class, 'id', 'id', 'farm_id', 'owner_id');
    }

    public function operations(): HasMany
    {
        return $this->hasMany(FarmlandOperation::class, 'farmland_id', 'id');
    }

    public function materials(): HasManyThrough
    {
        return $this->hasManyThrough(FarmlandOperationMaterials::class, FarmlandOperation::class, 'farmland_id', 'operation_id', 'id');
    }

    public function latestCropName(): Attribute {
        return new Attribute(function () {
            return $this->materials->where('material_type', FarmCrop::class)->sortByDesc('operation.operation_date')->first()?->material?->cropName ?? '';
        });
    }
}
