<?php

namespace App\Models;

use App\Enums\MaterialAmountType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FarmlandOperationMaterials extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'material_amount_type' => MaterialAmountType::class,
        'material_amount' => 'double',
    ];

    public function operation(): BelongsTo {
        return $this->belongsTo(FarmlandOperation::class, 'operation_id', 'id');
    }

    public function material(): MorphTo {
        return $this->morphTo();
    }

    public function costs(): Attribute {
        return Attribute::get(function() {
            $baseCosts = $this->material->cost_per_unit * $this->material->unit_type->convertTo($this->material_amount, $this->material_amount_type->unitType());
            return $baseCosts * ($this->material_amount_type->isPerHectare() ? $this->operation->farmlandArea : 1);
        })->shouldCache();
    }
}
