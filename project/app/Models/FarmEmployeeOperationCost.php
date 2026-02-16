<?php

namespace App\Models;

use App\Enums\CostType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmEmployeeOperationCost extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'cost_type' => CostType::class,
    ];

    public function operationType(): BelongsTo {
        return $this->belongsTo(Codifier::class, 'operation_type_code', 'code');
    }

    public function employee(): BelongsTo {
        return $this->belongsTo(FarmEmployee::class, 'employee_id', 'id');
    }
}
