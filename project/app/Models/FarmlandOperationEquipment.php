<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmlandOperationEquipment extends Model
{
    public $timestamps = false;
    protected $guarded = ['id'];

    public function operation(): BelongsTo {
        return $this->belongsTo(FarmlandOperation::class, 'operation_id', 'id');
    }

    public function equipment(): BelongsTo {
        return $this->belongsTo(FarmAgricultureEquipment::class, 'equipment_id', 'id');
    }

    public function attachment(): BelongsTo {
        return $this->belongsTo(FarmAgricultureEquipment::class, 'attachment_id', 'id');
    }
}
