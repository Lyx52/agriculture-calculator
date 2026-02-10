<?php

namespace App\Models;

use App\Enums\UnitType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmPlantProtection extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $casts = [
        'protection_category_codes' => 'json',
        'cost_per_unit' => 'double',
        'unit_type' => UnitType::class,
    ];
    public function owner(): BelongsTo {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function categories(): Attribute {
        return new Attribute(fn() =>
            Codifier::query()->whereIn('code', $this->protection_category_codes)->get()
        );
    }

    public function categoriesText(): Attribute {
        return new Attribute(fn() =>
            Codifier::query()->whereIn('code', $this->protection_category_codes)->pluck('name')->join(', ')
        );
    }

    public function costsText(): Attribute {
        return new Attribute(fn() => "$this->cost_per_unit EUR/{$this->unit_type->getLabel()}");
    }

    public function productName(): Attribute {
        return new Attribute(fn() => "$this->name ({$this->categoriesText})");
    }
}
