<?php

namespace App\Models;

use App\Enums\CostType;
use App\Enums\EmployeeType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmEmployee extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    protected $casts = [
        'cost_type' => CostType::class,
        'employee_type' => EmployeeType::class,
    ];

    public function owner(): BelongsTo {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function costs(): HasMany {
        return $this->hasMany(FarmEmployeeOperationCost::class, 'employee_id', 'id');
    }

    public function fullName(): Attribute {
        return new Attribute(fn() => "$this->name $this->surname");
    }

    public function fullNameWithType(): Attribute {
        return new Attribute(fn() => "$this->name $this->surname ({$this->employee_type->getLabel()})");
    }

    public function salaryText(): Attribute {
        return new Attribute(fn() => "$this->salary {$this->cost_type->getLabel()}");
    }
}
