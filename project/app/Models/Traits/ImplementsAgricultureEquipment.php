<?php

namespace App\Models\Traits;

use App\Enums\DefinedEquipmentTypes;
use App\Enums\DriveType;
use App\Enums\WorkAmountType;
use App\Models\Codifier;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait ImplementsAgricultureEquipment {
    public function category(): BelongsTo {
        return $this->belongsTo(Codifier::class, 'equipment_category_code', 'code');
    }

    public function subCategory(): BelongsTo {
        return $this->belongsTo(Codifier::class, 'equipment_sub_category_code', 'code');
    }

    public function isAttachment(): Attribute {
        return new Attribute(fn() => $this->equipment_category_code !== DefinedEquipmentTypes::TRACTOR->value && empty($this->is_self_propelled));
    }

    public function fullName(): Attribute {
        return new Attribute(fn() =>
            "$this->manufacturer $this->model ({$this->category->name})"
        );
    }

    protected function casts(): array
    {
        return [
            'purchased_date' => 'date',
            'is_self_propelled' => 'bool',
            'drive_type' => DriveType::class,
            'work_amount_type' => WorkAmountType::class,
            'work_amount' => 'double',
            'weight' => 'double',
            'required_power' => 'double',
            'power' => 'double',
            'working_speed' => 'double',
            'specific_fuel_consumption' => 'double',
        ];
    }
}
