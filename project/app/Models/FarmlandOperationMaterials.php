<?php

namespace App\Models;

use App\Enums\MaterialAmountType;
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
}
