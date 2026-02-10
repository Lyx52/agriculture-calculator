<?php

namespace App\Models;

use App\Enums\CostType;
use App\Enums\UnitType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmFertilizer extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $casts = [
        'cost_per_unit' => 'double',
        'unit_type' => UnitType::class,
    ];

    public function owner(): BelongsTo {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function costsText(): Attribute {
        return new Attribute(fn() => "$this->cost_per_unit EUR/{$this->unit_type->getLabel()}");
    }
}
