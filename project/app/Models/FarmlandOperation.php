<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmlandOperation extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    protected $casts = [
        'operation_date' => 'date'
    ];

    public function operation(): BelongsTo {
        return $this->belongsTo(Codifier::class, 'operation_code', 'code');
    }

    public function farmland(): BelongsTo {
        return $this->belongsTo(Farmland::class, 'farmland_id', 'id');
    }

    public function employee(): BelongsTo {
        return $this->belongsTo(FarmEmployee::class, 'employee_id', 'id');
    }

    public function farm(): HasOneThrough {
        return $this->hasOneThrough(Farm::class, Farmland::class, 'id', 'id', 'farmland_id', 'farm_id');
    }
}
