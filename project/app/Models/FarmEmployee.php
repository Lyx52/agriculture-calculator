<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmEmployee extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function employeeType(): BelongsTo {
        return $this->belongsTo(Codifier::class, 'employee_type_code', 'code');
    }

    public function owner(): BelongsTo {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function salaryCostType(): BelongsTo {
        return $this->belongsTo(Codifier::class, 'salary_cost_type_code', 'code');
    }

    public function fullName(): Attribute {
        return new Attribute(fn() => "$this->name $this->surname");
    }

    public function salaryText(): Attribute {
        return new Attribute(fn() => "$this->salary {$this->salaryCostType->name}");
    }
}
